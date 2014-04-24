<?php

class Mod_Test extends TF_Model
{
    public function getVipDatas()
    {
        $this->db->query("SELECT * FROM `vip_base_info`");
        return $this->db->fetch_rows();
    }
}