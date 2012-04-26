<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_cmd_CSVListCmd');
import('de_brb_hvl_wur_stumml_cmd_XmlHtmlTransformCmd');
import('de_brb_hvl_wur_stumml_util_ZipBundleFileFilter');

final class ZipBundleCmd
{
    private static $FILE_NAME = "datasheetsAndYellowPages.zip";
    private $oTargetFile;
    private $oReferenceFile;
    private $oTransform;

	public function __construct(FileManager $fm)
	{
		$this->oFileManager = $fm;
        $this->oTargetFile = Settings::uploadDir()."/".self::$FILE_NAME;
        // CSV List is reference because this file is included in zip archive
        $this->oReferenceFile = Settings::uploadDir()."/".CSVListCmd::$FILE_NAME;
        $this->oTransform = new XmlHtmlTransformCmd();
	}

	public function doCommand()
	{
		if ((!file_exists($this->oTargetFile) ||
			filemtime($this->oTargetFile) < filemtime($this->oReferenceFile)))
		{
            $dirToArchive = Settings::uploadDir();
            if (!is_writable($dirToArchive))
            {
                $message = "Directory -".$dirToArchive."- has no write ";
                $message .= "permission for php script!";
                throw new Exception($message);
            }

            if (file_exists($this->oTargetFile))
            {
                unlink($this->oTargetFile);
            }

            // parent full directory path of $dirToArchive, to generate the
            // local path in the archive
            $baseDir = Settings::uploadBaseDir();
            
            $iterator  = new RecursiveIteratorIterator(
                    new ZipBundleFileFilter(
                        new RecursiveDirectoryIterator($dirToArchive)));

            $zip = new ZipArchive();
            $zip->open($this->oTargetFile, ZipArchive::CREATE);
            foreach ($iterator as $key => $value)
            {
                $node = $key;
                if (is_file($node) && is_readable($node))
                {
                    $node_new = str_replace($baseDir."/", "", $node);
                    if ($this->oTransform->doCommand($node))
                    {
                        $zip->addFile($this->oTransform->getHtmlFile(), $node_new);
                    }
                    $zip->addFile($node, $node_new);
                }
            }
            $zip->close();
            chmod($this->oTargetFile, 0666);
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
