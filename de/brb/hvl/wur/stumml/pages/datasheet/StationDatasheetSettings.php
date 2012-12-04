<?php

import('de_brb_hvl_wur_stumml_Settings');

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
        return '04. Dezember 2012 14:00:00';
    }
    
    public final function newSheet()
    {
        return array('link' => 'Special_Add_Sheet', 'label' => 'Neue Betriebsstellendaten hinzufügen');
    }
    
    public final function getTemplateFile()
    {
        return $this->addonTemplateBaseDir().'/datasheets_list.php';
    }
    
    private function __construct(){}
    private function __clone(){}
}
?>
