<?php

//import('');
import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_BasicDirectory');
import('de_brb_hvl_wur_stumml_util_ZipBundleFileFilter');
import('de_brb_hvl_wur_stumml_cmd_XmlHtmlTransformCmd');

class ZipTest extends ZipArchive
{
    private $archiveFileName;
    private $dirToArchive;
    private $debug = true;

    public function __construct()
    {
        //parent constructor call not necessary!

        // setup directory to archive
        $this->dirToArchive = Settings::uploadDir();
        $this->printDebug("zu archivierendes Verzeichnis: ".$this->dirToArchive);

        $this->printDebug("<br/>Das gesamte Verzeichnis inklusive seiner Unterordner,");
        $this->printDebug("muss exakt so in das Archiv. Dabei wird die Archivdatei");
        $this->printDebug("in diesem Ordner abgelegt!<br/>");
        // check if directory is writeable for user www-data (php script)
        // otherwise all functions like creating, deleting will not work
        // properly!!!
        if (!is_writable($this->dirToArchive))
        {
            throw new Exception("Directory -".$this->dirToArchive."- has no write permission for php script!");
        }
        
        // archive filename as a getter method of Settings class!
        $this->archiveFileName = $this->dirToArchive."/test.zip";
        $this->printDebug("Archivdateiname inklusive Pfad: ".$this->archiveFileName);

        $xmlFiles = BasicDirectory::scanDirectories($this->dirToArchive, array("xml"));        
        // Wenn die Archivdatei nicht existiert oder 
        // der Zeitstempel der letzte Änderung einer Datenblattdatei 
        // neuer ist als der der Zeitstempel der Archivdatei
        if (!file_exists($this->archiveFileName) || filemtime($this->archiveFileName) < $this->getLatestChange($xmlFiles))
        {
            $this->printDebug("Archiv wird aktualisiert!");
        }
        else
        {
            $this->printDebug("Archiv auf dem neuesten Stand!");
        }

        if (file_exists($this->archiveFileName))
        {
            $this->printDebug("Archivdatei existiert bereits und wird gel&ouml;scht.");
            unlink($this->archiveFileName);
        }

        // grap all files
        //$allFiles = BasicDirectory::scanDirectories($this->dirToArchive, array("dtd", "xsl", "css", "xml", "png", "gif", "jpg", "txt", "html", "csv", "ods"));
        //$this->printDebug($allFiles);
        
        $t = $this->open($this->archiveFileName, ZipArchive::CREATE);
        $this->printDebug("open for creation: ".(($t === true) ? "success" : "failure"));

        // parent full directory path of $dirToArchive, to generate the
        // local path in the archive
        $baseDir = Settings::uploadBaseDir();
        
        $transform = new XmlHtmlTransformCmd("");
        //$dirIt = new RecursiveDirectoryIterator(Settings::uploadDir());
        //$filterIt = new ZipBundleFileFilter($dirIt);
        //$iterator  = new RecursiveIteratorIterator($filterIt, RecursiveIteratorIterator::SELF_FIRST);
        $iterator  = new RecursiveIteratorIterator(new ZipBundleFileFilter(new RecursiveDirectoryIterator(Settings::uploadDir())));
        foreach ($iterator as $key=>$value)
        //foreach ($allFiles as $node)
        {
            $node = $key;
            if (is_file($node) && is_readable($node))
            {
                $node_new = str_replace($baseDir."/", "", $node);
                $this->printDebug("adding ".$node." as ".$node_new);
                if ($transform->doCommand($node))
                {
                    $this->printDebug("Html-Datei angelegt; wird hinzugefuegt!".$transform->getHtmlFile());
                    $this->addFile($transform->getHtmlFile(), $node_new);
                }
                $this->addFile($node, $node_new);
            }
        }
        $this->printDebug("closing created: ".(($this->close() === true) ? "success" : "failure"));
    }

    private function getLatestChange($files)
    {
        $t = array();
        foreach ($files as $f)
        {
            if (substr($f, strrpos($f, '.') + 1) == "xml")
            {
                $t[] = filemtime($f);
            }
        }
        return max($t);
    }

    private function printDebug($str)
    {
        if ($this->debug)
        {
            if (is_array($str))
            {
                print "<pre>".print_r($str, true)."</pre>";
            }
            else
            {
                print $str."<br/>";
            }
        }
        else
        {
            return;
        }
    }
}
?>
