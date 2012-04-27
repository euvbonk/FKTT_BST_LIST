<?php

import('de_brb_hvl_wur_stumml_Settings');

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
        return '27. April 2012 12:00:00';
    }
    
    public final function getTemplateFile()
    {
        return $this->addonTemplateBaseDir().'/datasheet_editor.php';
    }

    public final function getUrl()
    {
        global $rootDir;
        $filepath = substr(parent::uploadBaseDir().DIRECTORY_SEPARATOR."rgzm".DIRECTORY_SEPARATOR."rgzm.jnlp", strlen($rootDir)+1);
        return str_replace('index.php/', '', common::AbsoluteUrl($filepath));
    }

    private function __construct(){}
    private function __clone(){}}
?>
