<?php

declare(strict_types=1);

namespace Jonaaix\SmartLog;

use Illuminate\Support\ServiceProvider;
use Jonaaix\SmartLog\Commands\TestSmartLogCommand;

class SmartLogServiceProvider extends ServiceProvider
{
   public function register(): void
   {
      $this->mergeConfigFrom(__DIR__ . '/../config/smart-log.php', 'smart-log');
   }

   public function boot(): void
   {
      if ($this->app->runningInConsole()) {
         $this->publishes([
            __DIR__ . '/../config/smart-log.php' => config_path('smart-log.php'),
         ], 'smart-log-config');

         $this->commands([
            TestSmartLogCommand::class,
         ]);
      }
   }
}
