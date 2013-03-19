<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_Settings');

import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageSpreadsheetGenerator');
import('de_brb_hvl_wur_stumml_beans_yellowPage_FkttYellowPage');
import('de_brb_hvl_wur_stumml_io_File');

final class YellowPageCmd
{
    private static $FILE_NAMES = array("I" => "YellowPage-I.ods", "II" => "YellowPage-II.ods",
        "III" => "YellowPage-III.ods", "IV" => "YellowPage.ods", "V" => "YellowPage-V.ods",
        "VI" => "YellowPage-VI.ods");
    private $oTemplateFile;
    private $oTargetFile;

    public function __construct(FileManager $fm)
    {
        $this->oFileManager = $fm;
        $this->oTemplateFile = new File(Settings::addonTemplateBaseDir()."/yellow-page.ots");
        $this->renameFile("IV");
    }

    public function doCommand($epoch)
    {
        if (strlen($epoch) == 0)
        {
            return false;
        }
        $this->renameFile($epoch);
        $latest = new File($this->oFileManager->getLatestFileFromEpoch($epoch));
        if (strlen($latest->getPathname()) > 0 &&
                (!$this->oTargetFile->exists() || !$this->oTargetFile->compareMTimeTo($latest))
        )
        {
            $page = new FkttYellowPage();
            $page->setDatasheetFileList($this->oFileManager->getFilesFromEpochWithOrder($epoch));
            $page->generate();

            if ($this->oTargetFile->exists())
            {
                $this->oTargetFile->delete();
            }

            $calc = new YellowPageSpreadsheetGenerator();
            $calc->openDocumentFromFile($this->oTemplateFile);

            $calc->setYellowPage($page->getAsSpreadsheetXml());
            $calc->generate();

            $calc->saveDocumentToFile($this->oTargetFile);
            $calc->closeDocument();
            return true;
        }
        return false;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->oTargetFile;
    }

    protected function renameFile($epoch)
    {
        $this->oTargetFile = new File(Settings::uploadDir()."/".self::$FILE_NAMES[$epoch]);
    }
}
