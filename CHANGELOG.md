# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 27 November 2026
### Added
- Setting `dev/query_debugger/max_queries` to limit the number of queries logged per request
- Documentation of the configuration and a warning about sensitive data in logged queries
- Support for PHP 8.5

### Changed
- The plugin now targets `AbstractDb` collections instead of the generic `Data\Collection` class
- Composer dependency on `magento/framework` constrained to `^103.0` instead of a wildcard; license set to OSL-3.0

### Removed
- Support for PHP 8.1

### Security
- Collection queries are no longer logged unconditionally: logging is disabled by default and only active in developer mode with `dev/query_debugger/enabled`
- Queries are written only to `var/log/query_debugger.log`, no longer leaking into `system.log` or `debug.log`
- Line breaks are stripped from logged queries to prevent log injection
