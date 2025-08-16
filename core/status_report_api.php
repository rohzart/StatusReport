<?php
function get_project_hourly_rate($project_id, $default_rate) {
    $project_rates = plugin_config_get('StatusReport_project_hourly_rates');
    return isset($project_rates[$project_id]) ? $project_rates[$project_id] : $default_rate;
}

function get_monthly_time_report() {
    $report = [];

    $default_hourly_rate = config_get('plugin_StatusReport_StatusReport_default_hourly_rate');
    $projects = project_get_all_rows();
    foreach ($projects as $project) {
        $project_last_billed_dates = plugin_config_get('StatusReport_project_last_billed_dates');
        $billed_date = isset($project_last_billed_dates[$project['id']]) ? $project_last_billed_dates[$project['id']] : '';
        if (!empty($billed_date)) {
            $start_date = date('Y-m-d', strtotime($billed_date));
        } else {
            $start_date = date('Y-m-01 00:00:00');
        }
        $end_date = date('Y-m-t 23:59:59');

        $query = "SELECT 
                p.id AS project_id,
                p.name AS project_name,
                ROUND(SUM(bn.time_tracking) / 60, 4) AS total_hours
            FROM 
                bugnote bn
            JOIN 
                bug b ON bn.bug_id = b.id
            JOIN 
                project p ON b.project_id = p.id
            WHERE 
                bn.time_tracking > 0
                AND FROM_UNIXTIME(bn.last_modified) BETWEEN " . db_param() . " AND " . db_param() . "
                AND p.id = " . db_param() . "
            GROUP BY 
                p.id, p.name;";

        $result = db_query($query, array($start_date, $end_date, $project['id']));

        while ($row = db_fetch_array($result)) {
            $hourly_rate = get_project_hourly_rate($row['project_id'], $default_hourly_rate);
            $report[] = [
                'project_id' => $row['project_id'],
                'project_name' => $row['project_name'],
                'total_hours' => $row['total_hours'],
                'cost' => round($row['total_hours'] * $hourly_rate, 2)
            ];
        }

        return $report;
    }
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