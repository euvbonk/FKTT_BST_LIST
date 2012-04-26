<?php

import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');

abstract class Settings
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
        return '19. Okotber 2011 09:30:00';
    }

    public final static function uploadBaseDir()
    {
        global $dataDir;
        return $dataDir.'/data/_uploaded/file/fktt';
    }
    
    public final function addonBaseDir()
    {
        global $addonPathCode;
        return $addonPathCode;
    }
    
    public final static function addonTemplateBaseDir()
    {
        return self::addonBaseDir().'/templates';
    }

    public final static function uploadDir()
    {
        return self::uploadBaseDir().'/db';
    }

    public final static function buildDownloadPath($filePath, $label)
    {
        global $rootDir;
        $filePath = substr($filePath, strlen($rootDir)+1);
        return str_replace('index.php/', '', HtmlUtil::toUtf8(common::AbsoluteLink($filePath, $label)));
    }    

    public abstract function getTemplateFile();

    private function __construct(){}
    private function __clone(){}
}
?>
