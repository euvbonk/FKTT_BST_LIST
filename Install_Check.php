<?php
defined('is_running') or die('Not an entry point...');

require_once('import.php');

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

    import('de_brb_hvl_wur_stumml_Settings');
    $dir = Settings::getInstance()->uploadBaseDir();
    if (is_link($dir))
    {
        $dir = realpath($dir);
    }
    if (!file_exists($dir))
    {
        echo '<p style="color:red">Addon kann nicht installiert werden, '.
             'der Ordner "'.substr($dir, strrpos($dir, '/')+1).'" existiert '.
             'nicht muss aber zwingend vorhanden sein!</p>';
        return false;
    }
    else
    {
        /* Eventuell den Ordner anlegen? */
    }
    return true;
}
