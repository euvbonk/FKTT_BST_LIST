<?php

import('de_brb_hvl_wur_stumml_Settings');

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
        $this->oDir = Settings::uploadBaseDir();
        $f = $this->oDir."/".self::$FILE_NAME;
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
        if (!is_writable($this->oDir))
        {
            $message = "Directory -".$this->oDir."- has no write ";
            $message .= "permission for php script!";
            throw new Exception($message);
        }

        // Alle bisherigen Dateien löschen
        $b = $this->oDir."/".self::$FILE_NAME."*.zip";
        foreach (glob($b) as $file)
        {
            unlink($file);
        }

        // parent full directory path of $dirToArchive, to generate the
        // local path in the archive
        $baseDir = substr($this->oDir, 0, strrpos($this->oDir, "/"));

        // follow also symbolic links
        $iterator =
                new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->oDir, FilesystemIterator::FOLLOW_SYMLINKS));

        $zip = new ZipArchive();
        $zip->open($this->oTargetFile->getPathname(), ZipArchive::CREATE);
        /** @var $node File */
        foreach ($iterator as $node)
        {
            //$node = new File($key);
            //if (is_file($node) && is_readable($node))
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
     * @return string
     */
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
