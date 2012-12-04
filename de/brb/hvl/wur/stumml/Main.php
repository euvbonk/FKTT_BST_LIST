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
                    if (QI::getCommand() == "")
                    {
                        $sheet = new AddonErrorPage("Kein Kommando angegeben oder Kommando fehlerhaft!");
                    }
                    else
                    {
                        import('de_brb_hvl_wur_stumml_Settings');
                        $short = strtolower(QI::getCommand());
                        $xmlFile = Settings::uploadDir().DIRECTORY_SEPARATOR.$short.DIRECTORY_SEPARATOR.$short.".xml";
                        if (!file_exists($xmlFile))
                        {
                            $sheet = new AddonErrorPage("Angegebenes Datenblatt existiert nicht!");
                        }
                        else
                        {
                            $xslFile = Settings::uploadDir().DIRECTORY_SEPARATOR."fpl.xsl";
                            $proc = new XSLTProcessor();
                            $proc->importStylesheet(DOMDocument::load($xslFile));
                            $html = $proc->transformToXML(DOMDocument::load($xmlFile));
                            $basePath = dirname("http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).DIRECTORY_SEPARATOR.substr($xmlFile, strlen(QI::getRootDir())+1));
                            $html = str_replace("bahnhof.css", $basePath.DIRECTORY_SEPARATOR."bahnhof.css", $html);
                            $html = str_replace("img src=\"".$short, "img src=\"".$basePath.DIRECTORY_SEPARATOR.$short, $html);
                            echo $html;
                            exit;
                        }
                    }
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
