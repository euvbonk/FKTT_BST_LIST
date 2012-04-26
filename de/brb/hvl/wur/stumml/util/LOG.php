<?php

final class LOG
{
    protected function __construct(){}

    public static function debug($message)
    {
        if (defined('gpdebug'))
        {
            message($message);
        }
    }
}
?>
