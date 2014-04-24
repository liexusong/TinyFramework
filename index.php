<?php

# TinyFramework Copyright(c) Liexusong@qq.com

require_once ('./init.php');


try {

    $router = new TF_Router(ROUTER_USE_PATHINFO);
    $router->run();

} catch (Exception $e) {
    echo 'Exception: '.$e->getMessage();
}

