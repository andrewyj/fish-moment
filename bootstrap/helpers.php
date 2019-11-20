<?php

/**
 * 日志分开存储
 * @param $message
 * @param $content
 * @param $path
 * @param string $level
 * @return bool
 */
function logPlus($message, $content, $path, $level = 'debug') {
    $levels = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug'
    ];
    if (!in_array($level, $levels)) {
        return false;
    }
    if (!is_array($content)) {
        return false;
    }
    $path = config("log.$path");
    echo $path;die;
    $stream_handler = new \Monolog\Handler\RotatingFileHandler($path);
    
    $stream_handler->setFormatter(new \Monolog\Formatter\JsonFormatter());
    (new \Monolog\Logger($path))
        ->pushHandler($stream_handler)
        ->$level($message, $content);
}