<?php

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
        return '23. Mai 2010 21:32:45';
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
    
    public final function addonTemplateBaseDir()
    {
        return $this->addonBaseDir().'/templates';
    }

    public final static function uploadDir()
    {
        return self::uploadBaseDir().'/db';
    }
    
    public abstract function getTemplateFile();

    private function __construct(){}
    private function __clone(){}
}
?>
