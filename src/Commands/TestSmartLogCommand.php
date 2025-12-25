<?php

declare(strict_types=1);

namespace Jonaaix\SmartLog\Commands;

use Illuminate\Console\Command;
use Jonaaix\SmartLog\SmartLog;

class TestSmartLogCommand extends Command
{
   protected $signature = 'smartlog:test';

   protected $description = 'Visual verification of SmartLog outputs';

   public function handle(): int
   {
      $this->newLine();
      $this->components->info('SmartLog Visual Verification');
      $this->newLine();

      $this->runTest('SmartLog::log() White text (Standard output)', function () {
         SmartLog::log('This is a standard line output');
      });

      $this->runTest('SmartLog::debug() Gray text (Debug/Ghost output)', function () {
         SmartLog::debug('This is a debug message [id: 123]');
      });

      $this->runTest('SmartLog::info() Cyan text (Info output)', function () {
         SmartLog::info('System is processing data...');
      });

      $this->runTest('SmartLog::success() Green text (Success alias)', function () {
         SmartLog::success('Operation completed successfully');
      });

      $this->runTest('SmartLog::warning() Yellow text (Warning output)', function () {
         SmartLog::warning('Disk space is running low');
      });

      $this->runTest('SmartLog::error() Red text (Error output)', function () {
         SmartLog::error('Connection failed');
      });

      $this->runTest('SmartLog::successBlock() Green Large Block (Success Block)', function () {
         SmartLog::successBlock('DEPLOYMENT FINISHED');
      });

      $this->runTest('SmartLog::errorBlock() Red Large Block (Error Block)', function () {
         SmartLog::errorBlock('CRITICAL FAILURE');
      });

      return self::SUCCESS;
   }

   private function runTest(string $expectation, \Closure $callback): void
   {
      $this->output->writeln("<comment>Expecting: $expectation</comment>");
      $this->output->write("Output:    ");
      $callback();
      $this->newLine();
   }
}
