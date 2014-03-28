<?php
namespace org\fktt\bstlist\beans\yellowpage;

\import('beans_datasheet_xml_StationElement');
\import('beans_yellowPage_YellowPageTableRowList');
\import('beans_yellowPage_YellowPageTableRowCellList');
\import('util_openOffice_SpreadsheetXml');

use SimpleXMLElement;
use org\fktt\bstlist\beans\datasheet\xml\StationElement;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\util\openOffice\SpreadsheetXml;

abstract class AbstractYellowPage implements SpreadsheetXml
{
    private $oYellowPageList = null;
    private $oFileList = null;

    /**
     * @param array $fileList [optional]
     * @return AbstractYellowPage
     */
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
        return $this;
    }

    /**
     * @param array $list
     */
    public function setDatasheetFileList($list)
    {
        $this->loadDatasheets($list);
    }

    /**
     * @return null|YellowPageTableRowList
     */
    public function getYellowPage()
    {
        return $this->oYellowPageList;
    }

    /**
     * @return string
     */
    public function getAsSpreadsheetXml()
    {
        return $this->oYellowPageList->getAsSpreadsheetXml();
    }

    /**
     * @return array|null
     */
    protected function getDatasheets()
    {
        return $this->oFileList;
    }

    /**
     * @param YellowPageTableRowCellList $list
     */
    protected function addRow(YellowPageTableRowCellList $list)
    {
        $this->oYellowPageList->addRow($list);
    }

    /**
     * @param array $list
     */
    private function loadDatasheets($list)
    {
        if (\count($list) > 0)
        {
            /** @var $value File */
            foreach ($list as $value)
            {
                // load as file url
                $this->oFileList[] = new StationElement(new SimpleXMLElement($value->getPathname(), null, true));
            }
        }
    }

    /**
     * @abstract
     * @return void
     */
    public abstract function generate();
}