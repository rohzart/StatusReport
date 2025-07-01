<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'StatusReport_plugin_title' ) );
layout_page_begin( 'config_page.php' );
print_manage_menu();

?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 
<br/>
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">
<?php echo form_security_field( 'plugin_format_config_edit' ) ?>
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo lang_get( 'StatusReport_plugin_title' ) . ': ' . lang_get( 'StatusReport_config' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 

<tr>
	<td class="category" width="60%">
		<?php echo lang_get( 'StatusReport_hourly_rate' ) ?>
	</td>
		<td  width="20%">
			<input type="text" name="StatusReport_hourly_rate" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_hourly_rate' )?>" >
	</td>
	</td><td>
</tr>

<tr>
	<td class="category" width="60%">
		<?php echo lang_get( 'StatusReport_days_to_send_csv' ) ?>
	</td>
		<td  width="20%">
			<input type="text" name="StatusReport_days_to_send_csv" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_days_to_send_csv' )?>" >
	</td>
	</td><td>
</tr>

<tr>
	<td class="category" width="60%">
		<?php echo lang_get( 'StatusReport_admin_email' ) ?>
	</td>
		<td  width="20%">
			<input type="text" name="StatusReport_admin_email" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_admin_email' )?>" >
	</td>
	</td><td>
</tr>

<tr>
	<td class="category" width="60%">
		<?php echo lang_get( 'StatusReport_ignore_project_ids_csv' ) ?>
	</td>
		<td  width="20%">
			<input type="text" name="StatusReport_ignore_project_ids_csv" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_ignore_project_ids_csv' )?>" >
	</td>
	</td><td>
</tr>

</table>
</div>
</div>
<div class="widget-toolbox padding-8 clearfix">
	<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo lang_get( 'StatusReport_change_configuration' )?>" />
</div>
</div>
</div>
</form>
</div>
</div>
 
<?php
layout_page_end();