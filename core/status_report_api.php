<?php
function get_project_hourly_rate($project_id, $default_rate) {
    $project_rates = plugin_config_get('StatusReport_project_hourly_rates');
    return isset($project_rates[$project_id]) ? $project_rates[$project_id] : $default_rate;
}

function get_project_last_billed_date($project_id, $default_start_date) {
    $project_last_billed_dates = plugin_config_get('StatusReport_project_last_billed_dates');
    return isset($project_last_billed_dates[$project_id]) ? date('Y-m-d', strtotime($project_last_billed_dates[$project_id])) : $default_start_date;
}

function get_report() {
    $report = [];

    $ignore_project_ids_csv = plugin_config_get('StatusReport_ignore_project_ids_csv');
    $default_hourly_rate = plugin_config_get('plugin_StatusReport_StatusReport_default_hourly_rate');
    $default_start_date = date('Y-m-01 00:00:00');
    $projects = project_get_all_rows();

    foreach ($projects as $project) {
        if (isset($project['enabled']) && $project['enabled'] && !in_array($project['id'], explode(',', $ignore_project_ids_csv))) {
            $hourly_rate = get_project_hourly_rate($project['id'], $default_hourly_rate);
            $start_date = get_project_last_billed_date($project['id'], $default_start_date);
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
                    AND FROM_UNIXTIME(bn.last_modified) BETWEEN " . db_param() . " AND " . db_param() . " AND p.id = " . db_param();

            $result = db_query($query, array($start_date, $end_date, $project['id']));

            while ($row = db_fetch_array($result)) {
                array_push($report, [
                    'project_id' => $row['project_id'],
                    'project_name' => $row['project_name'],
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'total_hours' => $row['total_hours'],
                    'hourly_rate' => $hourly_rate,
                    'cost' => round($row['total_hours'] * $hourly_rate, 2)
                ]);
            }
        }
    }
    return $report;
}

function email_report_to_admin($report) {
    $email = plugin_config_get('StatusReport_admin_email');

    if($report === null || empty($report) || $email===null || empty($email)) {
		return;
	}
        
    $template_path = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'admin.txt';
    $template_path_of_report_section = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'admin_report_section.txt';

    // Read the template file
    $template_content = file_get_contents($template_path);
    $template_report_section_content = file_get_contents($template_path_of_report_section);

    // Email subject and content
    $t_subject = "Status Report - All Projects";

    foreach ($report as $entry) {
        // Replace placeholders with actual data
        $report_section = str_replace(
            ['{project_name}', '{start_date}', '{end_date}', '{total_hours}', '{hourly_rate}', '{cost}', '{sender_name}'],
            [$entry['project_name'], date('F d, Y', strtotime($entry['start_date'])), date('F d, Y', strtotime($entry['end_date'])), $entry['total_hours'], $entry['hourly_rate'], '$' . number_format($entry['cost'], 2), 'Stakeholder Name'],
            $template_report_section_content
        );
    }
    $body = str_replace('{report_section}', $report_section, $template_content);
    
    email_store($email, $t_subject, $body);
    email_send_all();
}

function email_report_to_stakeholders($report) {
	if($report === null || empty($report)) {
		return;
	}

    $template_path = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'stakeholders.txt';

    // Read the template file
    $template_content = file_get_contents($template_path);

	foreach ($report as $entry) {
		$t_project_id = $entry['project_id'];

		// Get all users with STAKEHOLDER access (or higher) for this project
		$t_stakeholders = project_get_all_user_rows($t_project_id, STAKEHOLDER);

		foreach ($t_stakeholders as $stakeholder) {
            // confirm the user has only stakeholder access
            if (access_get_global_level($stakeholder['id']) <= STAKEHOLDER) {
                $t_username = user_get_name($stakeholder['id']);
                $t_email = user_get_email($stakeholder['id']);
                $t_project_name = $entry['project_name'];
                $t_start_date = date('F d, Y', strtotime($entry['start_date']));
                $t_end_date = date('F d, Y', strtotime($entry['end_date']));
                $t_total_hours = $entry['total_hours'];
                $t_hourly_rate = $entry['hourly_rate'];
                $t_cost = '$' . number_format($entry['cost'], 2);
                // strtotime($entry['start_date']))
                // Email subject and content
                
                $t_subject = "Status Report - {$t_project_name} (from {$t_start_date} to {$t_end_date})";
                $body = str_replace(
                    ['{project_name}', '{start_date}', '{end_date}', '{total_hours}', '{hourly_rate}', '${cost}', '{stakeholder_name}'],
                    [$t_project_name, $t_start_date, $t_end_date, $t_total_hours, $t_hourly_rate, $t_cost, $t_username],
                    $template_content
                );

                // email_store($t_email, $t_subject, $body);
                $email = plugin_config_get('StatusReport_admin_email');
                email_store($email, $t_subject, $body);
            }
		}
	}
	email_send_all();
}

?>