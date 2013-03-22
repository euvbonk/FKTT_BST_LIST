<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_cmd_CheckJNLPVersionCmd');
import('de_brb_hvl_wur_stumml_io_File');

class StationDatasheetSettings extends Settings
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
        return '08. M&auml;rz 2013 18:00:00';
    }
    
    /**
     * @return File
     */
    public final function getTemplateFile()
    {
        return new File($this->addonTemplateBaseDir().'/datasheets_list.php');
    }
    
    private function __construct(){}
    private function __clone(){}
}