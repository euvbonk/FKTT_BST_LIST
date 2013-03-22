<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_QI');
import('de_brb_hvl_wur_stumml_io_File');

class DatasheetEditorSettings extends Settings
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
        return '11. M&auml;rz 2013 10:00:00';
    }

    /**
     * @return File
     */
    public final function getTemplateFile()
    {
        return new File($this->addonTemplateBaseDir().'/datasheet_editor.php');
    }

    public final function getCertificateUrl()
    {
        return QI::getDataDir()."/rgzm/rgzm.cert";
    }

    private function __construct(){}
    private function __clone(){}
}