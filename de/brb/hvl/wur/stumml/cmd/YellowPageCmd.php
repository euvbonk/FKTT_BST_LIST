<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageSpreadsheetGenerator');
import('de_brb_hvl_wur_stumml_beans_yellowPage_FkttYellowPage');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_io_TemplateFile');

final class YellowPageCmd
{
    private static $FILE_NAMES = array("I" => "YellowPage-I.ods", "II" => "YellowPage-II.ods",
        "III" => "YellowPage-III.ods", "IV" => "YellowPage.ods", "V" => "YellowPage-V.ods",
        "VI" => "YellowPage-VI.ods");
    private $oTemplateFile;
    private $oTargetFile;

    /**
     * @param FileManager $fm
     * @return YellowPageCmd
     */
    public function __construct(FileManager $fm)
    {
        $this->oFileManager = $fm;
        $this->oTemplateFile = new TemplateFile("yellow-page.ots");
        $this->renameFile("IV");
        return $this;
    }

    /**
     * @param string $epoch
     * @return bool
     */
    public function doCommand($epoch)
    {
        if (strlen($epoch) == 0)
        {
            return false;
        }
        $this->renameFile($epoch);
        /** @var $latest File */
        $latest = $this->oFileManager->getLatestFileFromEpoch($epoch);
        if ($latest == null)
        {
            return false;
        }
        if (strlen($latest->getPathname()) > 0 &&
                (!$this->getFile()->exists() || $this->getFile()->getMTime() < $latest->getMTime())
        )
        {
            $page = new FkttYellowPage();
            $page->setDatasheetFileList($this->oFileManager->getFilesFromEpochWithOrder($epoch));
            $page->generate();

            if ($this->getFile()->exists())
            {
                $this->getFile()->delete();
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

    /**
     * @param string $epoch
     */
    protected function renameFile($epoch)
    {
        $this->oTargetFile = new File("db/".self::$FILE_NAMES[$epoch]);
    }
}
