<?php

import('de_brb_hvl_wur_stumml_pages_AddonErrorPage');
import('de_brb_hvl_wur_stumml_util_QI');
import('de_brb_hvl_wur_stumml_pages_datasheet_DatasheetsList');
import('de_brb_hvl_wur_stumml_pages_develop_Develop');
import('de_brb_hvl_wur_stumml_pages_goodsTraffic_GoodsTrafficBasics');
import('de_brb_hvl_wur_stumml_pages_moduleList_ModuleList');
import('de_brb_hvl_wur_stumml_pages_editor_DatasheetEditor');
import('de_brb_hvl_wur_stumml_pages_admin_AdminPage');

class Main
{
    public function __construct()
    {
        $sheet = new AddonErrorPage("No Module specified!");
        try
        {
            switch (strtolower(QI::getPageName()))
            {
                case "special_datasheets_list" :
                    $sheet = new DatasheetsList();
                    break;
                case "special_create_module_list" :
                    $sheet = new ModuleList();
                    break;
                case "special_goods_traffic_basics" :
                    $sheet = new GoodsTrafficBasics();
                    break;
                case "special_develop" :
                    $sheet = new Develop();
                    break;
                case "special_datasheet_editor" :
                    $sheet = new DatasheetEditor();
                    break;
                case "admin_export_data" :
                    $sheet = new AdminPage();
                    break;
                default :
                    // Never reached by gpEasy!
                    break;
            }
        }
        catch (InvalidArgumentException $e)
        {
            $sheet = new AddonErrorPage($e->getMessage());
        }
        $sheet->showContent();
    }
}
?>
