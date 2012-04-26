<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_Settings');

import('de_brb_hvl_wur_stumml_beans_datasheet_xml_StationElement');

final class CSVListCmd
{
    public static $FILE_NAME = "bst_list.csv";
    private static $EPOCH = "IV";
    private $oTargetFile;

	public function __construct(FileManager $fm)
	{
		$this->oFileManager = $fm;
        $this->oTargetFile = Settings::uploadDir()."/".self::$FILE_NAME;
	}

	public function doCommand()
	{
        $latest = $this->oFileManager->getLatestFileFromEpoch(self::$EPOCH);
		if (strlen($latest) > 0 && (!file_exists($this->oTargetFile) ||
			filemtime($this->oTargetFile) < filemtime($latest)))
		{

            //$page = new FkttYellowPage();
            //$page->setDatasheetFileList($this->oFileManager->getFilesFromEpochWithOrder($epoch));
            //$page->generate();
            $list = $this->oFileManager->getFilesFromEpochWithOrder(self::$EPOCH);
            // setDatasheetFileList && loadDatasheets && generate
            if (count($list) > 0)
            {
                $csvArray = array();
                foreach ($list as $value)
                {
                    // load as file url
                    $station = new StationElement(new SimpleXMLElement($value, null, true));
                    $csvArray[] = array($station->getName(), $station->getShort());
                }

                if (file_exists($this->oTargetFile))
                {
                    unlink($this->oTargetFile);
                }

                $fp = fopen($this->oTargetFile, 'w');

                foreach ($csvArray as $fields)
                {
                    fputcsv($fp, $fields, ",", '"');
                }

                fclose($fp);
            }

            //$calc = new YellowPageSpreadsheetGenerator();
            //$calc->openDocumentFromFile($this->oTemplateFile);

            //$calc->setYellowPage($page->getAsSpreadsheetXml());
            //$calc->generate();

            //$calc->saveDocumentToFile($this->oTargetFile);
            //$calc->closeDocument();
			return true;
		}
		return false;
	}

    public function getFileName()
    {
        if (file_exists($this->oTargetFile))
        {
            return $this->oTargetFile;
        }
        else
        {
            return "#";
        }
    }    
}
?>
