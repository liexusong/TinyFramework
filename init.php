<?php

# TinyFramework Copyright(c) Liexusong@qq.com


define('TF_BASE_PATH', dirname(__FILE__));
define('TF_CORE_PATH', TF_BASE_PATH.'/core');
define('TF_CTLS_PATH', TF_BASE_PATH.'/ctls');
define('TF_MODS_PATH', TF_BASE_PATH.'/mods');
define('TF_CONF_PATH', TF_BASE_PATH.'/conf');
define('TF_TPLS_PATH', TF_BASE_PATH.'/tpls');
define('TF_LOGS_PATH', TF_BASE_PATH.'/logs');


$__core_files_list = array(
    TF_CORE_PATH.'/tf_router.php',
    TF_CORE_PATH.'/tf_database.php',
    TF_CORE_PATH.'/tf_template.php',
    TF_CORE_PATH.'/tf_log.php',
    TF_CORE_PATH.'/tf_controller.php',
    TF_CORE_PATH.'/tf_model.php',
);

// 载入所以必须的内核文件
foreach ($__core_files_list as $corefile) {
    require_once ($corefile);
}


function load_conf($conf)
{
    $conf_file = TF_CONF_PATH.'/'.$conf.'.php';
    if (file_exists($conf_file)) {
        return include($conf_file);
    }
    return false;
}


function model($model)
{
    $mod_file = TF_MODS_PATH.'/'.$model.'.php';

    if (file_exists($mod_file)) {
        require_once ($mod_file);

        $model = 'Mod_'.ucfirst($model);
        if (!class_exists($model)) {
            throw new Exception("Not found model class `{$model}'");
        }

        $object = new $model();
        if (!is_subclass_of($object, "TF_Model")) {
            unset($object);
            throw new Exception("Model `{$model}' must extends TF_Model class");
        }
        return $object;
    }

    throw new Exception("Not found model file `{$mod_file}'");
}
