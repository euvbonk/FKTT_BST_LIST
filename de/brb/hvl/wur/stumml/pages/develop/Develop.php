<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');

import('de_brb_hvl_wur_stumml_util_logging_StdoutLogger');

interface DevelopPageContent
{
    public function content();
}

class Develop extends Frame implements DevelopPageContent
{
    private static $log;

    public function __construct()
    {
        parent::__construct(new File(Settings::addonTemplateBaseDir()."/develop.php"));
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

    public function getLastChangeTimestamp()
    {
    }
}
