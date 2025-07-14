<?php
form_security_validate( 'plugin_format_config_edit' );
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$f_StatusReport_default_hourly_rate		= gpc_get_int('StatusReport_default_hourly_rate', 0);
$f_StatusReport_days_to_send_csv		= gpc_get_string('StatusReport_days_to_send_csv', '');
$f_StatusReport_admin_email        		= gpc_get_string('StatusReport_admin_email', '');
$f_StatusReport_ignore_project_ids_csv  = gpc_get_string('StatusReport_ignore_project_ids_csv', '');

plugin_config_set('StatusReport_default_hourly_rate'	    , $f_StatusReport_default_hourly_rate);
plugin_config_set('StatusReport_days_to_send_csv'			, $f_StatusReport_days_to_send_csv);
plugin_config_set('StatusReport_admin_email'		        , $f_StatusReport_admin_email);
plugin_config_set('StatusReport_ignore_project_ids_csv'		, $f_StatusReport_ignore_project_ids_csv);

$project_rates = array();
$projects = project_get_all_rows();
foreach ($projects as $project) {
    $rate = gpc_get_string('project_rate_' . $project['id'], '');
    if (!empty($rate)) {
        $project_rates[$project['id']] = (float)$rate;
    }
}
plugin_config_set('StatusReport_project_hourly_rates', $project_rates);

print_header_redirect( plugin_page( 'config', TRUE ) );
