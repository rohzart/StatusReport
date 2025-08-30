# MantisBT StatusReport plugin

This is free and unencumbered software released into the public domain.

For more information, please refer to https://unlicense.org

## Description

Generates and emails brief billing status reports.

The report overview page can be accessed by role allowed access to manage plugins. 

The hourly rates and last billed dates are configured in this plugin's configuration.

The default threshold is set to `ADMINISTRATOR`.

```php
/**
 * Threshold needed to manage plugins.
 *
 * @global int $g_manage_plugin_threshold
 */
$g_manage_plugin_threshold = ADMINISTRATOR;
```

## Installation

Upload the whole folder into your `plugins/` folder in the mantis installation so that you e.g. have `MANTIS_INSTALLATION/plugins/StatusReport/StatusReport.php`. After that the plugin should show up on the `manage_plugin_page.php` page in the mantis settings. There you can simply install it.

Then each STAKEHOLDER user can access it via the menu.

## Configuration

Add a new 'access level' called "STAKEHOLDER" to the config.php file overwriting the variable `$g_access_levels_enum_string`:

```php
#########################
# MantisBT Enum Strings #
#########################

/**
 * Access levels enumeration.
 *
 * Status from $g_status_index-1 to 79 are used for the onboard customization
 * (if enabled) directly use MantisBT to edit them.
 * @global string $g_access_levels_enum_string
 */
$g_access_levels_enum_string = '10:viewer,25:reporter,35:stakeholder,40:updater,55:developer,70:manager,90:administrator';
```

### Cron job

The plugin is hooked to Mantis' cron job event called `EVENT_CRONJOB` to dispatch the email reports.

Alternatively, you may run the cron job manually from the command line:

Set it to once a week/month, any frequency as per your liking.
```
/usr/local/bin/php /home2/bearso19/public_html/office/mantis/plugins/StatusReport/scripts/status_report_cron.php
```

### Timezones

- Your PC's time zone should match what's set in the preference.
- The emails dispatched to the stakeholders have all timestamps following their timezone preference.
- The plugin's admin email doesn't necessarily have any association with mantis users; the timestamps in reports dispatched to that email follow UTC.
- The timestamps in the report pages, for the sake of consistency across different users, also follow UTC.
- But for the sake of conveniency, when setting the last billed dates in the config, the logged in users timezone preferences are observerd.