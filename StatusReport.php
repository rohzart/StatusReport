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

	/*** prefer setting up the access level manually in case the enum value is already configured/taken. */
	// function install() {
    //     $enum = config_get_global('access_levels_enum_string');
    //     if (strpos($enum, 'stakeholder') === false) {
    //         $enum .= ',35:stakeholder';
    //         config_set_global('access_levels_enum_string', $enum);
	// 		config_set_global('view_bug_threshold', array(10, 25, 35));
	// 		config_set_global('report_bug_threshold', array(25, 35));
    //     }
    // }

	/*** Default plugin configuration.	 */
	function config() {
		return array(
			'StatusReport_default_hourly_rate'		=> 0,
			'StatusReport_project_hourly_rates' 	=> array(),
			'StatusReport_project_last_billed_dates'=> array(),
			'StatusReport_dates_to_send_csv'			=> '',
			'StatusReport_admin_email'				=> '',
			'StatusReport_ignore_project_ids_csv'	=> ''
			);
	}

	function hooks() {
        return array(
            'EVENT_MENU_MAIN' => 'menu',
			'EVENT_CRONJOB' => 'cronjob'
        );
    }

	/*** When logged in, only the managers will see the menu item */
	function menu() {
        $t_menu[] = array(
            'title' => $this->name,
            'url' => plugin_page( 'status_report' ),
            'access_level' => MANAGER,
            'icon' => 'fa-money'
        );
        return $t_menu;
    }

	function reportDownload() {
		return array('<a href="'. plugin_page( 'status_report.php' ) . '">' . lang_get( 'StatusReport_download' ) . '</a>' );
	}

	function cronjob() {
		return array(
			'cronjob' => require_once( dirname( __FILE__, 1 ) . DIRECTORY_SEPARATOR . 'scripts/status_report_cron.php' ),
			'frequency' => 'daily',
			'description' => lang_get( 'StatusReport_cronjob_desc' )
		);
	}

}
