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

    public function lastAddonChange()
    {
        return '23. Mai 2010 09:56:00';
    }
    
    public final function newSheet()
    {
        return array('link' => 'Special_Add_Sheet', 'label' => 'Neue Betriebsstellendaten hinzufügen');
    }
    
    public final function templateFile()
    {
        return $this->addonTemplateBaseDir().'/datasheets_list.php';
    }
    
    public final function uploadDir()
    {
        return $this->uploadBaseDir().'/db';
    }
    
    private function __construct(){}
    private function __clone(){}
}
?>
