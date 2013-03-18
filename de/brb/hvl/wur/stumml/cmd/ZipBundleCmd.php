<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');

import('de_brb_hvl_wur_stumml_Settings');
//import('de_brb_hvl_wur_stumml_cmd_CSVListCmd');
import('de_brb_hvl_wur_stumml_cmd_XmlHtmlTransformCmd');
import('de_brb_hvl_wur_stumml_cmd_YellowPageCmd');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_util_ZipBundleFileFilter');

final class ZipBundleCmd
{
    private static $FILE_NAME = "datasheetsAndYellowPages.zip";
    private $oFileManager;
    private $oTargetFile;
    private $oReferenceFile;
    private $oTNormal;
    private $oTFPL;

    public function __construct(FileManager $fm)
    {
        $this->oFileManager = $fm;
        $ud = Settings::uploadDir()."/";
        $this->oTargetFile = new File($ud.self::$FILE_NAME);

        // TODO Verfeinerung hier notwendig, was soll das ReferenceFile sein?
        // TODO: Was ist, wenn nur die Bilddatei geaendert wird? Wie kann
        //       man das sinnvoll herausfinden?
        $x = $fm->getLatestFileFromEpoch(FileManagerImpl::$EPOCHS[3]);
        $h = str_replace(".xml", ".html", $x);
        if (file_exists($h))
        {
            $refFile = (filemtime($x) > filemtime($h)) ? $x : $h;
        }
        else
        {
            $refFile = $h;
        }
        $this->oReferenceFile = new File($refFile);

        // Zwei Transformer Commands
        $this->oTNormal = new XmlHtmlTransformCmd(new File($ud."bahnhof.xsl"));
        $this->oTFPL = new XmlHtmlTransformCmd(new File($ud."fpl.xsl"));
    }

    public function doCommand()
    {
        /* CSV Datei ist nicht bestandteil des Zip-Bundles!
        $CSVListCmd = new CSVListCmd($this->oFileManager);
        // wird nur ausgefuehrt, wenn es ein neueres Datenblatt gibt!
        $CSVListCmd->doCommand();*/

        $yellowPageCmd = new YellowPageCmd($this->oFileManager);
        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            // wird nur ausgefuehrt, wenn es ein neueres Datenblatt fuer die jeweilige Epoche gibt!
            $yellowPageCmd->doCommand($epoch);
        }

        if ((!$this->oTargetFile->exists() || !$this->oReferenceFile->exists() ||
                !$this->oTargetFile->compareMTimeTo($this->oReferenceFile))
        )
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

            $iterator =
                    new RecursiveIteratorIterator(new ZipBundleFileFilter(new RecursiveDirectoryIterator($dirToArchive)));

            $zip = new ZipArchive();
            $zip->open($this->oTargetFile->getPath(), ZipArchive::CREATE);
            foreach ($iterator as $key => $value)
            {
                $node = new File($key);
                if ($node->isFile() && $node->isReadable())
                {
                    $node_new = str_replace($baseDir."/", "", $node->getPath());
                    if ($this->oTNormal->doCommand($node))
                    {
                        $zip->addFile($this->oTNormal->getHtmlFile()->getPath(),
                            str_replace(".xml", ".html", $node_new));
                    }
                    $hFPL = new File(str_replace(".xml", "_fpl.html", $node->getPath()));
                    if ($this->oTFPL->doCommand($node, $hFPL))
                    {
                        $zip->addFile($this->oTFPL->getHtmlFile()->getPath(),
                            str_replace(".xml", "_fpl.html", $node_new));
                    }
                    $zip->addFile($node->getPath(), $node_new);
                }
            }
            // TODO: Update bstlist.html bzw. index.html which shows
            //       a local view of all datasheets
            //       create bstlist for all epochs
            $zip->close();
            $this->oTargetFile->changeFileRights(0666);
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
