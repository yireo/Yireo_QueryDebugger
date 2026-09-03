# Yireo QueryDebugger

<!-- badges.specs.start -->
![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/module-query-debugger)
<!-- badges.specs.end -->


This Magento 2 module purposefully forces the second argument `$logQuery` of a collection its `load($printQuery = false, $logQuery = false)` method to be always true (so `$logQuery = true`). This logs any query related to collections to the `var/log/debug.log` file. Use with care.
## Current status

<!-- badges.test.start -->
![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_QueryDebugger/compile.yml?label=compile)
<!-- badges.test.end -->
