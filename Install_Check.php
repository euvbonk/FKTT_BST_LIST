<?php
\defined('is_running') or die('Not an entry point...');

require_once('import.php');

\import('io_File');
use org\fktt\bstlist\io\File;

/*
 * Install_Check() can be used to check the destination server for required features
 * 		This can be helpful for addons that require PEAR support or extra PHP Extensions
 * 		Install_Check() is called from step1 of the install/upgrade process
 */
function Install_Check()
{	
	/*if (check_for_feature)
    {
		echo '<p style="color:red">Cannot install this addon, missing feature.</p>';
		return false;
	}*/

    $dir = new File();
    if ($dir->isLink()) // Sollte wenn moeglich vermieden werden!
    {
        $dir = new File(realpath($dir->getPathname()));
    }
    if (!\gpFiles::CheckDir($dir->getPathname())) // !!!Funktion legt bei nicht vorhanden sein des Ordners diesen an!!!
    {
        echo '<p style="color:red">Addon kann nicht installiert werden, '.
             'der Ordner "'.$dir->getName().'" existiert '.
             'nicht, muss aber zwingend vorhanden sein!</p>';
        return false;
    }
    if (!$dir->isWritable()) // Bedingung kann aufgrund vorhergehendem if nicht erreicht werden
    {
        echo '<p style="color:red">Addon kann nicht installiert werden, '.
             'der Ordner "'.$dir->getName().'" besitzt '.
             'keine Schreibrechte, die aber zwingend vorhanden sein m&uuml;ssen!</p>';
        return false;
    }
    else
    {
        /* Eventuell den Ordner anlegen? */
    }
    return true;
}
