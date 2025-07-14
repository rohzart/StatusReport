<?php
# Make sure this script doesn't run via the webserver
if( php_sapi_name() != 'cli' ) {
	echo "It is not allowed to run this script through the webserver.\n";
	exit( 1 );
}

require_once( dirname( __FILE__, 4 ) . DIRECTORY_SEPARATOR . 'core.php' );
require_once(dirname( __FILE__, 2 ) . DIRECTORY_SEPARATOR . 'core/status_report_api.php');

$hourly_rate = config_get('plugin_StatusReport_StatusReport_default_hourly_rate');
$email = config_get('plugin_StatusReport_StatusReport_admin_email');

$report = get_monthly_time_report($hourly_rate);

send_monthly_report_email_to_stakeholders($report);
send_monthly_report_email($email, $report);