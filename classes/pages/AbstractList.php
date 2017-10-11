<?php
namespace org\fktt\bstlist\pages;

\import('beans_datasheet_FileManagerImpl');
\import('pages_Frame');
\import('pages_FrameForm');
\import('util_QI');
\import('util_reportTable_ReportTableListImpl');

use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use org\fktt\bstlist\util\QI;
use org\fktt\bstlist\util\reportTable\ReportTableListImpl;

abstract class AbstractList extends Frame implements FrameForm
{
    private $epoch = "IV";
    private $countryCodes = null;

    private $oFileManager;
    private $oReportTable;

    public function __construct($templateFile)
    {
        parent::__construct($templateFile);
        $this->setEpoch(FileManagerImpl::$EPOCHS[3]);
        $this->oFileManager = new FileManagerImpl();
        $this->setCountryCodes($this->getFileManager()->getAllCountryCodes());
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

    protected final function setCountryCodes($countryCodes = array())
    {
        $this->countryCodes = $countryCodes;
    }

    protected final function getCountryCodes()
    {
        return $this->countryCodes;
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
        return array('FormActionUri', 'EpochOptionsUI', 'Table', 'CountryCodesUI');
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

    public final function CountryCodesUI()
    {
        $str = "";
        $countries = $this->getFileManager()->getAllCountryCodes();
        foreach ($countries as $country)
        {
            $str .= "<label><input type=\"checkbox\" name=\"countryCodes[]\" value=\"".$country."\"".((\in_array($country, $this->countryCodes)) ? " checked=\"checked\"" : "")." style=\"vertical-align:middle;\"/>";
            $str .= "&thinsp;<span style=\"vertical-align:bottom;\">".\strtoupper($country)."</span></label>&emsp;";
        }
        return $str;
    }

    public final function Table()
    {
        return $this->getReportTable()->getHtml();
    }
}
