<?php

require_once(dirname( __FILE__, 2 ) . DIRECTORY_SEPARATOR . 'core/status_report_api.php');

$hourly_rate = config_get('plugin_StatusReport_StatusReport_hourly_rate');
$report = get_monthly_time_report($hourly_rate);

$t_separator = ',';

if (!$report) {
	echo "No records found.";
	return;
} else {
	$body .= "Project".$t_separator."Hours".$t_separator."Cost(USD) \n";
	foreach ($report as $entry) {
		$body .= $entry['project_name'].$t_separator.$entry['total_hours'].$t_separator.$entry['cost']."\n";
	}
	$filename = "status_report_".date("Ymd_Hi").".csv";
	header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
	echo $body;
}
// header( 'Content-Disposition: attachment; filename="no_results.txt"' );
// echo $content;