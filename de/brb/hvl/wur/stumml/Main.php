<?php

import('de_brb_hvl_wur_stumml_pages_AddonErrorPage');
import('de_brb_hvl_wur_stumml_util_QI');
import('de_brb_hvl_wur_stumml_pages_datasheet_DatasheetsList');
import('de_brb_hvl_wur_stumml_pages_datasheet_FplView');
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
            setlocale(LC_TIME, "de_DE.utf8");
            switch (strtolower(QI::getPageName()))
            {
                case "datasheets_list" :
                    $sheet = new DatasheetsList();
                    break;
                case "create_module_list" :
                    $sheet = new ModuleList();
                    break;
                case "goods_traffic_basics" :
                    $sheet = new GoodsTrafficBasics();
                    break;
                case "develop" :
                    $sheet = new Develop();
                    break;
                case "datasheet_editor" :
                    $sheet = new DatasheetEditor();
                    break;
                case "admin_export_data" :
                    $sheet = new AdminPage();
                    break;
                case "fpl_view":
                    $sheet = new FplView(QI::getCommand());
                    break;
                default :
                    // Never reached by gpEasy!
                    break;
            }
        }
        catch (Exception $e)
        {
            $sheet = new AddonErrorPage($e->getMessage());
        }
        $sheet->showContent();
        return $this;
    }
}
