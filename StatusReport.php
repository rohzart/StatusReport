<?php
class StatusReportPlugin extends MantisPlugin {

	function register() {
		$this->name        = 'StatusReport';
		$this->description = lang_get( 'StatusReport_plugin_desc' );
		$this->version     = '2.30';
		$this->requires    = array('MantisCore'       => '2.0.0',);
        $this->author 	   = '@rohzart';
		$this->url         = 'https://github.com/rohzart/mantisbtStatusReports';
		$this->page        = 'config';
	}

	/*** Default plugin configuration.	 */
	function config() {
		return array(
			'StatusReport_hourly_rate'				=> 0,
			'StatusReport_days_to_send_csv'			=> '',
			'StatusReport_admin_email'				=> '',
			'StatusReport_ignore_project_ids_csv'	=> ''
			);
	}

	function hooks() {
        return array(
            'EVENT_MENU_MAIN' => 'menu',
        );
    }

	function menu() {
        $t_menu[] = array(
            'title' => $this->name,
            'url' => plugin_page( 'status_report' ),
            'access_level' => MANAGER,
            'icon' => 'fa-smile-o'
        );
        return $t_menu;
    }

	function reportDownload() {
		return array('<a href="'. plugin_page( 'status_report.php' ) . '">' . lang_get( 'StatusReport_download' ) . '</a>' );
	}

}
