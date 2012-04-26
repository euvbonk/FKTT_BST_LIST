<?php

import('de_brb_hvl_wur_stumml_datasheet_AbstractStationDatasheetList');
import('de_brb_hvl_wur_stumml_datasheet_StationDatasheetListOrderShort');
import('de_brb_hvl_wur_stumml_datasheet_StationDatasheetListOrderLast');
import('de_brb_hvl_wur_stumml_moduleList_ModuleList');

class Main
{
    public function __construct()
    {
        switch (strtolower(common::WhichPage()))
        {
            case "special_datasheets_list" :
                $cmd = common::GetCommand();
                if ($cmd === false || AbstractStationDatasheetList::$commands['ORDER_SHORT'] == $cmd)
                {
                    $sheet = new StationDatasheetListOrderShort();
                }
                else if (AbstractStationDatasheetList::$commands['ORDER_LAST'] == $cmd)
                {
                    $sheet = new StationDatasheetListOrderLast();
                }
                else
                {
                    throw new Exception("No such command -".$cmd."- for this module");
                }
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
