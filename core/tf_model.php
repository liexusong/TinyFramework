<?php

# TinyFramework Copyright(c) Liexusong@qq.com


class TF_Model
{
    protected $db = NULL;

    public function __construct()
    {
        $conf = load_conf('database');

        if (!empty($conf)) {
            $this->db = new TF_Database($conf['host'], $conf['username'],
                                    $conf['password'], $conf['database']);
        } else {
            $this->db = new TF_Database();
        }
    }
}
