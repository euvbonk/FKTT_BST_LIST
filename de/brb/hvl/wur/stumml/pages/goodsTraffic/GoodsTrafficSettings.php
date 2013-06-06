<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');

class GoodsTrafficSettings extends Settings
{
    /**
     * @static instance
     * @return Settings|GoodsTrafficSettings
     */
    public static function getInstance()
    {
        if (null === parent::$INSTANCE)
        {
            parent::$INSTANCE = new self;
        }
        return parent::getInstance();
    }

    /**
     * @return String
     */
    //@Override
    protected function getTemplateFileName()
    {
        return 'goods_traffic_basics.php';
    }

    private function __construct(){}
    private function __clone(){}
}