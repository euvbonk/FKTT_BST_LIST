<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_cmd_CSVListCmd');
import('de_brb_hvl_wur_stumml_cmd_XmlHtmlTransformCmd');
import('de_brb_hvl_wur_stumml_io_File');
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
        $this->oTargetFile = new File(Settings::uploadDir()."/".self::$FILE_NAME);

        // CSV List is reference because this file is included in zip archive
        //$this->oReferenceFile = Settings::uploadDir()."/".CSVListCmd::$FILE_NAME;
        // TODO Verfeinerung hier notwendig
        $x = $fm->getLatestFileFromEpoch(FileManagerImpl::$EPOCHS[3]);
        $h = str_replace(".xml", ".html", $x);
        if (file_exists($h))
            $refFile = (filemtime($x) > filemtime($h)) ? $x : $h;
        else
            $refFile = $h;
        $this->oReferenceFile = new File($refFile);
        $this->oTransform = new XmlHtmlTransformCmd();
	}

	public function doCommand()
	{
		// TODO: Was ist, wenn nur die Bilddatei geaendert wird? Wie kann
		//       man das sinnvoll herausfinden?
		if ((!$this->oTargetFile->exists() || !$this->oReferenceFile->exists() ||
			$this->oTargetFile->compareMTimeTo($this->oReferenceFile)))
		{
            $dirToArchive = Settings::uploadDir();
            if (!is_writable($dirToArchive))
            {
                $message = "Directory -".$dirToArchive."- has no write ";
                $message .= "permission for php script!";
                throw new Exception($message);
            }

            if ($this->oTargetFile->exists())
            {
                $this->oTargetFile->delete();
            }

            // parent full directory path of $dirToArchive, to generate the
            // local path in the archive
            $baseDir = Settings::uploadBaseDir();
            
            $iterator  = new RecursiveIteratorIterator(
                    new ZipBundleFileFilter(new RecursiveDirectoryIterator($dirToArchive)));

            $zip = new ZipArchive();
            $zip->open($this->oTargetFile->getPath(), ZipArchive::CREATE);
            foreach ($iterator as $key => $value)
            {
                $node = new File($key);
                if ($node->isFile() && $node->isReadable())
                {
                    $node_new = str_replace($baseDir."/", "", $node->getPath());
                    if ($this->oTransform->doCommand($node))
                    {
                        $zip->addFile($this->oTransform->getHtmlFile()->getPath(), str_replace(".xml", ".html", $node_new));
                    }
                    // TODO: add HTML File which gives view of fpl.xsl
                    $zip->addFile($node->getPath(), $node_new);
                }
            }
			// TODO: Update bstlist.html bzw. index.html which shows 
			//       a local view of all datasheets
            $zip->close();
            $this->oTargetFile->changeFileRights(0666);
			return true;
		}
		return false;
	}

    public function getFileName()
    {
        if ($this->oTargetFile->exists())
        {
            return $this->oTargetFile->getPath();
        }
        else
        {
            return "#";
        }
    }    
}
