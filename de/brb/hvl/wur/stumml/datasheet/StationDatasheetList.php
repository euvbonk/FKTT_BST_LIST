<?php

import('de_brb_hvl_wur_stumml_Frame');
import('de_brb_hvl_wur_stumml_datasheet_StationDatasheetSettings');
import('de_brb_hvl_wur_stumml_datasheet_TableContentEntries');

class StationDatasheetList extends Frame
{
    private $entries;

    public function __construct()
    {
        $this->setTemplateFile(StationDatasheetSettings::getInstance()->templateFile());
        $this->entries = new TableContentEntries();
        $this->entries->buildTableEntries();
    }
    
    public function getTableEntries()
    {
        $this->entries->echoEntries();
    }
}
?>
