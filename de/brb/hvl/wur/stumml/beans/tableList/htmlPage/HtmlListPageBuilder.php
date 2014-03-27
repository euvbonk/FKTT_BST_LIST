<?php
namespace org\fktt\bstlist\beans\tableList\htmlPage;

\import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');
\import('de_brb_hvl_wur_stumml_beans_tableList_htmlPage_AbstractHtmlPageBuilder');

\import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_HtmlPageDatasheetList');
\import('de_brb_hvl_wur_stumml_cmd_YellowPageCmd');
\import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableListImpl');

use org\fktt\bstlist\beans\datasheet\FileManager;
use org\fktt\bstlist\beans\tableList\datasheet\HtmlPageDatasheetList;
use org\fktt\bstlist\cmd\YellowPageCmd;
use org\fktt\bstlist\util\reportTable\ReportTableList;
use org\fktt\bstlist\util\reportTable\ReportTableListImpl;

class HtmlListPageBuilder extends AbstractHtmlPageBuilder
{
    private $oCurrentEpoch;
    private $oReportTable = null;
    private $oList = null;
    private $oYellowPage = null;
    private $oFileManager;

    protected function getTemplateFileName()
    {
        return "template_list.php";
    }

    protected function getActions()
    {
        return array("EPOCH" => '$this->getCurrentEpoch()', "ENTRIES" => '$this->getTableEntries()',
            "YELLOW_PAGE" => '$this->getYellowPageLink()');
    }

    public final function setEpoch($epoch)
    {
        $this->oCurrentEpoch = $epoch;
    }

    public function setFileManager($fm)
    {
        $this->oFileManager = $fm;
    }

    protected function getCurrentEpoch()
    {
        return $this->oCurrentEpoch;
    }

    protected function getTableEntries()
    {
        return $this->getReportTable()->getTableBody();
    }

    protected function getYellowPageLink()
    {
        if ($this->getYellowPage()->getFile()->exists())
        {
            $file = $this->getYellowPage()->getFile();
            $uri = $file->toHttpUrl();
            $link = $this->getYellowPage()->getFile()->toDownloadLink("Gelbe Seiten für die Epoche ".
            $this->getCurrentEpoch());
            return \str_replace($uri, $file->getBasename(), $link);
        }
        else
        {
            return "";
        }
    }

    /**
     * @return YellowPageCmd
     */
    private function getYellowPage()
    {
        return $this->oYellowPage;
    }

    /**
     * @return ReportTableList
     */
    private function getReportTable()
    {
        return $this->oReportTable;
    }

    /**
     * @return FileManager
     */
    private function getFileManager()
    {
        return $this->oFileManager;
    }

    public function doCommandBefore()
    {
        if ($this->oList == null)
        {
            $this->oList = new HtmlPageDatasheetList(false);
        }
        $this->oList->buildTableEntries($this->getFileManager()->getFilesFromEpochWithOrder($this->getCurrentEpoch()));
        if ($this->oReportTable == null)
        {
            $this->oReportTable = new ReportTableListImpl();
        }
        $this->oReportTable->setTableBody($this->oList->getTableEntries());
        if ($this->oYellowPage == null)
        {
            $this->oYellowPage = new YellowPageCmd($this->getFileManager());
        }
        $this->oYellowPage->doCommand($this->getCurrentEpoch());
    }
}