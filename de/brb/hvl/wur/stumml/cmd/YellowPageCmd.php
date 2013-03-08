<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_Settings');

import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageSpreadsheetGenerator');
import('de_brb_hvl_wur_stumml_beans_yellowPage_FkttYellowPage');

final class YellowPageCmd
{
    private static $FILE_NAMES = array("I" => "YellowPage-I.ods", "II" => "YellowPage-II.ods", "III" => "YellowPage-III.ods", "IV" => "YellowPage.ods", "V" => "YellowPage-V.ods", "VI" => "YellowPage-VI.ods");
	private $oFileList;
	private $oTemplateFile;
    private $oTargetFile;

	public function __construct(FileManager $fm)
	{
		$this->oFileManager = $fm;
		$this->oTemplateFile = Settings::addonTemplateBaseDir()."/yellow-page.ots";
        $this->renameFile("IV");
	}

	public function doCommand($epoch)
	{
		if (strlen($epoch) == 0) return false;
        $this->renameFile($epoch);
        $latest = $this->oFileManager->getLatestFileFromEpoch($epoch);
		if (strlen($latest) > 0 && (!file_exists($this->oTargetFile) ||
			filemtime($this->oTargetFile) < filemtime($latest)))
		{

            $page = new FkttYellowPage();
            $page->setDatasheetFileList($this->oFileManager->getFilesFromEpochWithOrder($epoch));
            $page->generate();

            if (file_exists($this->oTargetFile))
            {
                unlink($this->oTargetFile);
            }

            $calc = new YellowPageSpreadsheetGenerator();
            $calc->openDocumentFromFile($this->oTemplateFile);

            $calc->setYellowPage($page->getAsSpreadsheetXml());
            $calc->generate();

            $calc->saveDocumentToFile($this->oTargetFile);
            $calc->closeDocument();
			return true;
		}
		elseif (strlen($latest) > 0 && file_exists($this->oTargetFile))
		{
			return true;
		}
		return false;
	}

    public function getFileName()
    {
        return $this->oTargetFile;
    }    

    protected function renameFile($epoch)
    {
        $this->oTargetFile = Settings::uploadDir()."/".self::$FILE_NAMES[$epoch];
    }
}
?>
