<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_editor_DatasheetEditorSettings');

class DatasheetEditor extends Frame
{
    public function __construct()
    {
        parent::__construct(DatasheetEditorSettings::getInstance()->getTemplateFile());
        $this->checkOnNewVersion();
    }

    public function getLastChangeTimestamp()
    {
        return DatasheetEditorSettings::getInstance()->lastAddonChange();
    }

    public function url()
    {
        return DatasheetEditorSettings::getInstance()->getUrl();
    }

    public function content()
    {
        /* Not used regularly */
        return "";
    }
    
    private function checkOnNewVersion()
    {
    }
}
?>
