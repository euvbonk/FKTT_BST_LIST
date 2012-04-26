<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_Settings');

import('de_brb_hvl_wur_stumml_util_LOG');

interface DevelopPageContent
{
    public function content();
}

class Develop extends Frame implements DevelopPageContent
{
    public function __construct()
    {
        parent::__construct(Settings::addonTemplateBaseDir()."/develop.php");
    }

    public function content()
    {
        ob_start();
        LOG::debug("No Testing!");
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    public function getLastChangeTimestamp()
    {
    }
}
?>
