<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_xml_StationElement');
import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRowList');
import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRowCellList');
import('de_brb_hvl_wur_stumml_util_openOffice_SpreadsheetXml');

abstract class AbstractYellowPage implements SpreadsheetXml
{
    private $oYellowPageList = null;
    private $oFileList = null;

    public function __construct($fileList = array())
    {
        if (!empty($fileList))
        {
            $this->setDatasheetFileList($fileList);
        }
        else
        {
            $this->oFileList = array();
        }
        $this->oYellowPageList = new YellowPageTableRowList();
    }
    
    public function setDatasheetFileList($list)
    {
        $this->loadDatasheets($list);
    }

    public function getYellowPage()
    {
        return $this->oYellowPageList;
    }

    public function getAsSpreadsheetXml()
    {
        return $this->oYellowPageList->getAsSpreadsheetXml();
    }

    protected function getDatasheets()
    {
        return $this->oFileList;
    }
    
    protected function addRow(YellowPageTableRowCellList $list)
    {
        $this->oYellowPageList->addRow($list);
    }
    
    private function loadDatasheets($list)
    {
        if (count($list) > 0)
        {
            foreach ($list as $value)
            {
                // load as file url
                $this->oFileList[] = new StationElement(new SimpleXMLElement($value, null, true));
            }
        }
    }
    
    public abstract function generate();
}

?>
