# MantisBT StatusReport plugin

This is free and unencumbered software released into the public domain.

For more information, please refer to https://unlicense.org

## Description

Generates and emails brief billing status reports.

## Installation

Upload the whole folder into your `plugins/` folder in the mantis installation so that you e.g. have `MANTIS_INSTALLATION/plugins/StatusReport/StatusReport.php`. After that the plugin should show up on the `manage_plugin_page.php` page in the mantis settings. There you can simply install it.

Then each MANAGER user can access it via the menu.

### Cron job

Set it to once a week/month or as per your liking.
```
/usr/local/bin/php /home2/bearso19/public_html/office/mantis/plugins/StatusReport/scripts/status_report_cron.php
```