<?php
function get_monthly_time_report($hourly_rate) {
	$start_date = date('Y-m-01 00:00:00');
	$end_date = date('Y-m-t 23:59:59');

	$query = "SELECT 
					p.id AS project_id,
					p.name AS project_name,
					ROUND(SUM(bn.time_tracking) / 60, 2) AS total_hours,
					ROUND((SUM(bn.time_tracking) / 60) * " . db_param() . ", 2) AS cost
				FROM 
					bugnote bn
				JOIN 
					bug b ON bn.bug_id = b.id
				JOIN 
					project p ON b.project_id = p.id
				WHERE 
					bn.time_tracking > 0
					AND FROM_UNIXTIME(bn.last_modified) BETWEEN " . db_param() . " AND " . db_param() . "
				GROUP BY 
					p.name;";

	$result = db_query($query, array($hourly_rate, $start_date, $end_date));

	$report = [];
	while ($row = db_fetch_array($result)) {
		$report[] = [
			'project_id' => $row['project_id'],
			'project_name' => $row['project_name'],
			'total_hours' => $row['total_hours'],
			'cost' => $row['cost']
		];
	}

	return $report;
}

function send_monthly_report_email($email, $report) {
	if($report === null || empty($report)) {
		return;
	}
	
	if($email){
		$body = "Monthly Time Tracking Report:\n\n";
		foreach ($report as $entry) {
			$body .= "Project: {$entry['project_name']} - Hours: {$entry['total_hours']} - Cost: {$entry['cost']} USD \n";
		}

		$t_subject = 'Monthly Time Report - All Projects';

		email_store($email, $t_subject, $body );
		email_send_all();
	}
}

function send_monthly_report_email_to_stakeholders($report) {
	if($report === null || empty($report)) {
		return;
	}

	foreach ($report as $entry) {
		$t_project_id = $entry['project_id'];

		// Get all users with STAKEHOLDER access (or higher) for this project
		$t_stakeholders = project_get_all_user_rows($t_project_id, STAKEHOLDER);

		foreach ($t_stakeholders as $stakeholder) {
			$t_username = user_get_name($stakeholder['id']);
			$t_email = user_get_email($stakeholder['id']);
			$t_subject = "Monthly Time Report - {$entry['project_name']}";

			$body = "Hello {$t_username},\n\n";
			$body .= "Monthly Time Tracking Report for project '{$entry['project_name']}':\n";
			$body .= "Hours: {$entry['total_hours']} | Cost: {$entry['cost']} USD\n";

			email_store($t_email, $t_subject, $body);
		}
	}
	email_send_all();
}
?>