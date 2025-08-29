<?php

require_once(dirname( __FILE__, 2 ) . DIRECTORY_SEPARATOR . 'core/status_report_api.php');

access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'StatusReport_plugin_title' ) );
layout_page_begin( 'status_report.php' );

$report = get_report();
?>

<div class="col-md-12 col-xs-12">
	<h1>Status Report</h1>

	<a href="<?php echo plugin_page( 'status_report_csv.php' ) ?>" class="btn btn-primary btn-sm btn-white btn-round">Download CSV</a>

	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th>Project</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Hours</th>
				<th>Hourly Rate</th>
				<th>Cost(USD)</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($report as $entry) {
		?>
			<tr>
				<td><?php echo htmlspecialchars($entry['project_name']); ?></td>
				<td><?php echo htmlspecialchars($entry['start_date']); ?></td>
				<td><?php echo htmlspecialchars($entry['end_date']); ?></td>
				<td><?php echo htmlspecialchars($entry['total_hours']); ?></td>
				<td><?php echo htmlspecialchars($entry['hourly_rate']); ?></td>
				<td><?php echo htmlspecialchars($entry['cost']); ?></td>
			</tr>
		<?php
		}
		?>
		</tbody>
		</table>
	</div>
	<p class="text-muted">
		This report is generated based on the data available in the system. Please ensure that all project data is up-to-date for accurate reporting. (viz. the configured last billed dates)
		<a href="<?php echo plugin_page( 'config.php' ) ?>" class="btn btn-primary btn-sm btn-white btn-round">Configure</a>
	</p>
	<h3>Users</h3>
	<div class="row">
		<?php
			foreach ($report as $entry) {
				$t_project_id = $entry['project_id'];
				$t_stakeholders = project_get_all_user_rows($t_project_id, STAKEHOLDER);
				?>
				<div class="col-md-4">
				<h4><?php echo htmlspecialchars($entry['project_name']); ?></h2>
				<?php
				// Display each stakeholder's username and access level
				foreach ($t_stakeholders as $stakeholder) {
					$t_username = user_get_name($stakeholder['id']);
					$t_access_level = $stakeholder['access_level'];
					$access_level_name = get_enum_element('access_levels', $t_access_level);
					$access_level_name == "stakeholder"
					?>
					
					<p>
						<?php 
						echo $t_username . ": " . $access_level_name;
						?>
					</p>
					<?php
				}
				?>
				</div>
				<?php
			}
		?>
	</div>
</div>

<?php
layout_page_end();
