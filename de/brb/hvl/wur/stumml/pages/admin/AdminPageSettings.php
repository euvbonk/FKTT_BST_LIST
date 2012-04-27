<?php

import('de_brb_hvl_wur_stumml_Settings');

class AdminPageSettings extends Settings
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
        return '20. April 2012 14:00:00';
    }
    
    public final function getTemplateFile()
    {
        return $this->addonTemplateBaseDir().'/admin.php';
    }

    private function __construct(){}
    private function __clone(){}}
?>