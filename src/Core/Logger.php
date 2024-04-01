<?php

namespace Core;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    public function emergency($message, array $context = []): void
    {
//        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
//        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
//        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
//        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
//        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
//        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        $context = implode("\n", $context);
        $message = sprintf('[%s] %s: %s%s', date('Y-m-d H:i:s'), $level, $message.$context."\n", PHP_EOL);

        if ($level === LogLevel::ERROR){
            file_put_contents(dirname(__DIR__) . '/Storage/Logs/errors.txt', $message, FILE_APPEND);
        } elseif ($level === LogLevel::INFO){
            file_put_contents(dirname(__DIR__) . '/Storage/Logs/info.txt', $message, FILE_APPEND);
        }

    }
}