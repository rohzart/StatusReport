<?php
# Make sure this script doesn't run via the webserver
if( php_sapi_name() != 'cli' ) {
	echo "It is not allowed to run this script through the webserver.\n";
	exit( 1 );
}

require_once( dirname( __FILE__, 4 ) . DIRECTORY_SEPARATOR . 'core.php' );
require_once(dirname( __FILE__, 2 ) . DIRECTORY_SEPARATOR . 'core/status_report_api.php');

$dates_to_send_csv = plugin_config_get('StatusReport_dates_to_send_csv');
if (empty($dates_to_send_csv)) {
	echo "No dates configured for sending the report.\n";
	exit(0);
}

$dates = explode(',', $dates_to_send_csv);
$current_date = date('Y-m-d');
$current_day = date('d', strtotime($current_date));
if (!in_array($current_day, $dates)) {
	echo "Current date is not in the configured dates for sending the report.\n";
	exit(0);
}

$report = get_report();

email_report_to_stakeholders($report);
email_report_to_admin($report);