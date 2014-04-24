<?php

# TinyFramework Copyright(c) Liexusong@qq.com


define('ROUTER_USER_QUERYSTRING', 1);
define('ROUTER_USE_PATHINFO',     2);
define('ROUTER_USE_URI',          3);


class TF_Router
{
    private $_ctl = null;
    private $_act = null;
    private $_args = array();

    public function __construct($use = ROUTER_USE_PATHINFO)
    {
        $conf = load_conf('router');
        if (empty($conf)) {
            throw new Exception('Not found router configure file');
        }

        switch ($use) {
        case ROUTER_USER_QUERYSTRING:
            $this->_ctl = isset($_GET['ctl']) ? $_GET['ctl'] :
                                                $conf['default_ctl'];
            $this->_act = isset($_GET['act']) ? $_GET['act'] : 
                                                $conf['default_act'];

            foreach ($_GET as $key => $val) {
                if (!in_array($key, array('ctl', 'act'))) {
                    $this->_args[] = $val;
                }
            }
            break;

        case ROUTER_USE_PATHINFO:
            if (!isset($_SERVER['PATH_INFO'])
                || empty($_SERVER['PATH_INFO']))
            {
                $this->_ctl = $conf['default_ctl'];
                $this->_act = $conf['default_act'];
                break;
            }

            $path_info = explode('/', trim($_SERVER['PATH_INFO'], '/'));
            $this->_ctl = isset($path_info[0]) ? $path_info[0] : 
                                                 $conf['default_ctl'];
            $this->_act = isset($path_info[1]) ? $path_info[1] :
                                                 $conf['default_act'];

            for ($i = 2; $i < count($path_info); $i++) {
                $this->_args[] = $path_info[$i];
            }
            break;

        case ROUTER_USE_URI:
            if (!isset($_SERVER['REQUEST_URI'])
                || empty($_SERVER['REQUEST_URI']))
            {
                $this->_ctl = $conf['default_ctl'];
                $this->_act = $conf['default_act'];
                break;
            }

            $path_info = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $this->_ctl = isset($path_info[0]) ? $path_info[0] : 
                                                 $conf['default_ctl'];
            $this->_act = isset($path_info[1]) ? $path_info[1] :
                                                 $conf['default_act'];

            for ($i = 2; $i < count($path_info); $i++) {
                $this->_args[] = $path_info[$i];
            }
            break;
        }
    }

    public function run()
    {
        $ctl_file = TF_CTLS_PATH.'/'.$this->_ctl.'.php';
        if (!file_exists($ctl_file)) {
            throw new Exception("Not found controller file `{$ctl_file}'");
        }

        include_once($ctl_file); // include controller file

        $cls_name = 'Ctl_'.ucfirst($this->_ctl);
        if (!class_exists($cls_name)) {
            throw new Exception("Not found controller class `{$cls_name}'");
        }

        $object = new $cls_name();
        if (!is_subclass_of($object, 'TF_Controller')) {
            throw new Exception("Controller `{$cls_name}' must extends TF_Controller class");
        }

        if (!method_exists($object, $this->_act)) {
            throw new Exception("Not found method `{$cls_name}::{$this->_act}'");
        }

        call_user_func_array(array($object, $this->_act), $this->_args);
    }
}
