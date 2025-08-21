<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'StatusReport_plugin_title' ) );
layout_page_begin( 'config_page.php' );
print_manage_menu();

?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container"> 
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
	<td class="category">
		<?php echo lang_get( 'StatusReport_default_hourly_rate' ) ?>
	</td>
		<td>
			<input type="text" name="StatusReport_default_hourly_rate" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_default_hourly_rate' )?>">
	</td>
	</td><td>
</tr>
<tr>
    <td class="category">
        <?php echo lang_get( 'StatusReport_project_hourly_rates_and_last_billed_dates' ) ?>
    </td>
    <td>
		<table class="table table-bordered table-condensed" style="width: auto;">
		<thead>
			<tr>
				<th><?php echo lang_get( 'StatusReport_Project_Label' ) ?></th>
				<th><?php echo lang_get( 'StatusReport_Hourly_Rate' ) ?></th>
				<th><?php echo lang_get( 'StatusReport_Last_Billed_Date' ) ?></th>
			</tr>
		</thead>
		<tbody>
        <?php
   		$ignore_project_ids_csv = plugin_config_get('StatusReport_ignore_project_ids_csv');
        $projects = project_get_all_rows();
        foreach ($projects as $project) {
        	if (isset($project['enabled']) && $project['enabled'] && !in_array($project['id'], explode(',', $ignore_project_ids_csv))) {
				$project_rates = plugin_config_get('StatusReport_project_hourly_rates');
				$rate = isset($project_rates[$project['id']]) ? $project_rates[$project['id']] : '';

				$project_last_billed_dates = plugin_config_get('StatusReport_project_last_billed_dates');
				$billed_date = isset($project_last_billed_dates[$project['id']]) ? $project_last_billed_dates[$project['id']] : '';
				?>
			<tr>
				<td class="text-nowrap"><?php echo string_display_line($project['name'] . ' (' . $project['id'] . ')') ?></td>
				<td class="text-nowrap"><input type="text" name="project_rate_<?php echo $project['id'] ?>" size="10" maxlength="10" value="<?php echo $rate ?>"></td>
				<td class="text-nowrap"><input type="date" name="project_last_billed_date_<?php echo $project['id'] ?>" size="10" value="<?php echo $billed_date ?>"></td>
			</tr>
				<?php
			}
        }
        ?>
		</tbody>
		</table>
    </td>
    <td></td>
</tr>
<tr>
	<td class="category">
		<?php echo lang_get( 'StatusReport_dates_to_send_csv' ) ?>
	</td>
		<td>
			<input type="text" name="StatusReport_dates_to_send_csv" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_dates_to_send_csv' )?>">
			<div class="help-block">
				<?php
					$last_dispatch = plugin_config_get('StatusReport_last_report_dispatch', null);
					if ($last_dispatch !== null) {
						$user_id = auth_get_current_user_id();
						$user_timezone = user_pref_get_pref( $user_id, 'timezone' );
						$date = new DateTime('@' . $last_dispatch);
						$date->setTimezone(new DateTimeZone($user_timezone));
						$last_dispatch = $date->format('Y-m-d H:i:s') . ' (' . $date->format('T') . ')';
					} else {
						$last_dispatch = 'Never';
					}
				?>
				<p><?php echo lang_get( 'StatusReport_last_report_dispatch' ) . ": " . $last_dispatch ?></p>
				<?php echo lang_get( 'StatusReport_dates_to_send_csv_description' ) ?>
			</div>
	</td>
	</td><td>
</tr>

<tr>
	<td class="category">
		<?php echo lang_get( 'StatusReport_test_mode' ) ?>
	</td>
		<td>
			<?php 
			$checked = plugin_config_get('StatusReport_test_mode', false);
			?> 
			<input type="checkbox" name="StatusReport_test_mode" <?php echo $checked ? 'checked' : '' ?> />
			<div class="help-block">
			<?php echo lang_get( 'StatusReport_test_mode_description' ) ?>
			</div>
	</td>
	</td><td>
</tr>

<tr>
	<td class="category">
		<?php echo lang_get( 'StatusReport_admin_email' ) ?>
	</td>
		<td>
			<input type="text" name="StatusReport_admin_email" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_admin_email' )?>">
	</td>
	</td><td>
</tr>

<tr>
	<td class="category">
		<?php echo lang_get( 'StatusReport_ignore_project_ids_csv' ) ?>
	</td>
		<td>
			<input type="text" name="StatusReport_ignore_project_ids_csv" size="50" maxlength="50" value="<?php echo plugin_config_get( 'StatusReport_ignore_project_ids_csv' )?>">
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