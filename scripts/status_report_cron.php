<?php
# Make sure this script doesn't run via the webserver
if( php_sapi_name() != 'cli' ) {
	echo "It is not allowed to run this script through the webserver.\n";
	exit( 1 );
}

require_once( dirname( __FILE__, 4 ) . DIRECTORY_SEPARATOR . 'core.php' );
require_once( dirname( __FILE__, 2 ) . DIRECTORY_SEPARATOR . 'core/status_report_api.php' );

// Get the last dispatch timestamp from plugin config
$last_dispatch = plugin_config_get('StatusReport_last_report_dispatch', null, false, null);

// Current time and date
$now = time();

// Dispatch only if no previous dispatch or more than 24 hours have passed
if ($last_dispatch === null || ($now - $last_dispatch) >= 86400) {  // 86400 seconds = 24 hours

	$dates_to_send_csv = plugin_config_get('StatusReport_dates_to_send_csv');
	if (empty($dates_to_send_csv)) {
		echo "No dates configured for sending the report.\n";
		exit(0);
	}

	$dates = explode(',', $dates_to_send_csv);
	$current_date = date('d');

	// if 'last' is specified, treat it as the last day of the month
	if (in_array('last', $dates)) {
		$last_day_of_month = date('t');
		// replace 'last' with the actual last day of the month
		$dates = array_map(function($date) use ($last_day_of_month) {
			return $date === 'last' ? $last_day_of_month : $date;
		}, $dates);
	}
	$current_date = (int)$current_date;
	if (!in_array($current_date, $dates)) {
		echo "Current date is not in the configured dates for sending the report.\n";
		exit(0);
	}

	$report = get_report();

	email_report_to_stakeholders($report);
	email_report_to_admin($report);

	// Update the last dispatch time in config
	plugin_config_set('StatusReport_last_report_dispatch', $now);
}