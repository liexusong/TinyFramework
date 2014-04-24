<?php

# TinyFramework Copyright(c) Liexusong@qq.com


class TF_Template
{
    private $_tpl_path = TF_TPLS_PATH;
    private $_vars = array();

    public function set_tpl_path($path)
    {
        if (is_dir($path)) {
            $this->_tpl_path = rtrim(str_replace('\\', '/', $path), '/');
            return true;
        }
        return false;
    }

    public function assign($var, $value, $replace = true)
    {
        if (!preg_match('/^[a-zA-Z_]+[a-zA-Z0-9_]*$/', $var)) {
            return false;
        }

        if (!$replace && isset($this->_vars[$var])) {
            return false;
        }

        $this->_vars[$var] = $value;

        return true;
    }

    public function display($tpl_file, $data = array())
    {
        if (!empty($data)) {
            array_merge($this->_vars, $data);
        }

        if (!empty($this->_vars)) {
            extract($this->_vars);
        }

        $tpl_file = $this->_tpl_path.'/'.$tpl_file;

        if (file_exists($tpl_file)) {
            include($tpl_file);
            return true;
        }
        return false;
    }
}

