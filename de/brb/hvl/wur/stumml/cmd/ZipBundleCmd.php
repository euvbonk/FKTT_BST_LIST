<?php
namespace org\fktt\bstlist\cmd;

\import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');
\import('de_brb_hvl_wur_stumml_beans_tableList_htmlPage_HtmlIndexPageBuilder');
\import('de_brb_hvl_wur_stumml_beans_tableList_htmlPage_HtmlListPageBuilder');

//\import('de_brb_hvl_wur_stumml_cmd_CSVListCmd');
\import('de_brb_hvl_wur_stumml_cmd_XmlHtmlTransformCmd');
\import('de_brb_hvl_wur_stumml_cmd_YellowPageCmd');
\import('de_brb_hvl_wur_stumml_io_File');
\import('de_brb_hvl_wur_stumml_util_ZipBundleFileFilter');

use Exception;
use SplFileInfo;
use ZipArchive;
use org\fktt\bstlist\beans\datasheet\FileManager;
use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use org\fktt\bstlist\beans\tableList\htmlPage\HtmlIndexPageBuilder;
use org\fktt\bstlist\beans\tableList\htmlPage\HtmlListPageBuilder;
use org\fktt\bstlist\io\File;

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
        // Zwei Transformer Commands
        $transformNormal = new XmlHtmlTransformCmd(new File($dirToArchive->getPathname()."/bahnhof.xsl"));
        $transformFpl = new XmlHtmlTransformCmd(new File($dirToArchive->getPathname()."/fpl.xsl"));

        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            // falls Aenderungen am Datenblatt, dann muessen zwingend gelbe Seiten ebenfalls aktualisiert werden.
            $yellowPageCmd->doCommand($epoch);
            // pruefe jetzt fuer alle Datenblaetter, ob die entsprechenden HTML Dateien aktuell sind und aktualisiere
            // sie falls notwendig.
            /** @var $file File */
            foreach ($this->oFileManager->getFilesFromEpochWithOrder($epoch) as $file)
            {
                $transformNormal->doCommand($file);
                $transformFpl->doCommand($file, new File(\str_replace(".xml", "_fpl.html", $file->getPathname())));
            }
        }

        $allFiles = array();
        $iterator = $dirToArchive->listFiles('org\fktt\bstlist\util\ZipBundleFileFilter');
        // sammle jetzt alle Dateien, die fuer das Zip-Bundle in Frage kommen, in einem Array
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

            $zip = new ZipArchive();
            $zip->open($this->getFile()->getPathname(), ZipArchive::CREATE);
            /** @var $node File */
            foreach ($allFiles as $node)
            {
                if ($node->isFile() && $node->isReadable())
                {
                    $zip->addFile($node->getPathname(),
                        \str_replace($baseDir->getPathname()."/", "", $node->getPathname()));
                }
            }
            $indexBuilder = new HtmlIndexPageBuilder();
            $zip->addFromString('db/index.html', $indexBuilder->doCommand());
            $listBuilder = new HtmlListPageBuilder();
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
}
