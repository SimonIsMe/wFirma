<?php
require_once 'config.php';
error_reporting(Config::ERROR_REPORTING_LEVEL);
ini_set('display_errors', Config::DISPLAY_ERRORS);

\SS\Log::setFilePath(__DIR__ . '/' . Config::LOG_PATH_FILE);

function __autoload($classname) 
{
    $parts = explode('\\', $classname);
    require_once __DIR__ . '/library/' . implode('/', $parts) . '.php';
}