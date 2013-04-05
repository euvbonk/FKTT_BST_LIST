<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');

final class BackupZipBundleCmd
{
    private static $FILE_NAME = "datasheet_fulldata_backup_";
    private $oTargetFile;
    private $oDir;

    /**
     * @return BackupZipBundleCmd
     */
    public function __construct()
    {
        $this->oDir = new File(Settings::uploadBaseDir());
        $f = $this->oDir->getPathname()."/".self::$FILE_NAME;
        $this->oTargetFile = new File($f.strftime("%F-%H%M").".zip");
        return $this;
    }

    /**
     * @return bool true
     * @throws Exception if directory has no write permissions
     */
    public function doCommand()
    {
        // Command immer ausführen!
        if (!$this->oDir->isWritable())
        {
            $message = "Directory -".$this->oDir->getPathname()."- has no write ";
            $message .= "permission for php script!";
            throw new Exception($message);
        }

        // Alle bisherigen Dateien löschen
        $b = $this->oDir->getPathname()."/".self::$FILE_NAME."*.zip";
        foreach (glob($b) as $file)
        {
            unlink($file);
        }

        // parent full directory path of $dirToArchive, to generate the
        // local path in the archive
        $baseDir = substr($this->oDir->getPathname(), 0, strrpos($this->oDir->getPathname(), "/"));

        $iterator = $this->oDir->listFiles();

        $zip = new ZipArchive();
        $zip->open($this->oTargetFile->getPathname(), ZipArchive::CREATE);
        /** @var $node File */
        foreach ($iterator as $node)
        {
            if ($node->isFile() && $node->isReadable())
            {
                $node_new = str_replace($baseDir."/", "", $node->getPathname());
                $zip->addFile($node->getPathname(), $node_new);
            }
        }
        $zip->close();
        $this->oTargetFile->changeFileRights(0666);
        return true;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->oTargetFile;
    }
}
