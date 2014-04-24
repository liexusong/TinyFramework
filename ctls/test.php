<?php

class Ctl_Test extends TF_Controller
{
    public function index($msg1, $msg2)
    {
        $model = model('test');
        var_dump($model->getVipDatas());
    }
}
