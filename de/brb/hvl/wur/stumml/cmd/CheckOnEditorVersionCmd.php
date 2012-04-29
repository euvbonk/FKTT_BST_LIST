<?php

import('de_brb_hvl_wur_stumml_util_logging_StdoutLogger');
import('de_brb_hvl_wur_stumml_Settings');

class CheckOnEditorVersionCmd
{
    private static $log;
    private static $oFile = "rgzm.jar";         // auf das zu verweisende Archiv
    private static $DS = DIRECTORY_SEPARATOR;
    private $oCurrent;
    private $oSearchPath;
    private $oAllDirs;

    public function __construct()
    {
        self::$log = new StdoutLogger(get_class($this));
        // Basisverzeichnis der aktuellen Version; enthaelt den Symlink
        $this->oCurrent = Settings::uploadBaseDir().self::$DS."rgzm".self::$DS."current";

        // Verzeichnis in dem nach Versionsordnern gesucht werden soll        
        $this->oSearchPath = Settings::uploadBaseDir().self::$DS."rgzm".self::$DS;
        
        // einlesen der gewuenschten Verzeichnisse, wird gleichzeitig sortiert!
        $this->oAllDirs = glob($this->oSearchPath."v*", GLOB_ONLYDIR);
    }
    
    public function doCommand()
    {
        $bp = getcwd();
        self::$log->debug("Cwd: ".$bp);
        // current file
        $cf = $this->oCurrent.self::$DS.self::$oFile;
        // new current file
        $ncf = $this->oAllDirs[count($this->oAllDirs)-1].self::$DS.self::$oFile;         
        if (!file_exists($cf) && count($this->oAllDirs) > 0)
        {
            // getestet und fuer gut befunden
            chdir($this->oCurrent);
            symlink($ncf, self::$oFile);
        }
        elseif (file_exists($cf) && count($this->oAllDirs) > 0)
        {
            if (filemtime($cf) < filemtime($ncf))
            {
                self::$log->debug("Link muss erneuert werden");
                chdir($this->oCurrent);
                unlink(self::$oFile);
                symlink($ncf, self::$oFile);
            }
            else
            {
                self::$log->debug("Link zeigt auf aktuellste Version!");
            }
        }
        elseif (file_exists($cf) && count($this->oAllDirs) == 0)
        {
            // getestet und fuer gut befunden
            // link wird entfernt
            chdir($this->oCurrent);
            unlink(self::$oFile);
        }
        chdir($bp);
        self::$log->debug("Cwd: ".getcwd());
        return true;
    }
}
?>
