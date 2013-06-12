<?php

/**
 * Class holds all functions as interface for gpEasy.
 * Whenever a function of the gpeasy framework should be used,
 * this QI interface should handle this call for delegating
 * because it is easier to change framework function at one point
 */
final class QI
{
    // private access from outside, all methods are static
    protected function __construct() {}

    public static function isGpeasyDebugEnabled()
    {
        return (defined('gpdebug') ? constant('gpdebug') : false);
    }

    public static function getPageName()
    {
        return common::WhichPage();
    }

    public static function buildAbsoluteLink($path, $label, $query = '')
    {
        return common::AbsoluteLink($path, $label, $query);
    }
    
    public static function getPageUri()
    {
        return common::AbsoluteUrl(common::WhichPage());
    }

    public static function getCommand($type='cmd')
    {
        return common::GetCommand($type);
    }

    public static function getUriFrom($path)
    {
        return common::AbsoluteUrl($path);
    }
}
