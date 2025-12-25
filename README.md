<p align="center">
  <a href="https://github.com/jonaaix/laravel-smart-log">
    <img src="https://raw.githubusercontent.com/jonaaix/laravel-smart-log/main/docs/logo.webp" alt="Laravel SmartLog Logo" width="200">
  </a>
</p>

<h1 align="center">Laravel SmartLog</h1>

<p align="center">
Context-aware logging wrapper for Laravel applications.
</p>

<p align="center">
  <a href="https://packagist.org/packages/aaix/laravel-smart-log">
    <img src="https://img.shields.io/packagist/v/aaix/laravel-smart-log.svg?style=flat-square" alt="Latest Version on Packagist">
  </a>
  <a href="https://packagist.org/packages/aaix/laravel-smart-log">
    <img src="https://img.shields.io/packagist/dt/aaix/laravel-smart-log.svg?style=flat-square" alt="Total Downloads">
  </a>
  <a href="https://github.com/jonaaix/laravel-smart-log/blob/main/LICENSE.md">
    <img src="https://img.shields.io/packagist/l/aaix/laravel-smart-log.svg?style=flat-square" alt="License">
  </a>
</p>

SmartLog solves a common problem in Laravel CLI development: You want visual feedback in your terminal (colors, progress), but you
don't want to clutter your production log files (`laravel.log`, Sentry, Slack) with developer noise.

SmartLog intelligently routes your messages based on context (CLI vs. Web) and configuration.

---


## Installation

```bash
composer require jonaaix/laravel-smart-log
```

## Usage

Use `SmartLog` anywhere in your code. It automatically detects if it's running inside an Artisan command or a web request.

```php
use Jonaaix\SmartLog\SmartLog;

// 1. Standard Output (CLI: White | File: Ignored)
// Perfect for progress bars, raw data dumps, or steps.
SmartLog::log('Importing users step 1 of 5...');

// 2. Developer Output (CLI: Gray | File: Ignored)
// Subtle output for debugging.
SmartLog::debug('Memory usage: 12MB');

// 3. Operational Info (CLI: Cyan | File: Ignored by default)
// Good for status updates that don't need permanent storage.
SmartLog::info('User 123 imported successfully.');
SmartLog::success('All operations finished.'); // Alias for info with green color

// 4. Issues (CLI: Yellow/Red | File: LOGGED)
// These are automatically sent to your default log channel (File, Sentry, etc.)
SmartLog::warning('API rate limit approaching.');
SmartLog::error('Connection failed, retrying...');

// 5. Block Styles (CLI: Large Blocks | File: LOGGED)
// Visually dominant blocks for critical start/stop events.
SmartLog::successBlock('DEPLOYMENT COMPLETE');
SmartLog::errorBlock('CRITICAL SYSTEM FAILURE');
```

## How it works

| Method      | CLI Output (Visual) | Log File (Persistence) |
|-------------|---------------------|------------------------|
| `log()`     | White text          | ❌ (Ignored)            |
| `debug()`   | Gray text (subtle)  | ❌ (Ignored)            |
| `info()`    | Cyan text           | ❌ (Ignored*)           |
| `success()` | Green text          | ❌ (Ignored*)           |
| `warning()` | Yellow text         | ✅ **Logged** (warning) |
| `error()`   | Red text            | ✅ **Logged** (error)   |

**Default configuration. You can enable persistence for info/debug levels in the config file.*

## Configuration

By default, only `warning` and `error` levels are written to the log file. Everything else remains visual-only in the console.

To change this, publish the configuration file:

```bash
php artisan vendor:publish --tag=smart-log-config
```

**config/smart-log.php**

```php
return [
    'persist_levels' => [
        'error',
        'warning', 
        // Add 'info' here to save info() and success() calls to the log file
    ],
];
```

## Web Context Behavior

If you use `SmartLog` within a Controller or Job (non-CLI):

* **Visual Output:** Skipped (no `echo` or `print`).
* **Persistence:** Respects the config. `SmartLog::error()` will log to the file. `SmartLog::log()` will do nothing.

## Testing
Run the test command:
```bash
./vendor/bin/testbench smartlog:test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
