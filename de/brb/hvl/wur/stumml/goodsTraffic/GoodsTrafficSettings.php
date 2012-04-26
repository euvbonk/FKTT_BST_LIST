<?php

import('de_brb_hvl_wur_stumml_Settings');

class GoodsTrafficSettings extends Settings
{
    private static $INSTANCE = null;
    
    public static function getInstance()
    {
        if (null === self::$INSTANCE)
        {
            self::$INSTANCE = new self;
        }
        return self::$INSTANCE;
    }

    /*@Override*/
    public function lastAddonChange()
    {
        return '23. September 2011 12:45:00';
    }
    
    public final function getTemplateFile()
    {
        return $this->addonTemplateBaseDir().'/goods_traffic_basics.php';
    }

    private function __construct(){}
    private function __clone(){}}
?>
