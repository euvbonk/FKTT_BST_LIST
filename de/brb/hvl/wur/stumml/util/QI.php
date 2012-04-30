<?php

/**
 * Class holds all function as interface for gpEasy
 * whenever a function of gpeasy framework is used, this QI interface has to be
 * called for delegating
 */
final class QI
{
    // private access for outside, all methods are static 
    protected function __construct() {}

    public static function isGpeasyDebugEnabled()
    {
        return (defined('gpdebug') ? constant('gpdebug') : false);
    }

    public static function getPageName()
    {
        return common::WhichPage();
    }
    
    public static function getDataDir()
    {
        global $dataDir;
        return $dataDir.'/data/_uploaded/file/fktt';
    }

    public static function getAddonPathCode()
    {
        global $addonPathCode;
        return $addonPathCode;
    }
    
    public static function getRootDir()
    {
        global $rootDir;
        return $rootDir;
    }

    public static function buildAbsoluteLink($path, $label)
    {
        return common::AbsoluteLink($path, $label);
    }
    
    public static function getPageUri()
    {
        return common::AbsoluteUrl(common::WhichPage());
    }

    public static function getCommand()
    {
        return common::GetCommand();
    }

    public static function getUriFrom($path)
    {
        return common::AbsoluteUrl($path);
    }
}
?>
