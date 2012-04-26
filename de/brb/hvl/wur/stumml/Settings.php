<?php

class Settings
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
        return '21. Mai 2010 22:52:45';
    }

    public final function uploadBaseDir()
    {
        global $dataDir;
        return $dataDir.'/data/_uploaded/file/fktt';
    }
    
    public final function addonBaseDir()
    {
        global $dataDir;
        return $dataDir.'/data/_addoncode/FKTT_BST_LIST';
    }
    
    public final function addonTemplateBaseDir()
    {
        return $this->addonBaseDir().'/templates';
    }

    private function __construct(){}
    private function __clone(){}
}
?>
