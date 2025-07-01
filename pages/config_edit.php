<?php
form_security_validate( 'plugin_format_config_edit' );
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$f_StatusReport_hourly_rate		        	= gpc_get_int('StatusReport_hourly_rate',0);
$f_StatusReport_days_to_send_csv			= gpc_get_string('StatusReport_days_to_send_csv', '');
$f_StatusReport_admin_email        		= gpc_get_string('StatusReport_admin_email', '');
$f_StatusReport_ignore_project_ids_csv		= gpc_get_string('StatusReport_ignore_project_ids_csv', '');

plugin_config_set('StatusReport_hourly_rate'	    		, $f_StatusReport_hourly_rate);
plugin_config_set('StatusReport_days_to_send_csv'			, $f_StatusReport_days_to_send_csv);
plugin_config_set('StatusReport_admin_email'		        , $f_StatusReport_admin_email);
plugin_config_set('StatusReport_ignore_project_ids_csv'		, $f_StatusReport_ignore_project_ids_csv);

print_header_redirect( plugin_page( 'config', TRUE ) );
