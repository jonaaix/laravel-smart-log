<?php

return [

   /*
   |--------------------------------------------------------------------------
   | Persistence Filter (Log File / Sentry / Slack)
   |--------------------------------------------------------------------------
   |
   | SmartLog ALWAYS outputs messages to the console (CLI) for visual feedback.
   |
   | This configuration controls which levels are ADDITIONALLY written to your
   | default log channels (storage/logs/laravel.log, Sentry, etc.).
   |
   | - Listed here:     Visible in Terminal AND saved to Logfile.
   | - Commented out:   Visible in Terminal ONLY.
   |
   */

   'persist_levels' => [

      // --- Errors & Warnings (Recommended: ENABLED) ---
      // Keep these enabled to ensure failures and issues are tracked in your
      // monitoring tools (e.g., Sentry) or log files.
      'error',
      'warning',

      // --- Operational Info (Recommended: DISABLED) ---
      // Enable these only if you need a full audit trail of every step in your logs.
      // Keeping them disabled prevents "log noise" and saves disk space,
      // while still showing you the progress in your terminal.
      // 'success',
      // 'info',
      // 'debug',
      // 'log',
   ],

];
