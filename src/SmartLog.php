<?php

declare(strict_types=1);

namespace Aaix\SmartLog;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class SmartLog
{
   private static ?ConsoleOutput $output = null;

   public static function log(mixed ...$data): void
   {
      self::handle('debug', null, ...$data);
   }

   public static function debug(mixed ...$data): void
   {
      self::handle('debug', 'fg=gray', ...$data);
   }

   public static function info(mixed ...$data): void
   {
      self::handle('info', 'fg=cyan', ...$data);
   }

   public static function success(mixed ...$data): void
   {
      self::handle('info', 'info', ...$data);
   }

   public static function warning(mixed ...$data): void
   {
      self::handle('warning', 'comment', ...$data);
   }

   public static function error(mixed ...$data): void
   {
      self::handle('error', 'error', ...$data);
   }

   public static function successBlock(mixed ...$data): void
   {
      [$message, $context] = self::resolveMessageAndContext($data);

      self::persist('info', $message, $context);

      if (App::runningInConsole()) {
         self::createStyle()->success($message);
      }
   }

   public static function errorBlock(mixed ...$data): void
   {
      [$message, $context] = self::resolveMessageAndContext($data);

      self::persist('error', $message, $context);

      if (App::runningInConsole()) {
         self::createStyle()->error($message);
      }
   }

   private static function handle(string $level, ?string $cliTag, mixed ...$data): void
   {
      [$message, $context] = self::resolveMessageAndContext($data);

      self::persist($level, $message, $context);

      if (App::runningInConsole()) {
         self::printToConsole($cliTag, ...$data);
      }
   }

   private static function resolveMessageAndContext(array $data): array
   {
      if (empty($data)) {
         return ['Empty Log Entry', []];
      }

      $first = $data[0];

      if (is_string($first) || is_numeric($first)) {
         return [(string) $first, array_slice($data, 1)];
      }

      return ['Unnamed log', $data];
   }

   private static function persist(string $level, string $message, array $context): void
   {
      if (in_array($level, config('smart-log.persist_levels', []))) {
         $laravelMethod = $level === 'console' ? 'debug' : $level;

         if (!method_exists(Log::class, $laravelMethod)) {
            $laravelMethod = 'debug';
         }

         Log::$laravelMethod($message, $context);
      }
   }

   private static function createStyle(): SymfonyStyle
   {
      if (!self::$output) {
         self::$output = new ConsoleOutput();
      }

      return new SymfonyStyle(new StringInput(''), self::$output);
   }

   private static function printToConsole(?string $tag, mixed ...$data): void
   {
      if (!self::$output) {
         self::$output = new ConsoleOutput();
      }

      $messages = array_map(function ($item) {
         return is_scalar($item)
            ? (string)$item
            : json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      }, $data);

      $text = implode(' ', $messages);

      if ($tag) {
         self::$output->writeln("<$tag>$text</>");
      } else {
         self::$output->writeln($text);
      }
   }
}
