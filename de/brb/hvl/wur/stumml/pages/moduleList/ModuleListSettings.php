<?php

import('de_brb_hvl_wur_stumml_Settings');

class ModuleListSettings extends Settings
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
        return '22. September 2011 07:30:00';
    }
    
    public final function getTemplateFile()
    {
        return $this->addonTemplateBaseDir().'/module_list.php';
    }

    private function __construct(){}
    private function __clone(){}}
?>
