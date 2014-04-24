<?php

# TinyFramework Copyright(c) Liexusong@qq.com


define('DB_FETCH_ARRAY',  1);
define('DB_FETCH_ROW',    2);
define('DB_FETCH_OBJECT', 3);


class TF_Database
{
    private $_link = NULL;
    private $_result = NULL;
    private $_slowlog = true;

    public function __construct($host = NULL, $user = NULL, $pass = NULL,
                                $database = NULL)
    {
        if (!empty($host)) {
            $this->reconnect($host, $user, $pass, $database);
        }
    }

    public function reconnect($host, $user, $pass, $database = NULL)
    {
        if ($this->_link) {
            mysql_close($this->_link);
        }

        $this->_link = mysql_connect($host, $user, $pass);
        if ($this->_link) {
            if ($database) {
                mysql_select_db($database, $this->_link);
            }
            return true;
        }
        return false;
    }

    public function execute($sql)
    {
        static $allows = array('SELECT', 'UPDATE', 'INSERT', 'DELETE');

        $sql = trim($sql);

        if (empty($sql) ||
            !in_array(strtoupper(substr($sql, 0, 6)), $allows))
        {
            return false;
        }

        if ($this->_result) {
            mysql_free_result($this->_result);
        }

        if ($this->_slowlog) {
            $execute_time = microtime(true);
        }

        $this->_result = mysql_query($sql, $this->_link);

        if ($this->_slowlog) {
            $times = microtime(true) - $execute_time;
            if ($times >= 1.0) { // 1 second was slow query
                TF_Log::log("Slow SQL query `$sql'", LOG_LEVEL_NOTICE);
            }
        }
        
        if ($this->_result) {
            return true;
        }

        return false;
    }

    public function query($sql)
    {
        return $this->execute($sql);
    }

    public function fetch_row($type = DB_FETCH_ARRAY)
    {
        switch ($type) {
        case DB_FETCH_ARRAY:
            $fn = 'mysql_fetch_assoc';
            break;
        case DB_FETCH_ROW:
            $fn = 'mysql_fetch_row';
            break;
        case DB_FETCH_OBJECT:
            $fn = 'mysql_fetch_object';
            break;
        }

        return $fn($this->_result);
    }

    public function fetch_rows($type = DB_FETCH_ARRAY)
    {
        $retval = array();
        while ($row = $this->fetch_row($type)) {
            $retval[] = $row;
        }
        return $retval;
    }
    
    public function insert_id()
    {
        return mysql_insert_id($this->_link);
    }

    public function affected_rows()
    {
        return mysql_affected_rows($this->_link);
    }

    public function error()
    {
        return mysql_error($this->_link);
    }
}
