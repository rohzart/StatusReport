<?php

require_once(dirname( __FILE__, 2 ) . DIRECTORY_SEPARATOR . 'core/status_report_api.php');

access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'StatusReport_plugin_title' ) );
layout_page_begin( 'status_report.php' );

$report = get_monthly_time_report();
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
</div>

<?php
layout_page_end();
