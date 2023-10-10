# Yireo QueryDebugger

This Magento 2 module purposefully forces the second argument `$logQuery` of a collection its `load($printQuery = false, $logQuery = false)` method to be always true (so `$logQuery = true`). This logs any query related to collections to the `var/log/debug.log` file. Use with care.