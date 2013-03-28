<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');

class ModuleListSettings extends Settings
{
    private static $INSTANCE = null;

    /**
     * @static instance
     * @return Settings
     */
    public static function getInstance()
    {
        if (null === self::$INSTANCE)
        {
            self::$INSTANCE = new self;
        }
        return self::$INSTANCE;
    }

    /**
     * @return String
     */
    /*@Override*/
    public function lastAddonChange()
    {
        return '22. M&auml;rz 2013 19:00:00';
    }

    /**
     * @return File
     */
    public final function getTemplateFile()
    {
        return new File($this->addonTemplateBaseDir().'/module_list.php');
    }

    private function __construct(){}
    private function __clone(){}
}