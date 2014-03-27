<?php
namespace org\fktt\bstlist\cmd;

\import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');
\import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_AbstractDatasheetList');
\import('de_brb_hvl_wur_stumml_beans_tableList_htmlPage_HtmlIndexPageBuilder');
\import('de_brb_hvl_wur_stumml_beans_tableList_htmlPage_HtmlListPageBuilder');

//\import('de_brb_hvl_wur_stumml_cmd_CSVListCmd');
\import('de_brb_hvl_wur_stumml_cmd_XmlHtmlTransformCmd');
\import('de_brb_hvl_wur_stumml_cmd_YellowPageCmd');
\import('de_brb_hvl_wur_stumml_io_File');
\import('de_brb_hvl_wur_stumml_io_GlobIterator');
\import('de_brb_hvl_wur_stumml_util_ZipBundleFileFilter');

use Exception;
use SplFileInfo;
use ZipArchive;
use org\fktt\bstlist\beans\datasheet\FileManager;
use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use org\fktt\bstlist\beans\tableList\htmlPage\HtmlIndexPageBuilder;
use org\fktt\bstlist\beans\tableList\htmlPage\HtmlListPageBuilder;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\io\GlobIterator;

final class ZipBundleCmd
{
    private static $FILE_NAME = "datasheetsAndYellowPages.zip";
    private $oFileManager;
    private $oTargetFile = null;

    /**
     * @param FileManager $fm
     * @return ZipBundleCmd
     */
    public function __construct(FileManager $fm)
    {
        $this->oFileManager = $fm;
        return $this;
    }

    /**
     * @return bool
     * @throws Exception if directory has no write permission
     */
    public function doCommand()
    {
        $dirToArchive = new File("db");
        if (!$dirToArchive->isWritable())
        {
            $message = "Directory -".$dirToArchive->getPathname()."- has no write ";
            $message .= "permission for php script!";
            throw new Exception($message);
        }

        /* CSV Datei ist nicht bestandteil des Zip-Bundles!
        $CSVListCmd = new CSVListCmd($this->oFileManager);
        // wird nur ausgefuehrt, wenn es ein neueres Datenblatt gibt!
        $CSVListCmd->doCommand();*/

        $yellowPageCmd = new YellowPageCmd($this->oFileManager);

        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            // falls Aenderungen am Datenblatt, dann muessen zwingend gelbe Seiten ebenfalls aktualisiert werden.
            $yellowPageCmd->doCommand($epoch);
        }

        $allFiles = array();
        $iterator = $dirToArchive->listFiles('org\fktt\bstlist\util\ZipBundleFileFilter');
        // sammle jetzt alle Dateien, die fuer das Zip-Bundle in Frage kommen, in einem Array
        // enthaelt reine xml und nur alles was fuer deren direkte Anzeige in Frage kommt (CSS, XSL, DTD, Bilder),
        //der Rest wird spaeter automatisch generiert
        /** @var $file File */
        foreach ($iterator as $file)
        {
            if ($file->isFile() && $file->isReadable())
            {
                $allFiles[] = $file;
            }
        }
        // Sortiere alle Dateien so, dass die zuletzt geaenderte Datei ganz am Anfang des Array steht!
        \usort($allFiles, array("org\\fktt\\bstlist\\io\\File", "compareLastModified"));
        // Testausgabe. Achtung $file ist vom Typ SplFileInfo Objekt!
        /** @var $file SplFileInfo */
        /*foreach ($allFiles as $file)
        {
            echo $file->getPathname()." = > ".strftime("%a, %d. %b %Y %H:%M", $file->getMTime())."<br>";
        }*/

        $this->oTargetFile = new File("db/".self::$FILE_NAME);

        // zunaechst muessen ueberhaupt Dateien zum Archivieren vorhanden sein
        // Dann soll auf jedenfall ein Archiv erstellt werden, wenn die Zieldatei noch gar nicht existiert oder eben
        // falls neueste Datei aller Dateien spaeter modifiziert wurde als die Zieldatei
        /** @var $allFiles File[] */
        if (\count($allFiles) > 0 &&
                (!$this->getFile()->exists() || $this->getFile()->getMTime() < $allFiles[0]->getMTime())
        )
        {
            if ($this->getFile()->exists())
            {
                $this->getFile()->delete();
            }

            // parent full directory path of $dirToArchive, to generate the
            // local path in the archive
            $baseDir = new File();

            $xslHtml = $this->prepareXsl(); // array with all lang xsl and fpl.xsl files
            // Transformer factory command for XML to HTML conversion
            $transform = new XmlHtmlTransformCmd();

            $zip = new ZipArchive();
            $zip->open($this->getFile()->getPathname(), ZipArchive::CREATE);
            /** @var $node File */
            foreach ($allFiles as $node)
            {
                if ($node->isFile() && $node->isReadable())
                {
                    $localXmlPath = \str_replace($baseDir->getPathname()."/", "", $node->getPathname());
                    $zip->addFile($node->getPathname(), $localXmlPath);
                    // pruefe ob aktuelle Datei ein Datenblaett ist und erstelle je vorhandener Sprache und Fpl-View
                    // die dazuegoerige HTML Datei und lege diese ebenfalls im zip ab
                    if ($node->endsWith("xml"))
                    {
                        $localHtmlPath = \explode(".", $localXmlPath);
                        foreach ($xslHtml as $key => $xslFile)
                        {
                            $zip->addFromString($localHtmlPath[0].$key.".html", $transform->doCommand($xslFile, $node));
                        }
                    }
                }
            }
            $indexBuilder = new HtmlIndexPageBuilder();
            $zip->addFromString('db/index.html', $indexBuilder->doCommand());
            $listBuilder = new HtmlListPageBuilder($xslHtml);
            $listBuilder->setFileManager($this->oFileManager);
            foreach (FileManagerImpl::$EPOCHS as $epoch)
            {
                $listBuilder->setEpoch($epoch);
                $zip->addFromString('db/list-'.$epoch.'.html', $listBuilder->doCommand());
            }
            $zip->close();
            $this->getFile()->changeFileRights(0666);
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

    protected function prepareXsl()
    {
        $ret = array();
        $f = new File("db");
        $it = new GlobIterator($f->getPathname()."/bahnhof*.xsl");
        $it->setInfoClass('org\fktt\bstlist\io\File');
        /** @var $file File */
        foreach ($it as $file)
        {
            $n = $file->getBasename(".xsl");
            $a = \explode("_", $n);
            if (!isset($a[1])) // DE
            {
                $ret[""] = $file;
            }
            else if ($a[1] != 'tpl') // for all other than template file
            {
                $ret["_".\strtolower($a[1])] = $file;
            }
        }
        $ret["_fpl"] = new File($f->getPathname()."/fpl.xsl");
        return $ret;
    }
}
