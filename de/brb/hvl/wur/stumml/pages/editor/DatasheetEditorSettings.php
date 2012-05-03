<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_QI');

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
        return '03. Mai 2012 21:00:00';
    }
    
    public final function getTemplateFile()
    {
        return $this->addonTemplateBaseDir().'/datasheet_editor.php';
    }

    public final function getUrl()
    {
        return self::getUrlForFile("rgzm.jnlp");
    }

    public final function getCertificateUrl()
    {
        return parent::uploadBaseDir().DIRECTORY_SEPARATOR."rgzm".DIRECTORY_SEPARATOR."rgzm.cert";
    }

    protected static function getUrlForFile($fileName)
    {
        $filepath = substr(parent::uploadBaseDir().DIRECTORY_SEPARATOR."rgzm".DIRECTORY_SEPARATOR.$fileName, strlen(QI::getRootDir())+1);
        return str_replace('index.php/', '', QI::getUriFrom($filepath));
    }

    private function __construct(){}
    private function __clone(){}}
?>
