<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_Settings');

import('de_brb_hvl_wur_stumml_beans_datasheet_xml_StationElement');
import('de_brb_hvl_wur_stumml_io_File');

final class CSVListCmd
{
    public static $FILE_NAME = "bst_list.csv";
    private static $EPOCH = "IV";
    private $oTargetFile;

    public function __construct(FileManager $fm)
    {
        $this->oFileManager = $fm;
        $this->oTargetFile = new File(Settings::uploadDir()."/".self::$FILE_NAME);
    }

    public function doCommand()
    {
        $latest = $this->oFileManager->getLatestFileFromEpoch(self::$EPOCH);
        if ($latest == null)
        {
            return false;
        }
        if (strlen($latest->getPathname()) > 0 &&
                (!$this->oTargetFile->exists() || $this->oTargetFile->getMTime() < $latest->getMTime())
        )
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
                    $station = new StationElement(new SimpleXMLElement($value->getPathname(), null, true));
                    $csvArray[] = array($station->getName(), $station->getShort());
                }

                if ($this->oTargetFile->exists())
                {
                    $this->oTargetFile->delete();
                }

                $fp = fopen($this->oTargetFile->getPathname(), 'w');

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

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->oTargetFile;
    }
}
