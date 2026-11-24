# Yireo QueryDebugger

<!-- badges.specs.start -->
![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/module-query-debugger)
<!-- badges.specs.end -->


This Magento 2 module logs the SQL query of every database collection (`Magento\Framework\Data\Collection\AbstractDb::load()`) to the file `var/log/query_debugger.log`. It is meant for development only.

## Configuration
The logging is disabled by default. It only works when both conditions are met:

- Magento runs in developer mode (`bin/magento deploy:mode:set developer`);
- The setting **Stores > Configuration > Advanced > Developer > Query Debugger > Enabled** is set to **Yes**:

```bash
bin/magento config:set dev/query_debugger/enabled 1
```

The setting `dev/query_debugger/max_queries` (default `500`, use `0` for no limit) limits the number of queries logged per request. Line breaks within queries are replaced with spaces, so that every query takes up exactly one line.

## Warning
- The logged queries contain the actual values used in filters: email addresses, names, addresses, password reset tokens, order data and more. Treat `var/log/query_debugger.log` as sensitive personal data: never use this module in production, never share the log file and delete it when done.
- Every collection load is logged, also of anonymous visitors, cron and CLI. The log file grows quickly: configure `logrotate` or delete the file regularly.
## Current status

<!-- badges.test.start -->
![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/compile.yml?label=compile)
<!-- badges.test.end -->
