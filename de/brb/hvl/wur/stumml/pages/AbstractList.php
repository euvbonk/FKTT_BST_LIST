<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');
import('de_brb_hvl_wur_stumml_io_File');

import('de_brb_hvl_wur_stumml_util_QI');
import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableListImpl');

abstract class AbstractList extends Frame implements FrameForm
{
    private $epoch = "IV";

    private $oFileManager;
    private $oReportTable;

    public function __construct(File $templateFile)
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
        return array('getFormActionUri', 'getEpochOptionsUI', 'getTable');
    }
    /**
     * @see Interface FrameForm
     * @return String
     */
    public final function getFormActionUri()
    {
        return QI::getPageUri();
    }

    public final function getEpochOptionsUI()
    {
        $str = "";
        foreach (FileManagerImpl::$EPOCHS as $value)
        {
            $str .= "<option".(($this->epoch == $value) ? " selected=\"selected\"" : "").">".$value."</option>";
        }
        return $str;
    }
    
    public final function getTable()
    {
        return $this->getReportTable()->getHtml();
    }
}
