<?php

\import('pages_AddonErrorPage');
\import('pages_admin_AdminPage');
\import('pages_admin_SyncFormatFiles');
\import('pages_admin_LatestSheetChangesList');
\import('pages_editor_DatasheetEditor');
\import('pages_datasheet_DatasheetsList');
//\import('pages_datasheet_EditDatasheet');
\import('pages_datasheet_SheetView');
\import('pages_develop_Develop');
\import('pages_goodsTraffic_GoodsTrafficBasics');
\import('pages_moduleList_ModuleList');
\import('pages_remoteUpload_RemoteSheetUpload');
\import('util_QI');

use org\fktt\bstlist\pages\AddonErrorPage;
use org\fktt\bstlist\pages\admin\AdminPage;
use \org\fktt\bstlist\pages\LatestSheetChangesList;
use org\fktt\bstlist\pages\admin\SyncFormatFiles;
use org\fktt\bstlist\pages\datasheet\DatasheetsList;
//use org\fktt\bstlist\pages\datasheet\EditDatasheet;
use org\fktt\bstlist\pages\datasheet\SheetView;
use org\fktt\bstlist\pages\develop\Develop;
use org\fktt\bstlist\pages\editor\DatasheetEditor;
use org\fktt\bstlist\pages\goodsTraffic\GoodsTrafficBasics;
use org\fktt\bstlist\pages\moduleList\ModuleList;
use org\fktt\bstlist\pages\remoteUpload\RemoteSheetUpload;
use org\fktt\bstlist\util\QI;

class Main
{
    public function __construct()
    {
        $sheet = new AddonErrorPage("No Module specified!");
        try
        {
            \setlocale(LC_TIME, "de_DE.utf8");
            \date_default_timezone_set("Europe/Berlin");
            switch (\strtolower(QI::getPageName()))
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
                case "fpl_view":
                    $sheet = new SheetView(QI::getCommand(), "fpl");
                    break;
                /*case "edit_datasheet":
                    $sheet = new EditDatasheet(QI::getCommand());
                    break;*/
                case "remote_sheet_upload":
                    $sheet = new RemoteSheetUpload();
                    break;
                case "sheet_view" :
                    $sheet = new SheetView(QI::getCommand(), "bahnhof", QI::getCommand('lang'));
                    break;
                case "admin_export_data" :
                    $sheet = new AdminPage();
                    break;
                case "admin_sync_format_files" :
                    $sheet = new SyncFormatFiles();
                    break;
                case "admin_latest_sheet_changes_list" :
                    $sheet = new LatestSheetChangesList();
                    break;
                default :
                    // Never reached by gpEasy!
                    break;
            }
        }
        catch (\Exception $e)
        {
            $sheet = new AddonErrorPage($e->getMessage());
        }
        $sheet->showContent();
        return $this;
    }
}
