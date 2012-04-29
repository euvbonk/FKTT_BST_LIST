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
        return (defined('gpdebug') ? true : false);
    }
}
?>
