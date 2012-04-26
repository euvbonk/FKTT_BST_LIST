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

    public function lastAddonChange()
    {
        return '23. Mai 2010 10:56:00';
    }
    
    public final function templateFile()
    {
        return $this->addonTemplateBaseDir().'/module_list.php';
    }

    private function __construct(){}
    private function __clone(){}}
?>
