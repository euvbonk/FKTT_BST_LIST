<?php

import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRow');
/* contains StationElement */
import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageElement');

abstract class AbstractYellowPage implements OpenOfficeTableXml
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
        $this->oYellowPageList = array();
    }
    
    public function setDatasheetFileList($list)
    {
        $this->loadDatasheets($list);
    }

    public function getYellowPage()
    {
        return $this->oYellowPageList;
    }

    public function getAsOpenOfficeFormat()
    {
        $str = "";
        if (count($this->getYellowPage())>0)
        {
            $index = 0;
            foreach ($this->getYellowPage() as $row)
            {
                $str .= $row->getAsOpenOfficeFormat();
                $index++;
            }
            $str .= '<table:table-row table:style-name="ro1" table:number-rows-repeated="'.(65534-$index);
        }
        return $str;
    }

    protected function getDatasheets()
    {
        return $this->oFileList;
    }
    
    protected function add(YellowPageTableRow $list)
    {
        $this->oYellowPageList[] = $list;
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
