<?php

import('de_brb_hvl_wur_stumml_util_logging_StdoutLogger');

class CheckOnEditorVersionCmd
{
    private static $log;

    public function __construct()
    {
        self::$log = new StdoutLogger(get_class($this));
    }
    
    public function doCommand()
    {
        $bp = getcwd();
        self::$log->debug("Cwd: ".$bp);
        // auf das zu verweisende Archiv
        $file = "rgzm.jar";

        // Basisverzeichnis der aktuellen Version; enthaelt den Symlink
        $current = Settings::uploadBaseDir().DIRECTORY_SEPARATOR."rgzm".DIRECTORY_SEPARATOR."current";

        // Verzeichnis in dem nach Versionsordnern gesucht werden soll        
        $path = Settings::uploadBaseDir().DIRECTORY_SEPARATOR."rgzm".DIRECTORY_SEPARATOR;
        
        $allDirs = glob($path."v*", GLOB_ONLYDIR); // wird gleichzeitig sortiert!
        if (!file_exists($current.DIRECTORY_SEPARATOR.$file) && count($allDirs) > 0)
        {
            // getestet und fuer gut befunden
            chdir($current);
            symlink($allDirs[count($allDirs)-2].DIRECTORY_SEPARATOR.$file, $file);
        }
        elseif (file_exists($current.DIRECTORY_SEPARATOR.$file) && count($allDirs) > 0)
        {
            $newCurrent = $allDirs[count($allDirs)-1].DIRECTORY_SEPARATOR.$file;
            if (filemtime($current.DIRECTORY_SEPARATOR.$file) < 
                filemtime($newCurrent))
            {
                self::$log->debug("Link muss erneuert werden");
                chdir($current);
                unlink($file);
                symlink($newCurrent, $file);
            }
            else
            {
                self::$log->debug("Link zeigt auf aktuellste Version!");
            }
        }
        elseif (file_exists($current.DIRECTORY_SEPARATOR.$file) && count($allDirs) == 0)
        {
            // getestet und fuer gut befunden
            // link wird entfernt
            chdir($current);
            unlink($file);
        }
        chdir($bp);
        self::$log->debug("Cwd: ".getcwd());
        return true;
    }
}
?>
