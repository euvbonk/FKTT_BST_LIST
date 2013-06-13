<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_io_File');

import('de_brb_hvl_wur_stumml_util_logging_StdoutLogger');

class Develop extends Frame
{
    private static $log;

    public function __construct()
    {
        parent::__construct("develop");
        self::$log = new StdoutLogger(get_class($this));
        return $this;
    }

    public function content()
    {
        ob_start();
        self::$log->debug("No Testing!");
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    protected function getCallableMethods()
    {
        return array('content');
    }
}
