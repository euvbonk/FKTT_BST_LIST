<?php
namespace org\fktt\bstlist\pages;

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');
use org\fktt\bstlist\beans\datasheet\FileManagerImpl;

import('de_brb_hvl_wur_stumml_util_QI');
use org\fktt\bstlist\util\QI;

import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableListImpl');
use org\fktt\bstlist\util\reportTable\ReportTableListImpl;

abstract class AbstractList extends Frame implements FrameForm
{
    private $epoch = "IV";

    private $oFileManager;
    private $oReportTable;

    public function __construct($templateFile)
    {
        parent::__construct($templateFile);
        $this->setEpoch(FileManagerImpl::$EPOCHS[3]);
        $this->oFileManager = new FileManagerImpl();
        $this->oReportTable = new ReportTableListImpl();
    }

    protected final function setEpoch($epoch = "IV")
    {
        $this->epoch = $epoch;
    }

    protected final function getEpoch()
    {
        return $this->epoch;
    }

    protected final function getFileManager()
    {
        return $this->oFileManager;
    }

    protected final function getReportTable()
    {
        return $this->oReportTable;
    }

    protected function getCallableMethods()
    {
        return array('FormActionUri', 'EpochOptionsUI', 'Table');
    }
    /**
     * @see Interface FrameForm
     * @return String
     */
    public final function FormActionUri()
    {
        return QI::getPageUri();
    }

    public final function EpochOptionsUI()
    {
        $str = "";
        foreach (FileManagerImpl::$EPOCHS as $value)
        {
            $str .= "<option".(($this->epoch == $value) ? " selected=\"selected\"" : "").">".$value."</option>";
        }
        return $str;
    }
    
    public final function Table()
    {
        return $this->getReportTable()->getHtml();
    }
}
