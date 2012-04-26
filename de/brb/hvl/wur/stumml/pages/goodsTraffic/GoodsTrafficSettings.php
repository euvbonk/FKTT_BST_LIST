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
        return '14. Oktober 2011 22:30:00';
    }
    
    public final function getTemplateFile()
    {
        return $this->addonTemplateBaseDir().'/goods_traffic_basics.php';
    }

    private function __construct(){}
    private function __clone(){}}
?>
