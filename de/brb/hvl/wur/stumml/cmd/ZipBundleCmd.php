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
    private $oTargetFile = null;

    public function __construct(FileManager $fm)
    {
        $this->oFileManager = $fm;
    }

    public function doCommand()
    {
        $dirToArchive = Settings::uploadDir();
        if (!is_writable($dirToArchive))
        {
            $message = "Directory -".$dirToArchive."- has no write ";
            $message .= "permission for php script!";
            throw new Exception($message);
        }

        /* CSV Datei ist nicht bestandteil des Zip-Bundles!
        $CSVListCmd = new CSVListCmd($this->oFileManager);
        // wird nur ausgefuehrt, wenn es ein neueres Datenblatt gibt!
        $CSVListCmd->doCommand();*/

        $yellowPageCmd = new YellowPageCmd($this->oFileManager);
        // Zwei Transformer Commands
        $transformNormal = new XmlHtmlTransformCmd(new File($dirToArchive."/bahnhof.xsl"));
        $transformFpl = new XmlHtmlTransformCmd(new File($dirToArchive."/fpl.xsl"));

        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            // falls Aenderungen am Datenblatt, dann muessen zwingend gelbe Seiten ebenfalls aktualisiert werden.
            $yellowPageCmd->doCommand($epoch);
            // pruefe jetzt fuer alle Datenblaetter, ob die entsprechenden HTML Dateien aktuell sind und aktualisiere
            // sie falls notwendig.
            foreach ($this->oFileManager->getFilesFromEpochWithOrder($epoch) as $file)
            {
                //$file = new File($file);
                $transformNormal->doCommand($file);
                $transformFpl->doCommand($file);
            }
        }

        $allFiles = array();
        $iterator =
                new RecursiveIteratorIterator(new ZipBundleFileFilter(new RecursiveDirectoryIterator($dirToArchive)));
        // sammle jetzt alle Dateien, die fuer das Zip-Bundle in Frage kommen, in einem Array
        foreach ($iterator as $file)
        {
            if ($file->isFile() && $file->isReadable())
            {
                $allFiles[] = $file;
            }
        }
        // Sortiere alle Dateien so, dass die zuletzt geaenderte Datei ganz am Anfang des Array steht!
        usort($allFiles, array("File", "compareLastModified"));
        // Testausgabe. Achtung $file ist vom Typ SplFileInfo Objekt!
        /*foreach ($allFiles as $file)
        {
            echo $file->getPathname()." = > ".strftime("%a, %d. %b %Y %H:%M", $file->getMTime())."<br>";
        }*/

        $this->oTargetFile = new File($dirToArchive."/".self::$FILE_NAME);

        // zunaechst muessen ueberhaupt Dateien zum Archivieren vorhanden sein
        // Dann soll auf jedenfall ein Archiv erstellt werden, wenn die Zieldatei noch gar nicht existiert oder eben
        // falls neueste Datei aller Dateien spaeter modifiziert wurde als die Zieldatei
        if (
            count($allFiles) > 0 && (!$this->oTargetFile->exists() || !$this->oTargetFile->compareMTimeTo($allFiles[0]))
        )
        {
            if ($this->oTargetFile->exists())
            {
                $this->oTargetFile->delete();
            }

            // parent full directory path of $dirToArchive, to generate the
            // local path in the archive
            $baseDir = Settings::uploadBaseDir();

            $zip = new ZipArchive();
            $zip->open($this->oTargetFile->getPathname(), ZipArchive::CREATE);
            foreach ($allFiles as $node)
            {
                if ($node->isFile() && $node->isReadable())
                {
                    $zip->addFile($node->getPathname(), str_replace($baseDir."/", "", $node->getPathname()));
                }
            }
            // TODO: Update bstlist.html bzw. index.html which shows
            //       a local view of all datasheets
            //       create bstlist for all epochs and add them to archive
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
