<?php

# TinyFramework Copyright(c) Liexusong@qq.com


define('LOG_LEVEL_DEBUG',  1);
define('LOG_LEVEL_ALERT',  2);
define('LOG_LEVEL_NOTICE', 3);
define('LOG_LEVEL_ERROR',  4);


class TF_Log
{
    private static $_path = TF_LOGS_PATH;

    public static function log($message, $level = LOG_LEVEL_DEBUG)
    {
        $logfile = self::$_path.'/'.date('Y-m-d').'.log';

        switch ($level) {
        case LOG_LEVEL_DEBUG:
            $prefix = 'DEBUG';
            break;
        case LOG_LEVEL_ALERT:
            $prefix = 'ALERT';
            break;
        case LOG_LEVEL_NOTICE:
            $prefix= 'NOTICE';
            break;
        default:
            $prefix= 'ERROR';
            break;
        }

        $date = date('Y-m-d H:i:s');

        return file_put_contents($logfile, "[$date] {$prefix}: $message\n");
    }
}

