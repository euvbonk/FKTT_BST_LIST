<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_Settings');

import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageSpreadsheetGenerator');
import('de_brb_hvl_wur_stumml_beans_yellowPage_FkttYellowPage');

final class YellowPageCmd
{
	private $oFileList;
	private $oTemplateFile;
    private $oTargetFile;

	public function __construct(FileManager $fm)
	{
		$this->oFileManager = $fm;
		$this->oTemplateFile = Settings::uploadDir()."/yellow-page.ots";
        $this->oTargetFile = Settings::uploadDir()."/YellowPage.ods";
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
		return false;
	}

    public function getFileName()
    {
        return $this->oTargetFile;
    }    

    protected function renameFile($epoch)
    {
        if ($epoch == "IV") return;
        $this->oTargetFile = substr_replace($this->oTargetFile, "-".$epoch, strrpos($this->oTargetFile, "."), 0);
    }
}
?>
