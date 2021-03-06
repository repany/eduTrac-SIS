<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

/**
 * eduTrac SIS Logging Functions
 *
 * @license GPLv3
 *         
 * @since 6.2.11
 * @package eduTrac SIS
 * @author Joshua Parker <joshmac3@icloud.com>
 */

/**
 * Default Error Handler
 * 
 * Sets the default error handler to handle
 * PHP errors and exceptions.
 *
 * @since 6.2.11
 */
function etsis_error_handler($type, $string, $file, $line)
{
    $logger = _etsis_logger();
    $logger->logError($type, $string, $file, $line);
}

/**
 * Set Error Log for Debugging.
 * 
 * @since 6.2.11
 * @param string|array $value The data to be catched.
 */
function etsis_error_log($value)
{
    if (is_array($value)) {
        error_log(var_export($value, true));
    } else {
        error_log($value);
    }
}

/**
 * Write Activity Logs to Database.
 *
 * @since 6.2.11
 */
function etsis_logger_activity_log_write($action, $process, $record, $uname)
{
    $logger = _etsis_logger();
    $logger->writeLog($action, $process, $record, $uname);
}

/**
 * Purges the error log of old records.
 *
 * @since 6.2.11
 */
function etsis_logger_error_log_purge()
{
    $logger = _etsis_logger();
    $logger->purgeErrorLog();
}

/**
 * Purges the activity log of old records.
 *
 * @since 6.2.11
 */
function etsis_logger_activity_log_purge()
{
    $logger = _etsis_logger();
    $logger->purgeActivityLog();
}

/**
 * Custom error log function for better PHP logging.
 * 
 * @since 6.2.11
 * @param string $name
 *            Log channel and log file prefix.
 * @param string $message
 *            Message printed to log.
 * @param string $level The logging level.
 */
function etsis_monolog($name, $message, $level = 'addInfo')
{
    $log = new \Monolog\Logger(_trim($name));
    $log->pushHandler(new \Monolog\Handler\StreamHandler(APP_PATH .'tmp'.DS.'logs'.DS._trim($name).'.'.date('m-d-Y').'.txt'));
    $log->$level($message);
}

/**
 * Set the system environment.
 * 
 * @since 6.2.11
 */
function etsis_set_environment()
{
    /**
     * Error log setting
     */
    if (APP_ENV == 'DEV') {
        /**
         * Print errors to the screen.
         */
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'On');
    } else {
        /**
         * Log errors to a file.
         */
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', BASE_PATH . 'app' . DS . 'tmp' . DS . 'logs' . DS . 'error.' . date('m-d-Y') . '.txt');
        set_error_handler('etsis_error_handler', E_ALL & ~E_NOTICE);
    }
}
