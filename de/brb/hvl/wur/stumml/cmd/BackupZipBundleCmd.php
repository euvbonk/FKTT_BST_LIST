<?php

import('de_brb_hvl_wur_stumml_Settings');

final class BackupZipBundleCmd
{
    private static $FILE_NAME = "datasheet_fulldata_backup_";
    private $oTargetFile;
    private $oDir;

    public function __construct()
    {
        $this->oDir = Settings::uploadBaseDir();
        $f = $this->oDir.DIRECTORY_SEPARATOR.self::$FILE_NAME;
        $this->oTargetFile = new File($f.strftime("%F-%H%M").".zip");
    }

    public function doCommand()
    {
        // Command immer ausführen!
        if (!is_writable($this->oDir))
        {
            $message = "Directory -".$this->oDir."- has no write ";
            $message .= "permission for php script!";
            throw new Exception($message);
        }

        // Alle bisherigen Dateien löschen
        $b = $this->oDir.DIRECTORY_SEPARATOR.self::$FILE_NAME."*.zip";
        foreach (glob($b) as $file)
        {
            unlink($file);
        }

        // parent full directory path of $dirToArchive, to generate the
        // local path in the archive
        $baseDir = substr($this->oDir, 0, strrpos($this->oDir, "/"));

        // follow also symbolic links
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->oDir, FilesystemIterator::FOLLOW_SYMLINKS));

        $zip = new ZipArchive();
        $zip->open($this->oTargetFile->getPathname(), ZipArchive::CREATE);
        foreach ($iterator as $key => $value)
        {
            $node = $key;
            if (is_file($node) && is_readable($node))
            {
                $node_new = str_replace($baseDir."/", "", $node);
                $zip->addFile($node, $node_new);
            }
        }
        $zip->close();
        $this->oTargetFile->changeFileRights(0666);
        return true;
    }

    public function getFileName()
    {
        if ($this->oTargetFile->exists())
        {
            return $this->oTargetFile->getPathname();
        }
        else
        {
            return "#";
        }
    }
}
