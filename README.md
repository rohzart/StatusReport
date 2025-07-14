# MantisBT StatusReport plugin

This is free and unencumbered software released into the public domain.

For more information, please refer to https://unlicense.org

## Description

Generates and emails brief billing status reports.

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

Set it to once a week/month or as per your liking.
```
/usr/local/bin/php /home2/bearso19/public_html/office/mantis/plugins/StatusReport/scripts/status_report_cron.php
```