<?php

import('de_brb_hvl_wur_stumml_datasheet_StationDatasheetList');
import('de_brb_hvl_wur_stumml_moduleList_ModuleList');

class Main
{
    public function __construct()
    {
        switch (strtolower(common::WhichPage()))
        {
            case "special_datasheets_list" :
                $sheet = new StationDatasheetList();
                $sheet->showContent();
                break;
            case "special_create_module_list" :
                $sheet = new ModuleList();
                $sheet->showContent();
                break;
            default :
                echo "<p>No Module specified!</p>";
                break;
        }
    }
}
?>
