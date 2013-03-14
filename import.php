<?php
defined('is_running') or die('Not an entry point...');

/* Datei definiert die 'import' Funktion, die notwendig ist für das
 * gesamte Addon, da mittels dieser Funktion alle notwendigen Klassen
 * importiert werden, um aufgerufen werden zu können.
 */ 
if (!function_exists('import'))
{
    function import($className)
    {
        //Directories added here must be
        //relative to the script going to use this file.
        //New entries can be added to this list
        $directories = array(
          '',
          'classes/'
        );

        //Add your file naming formats here
        $fileNameFormats = array(
          '%s.php',
          '%s.class.php',
          'class.%s.php',
          '%s.inc.php',
          '%s.class.inc'
        );

        // this is to take care of the PEAR style of naming classes
        $path = str_ireplace('_', '/', $className);
        if (strrpos($className, "*")!== false)
        {
            $bd = dirname(__FILE__)."/";
            $su = ".php";
            foreach (glob($bd.$path.$su) as $directory)
            {
                $directory = str_replace($bd, "", $directory);
                $directory = str_replace($su, "", $directory);
                import($directory);
            }
            return;
        }
        else
        {
            if (include_once($path.".php"))
            {
                return;
            }
        }

        foreach ($directories as $directory)
        {
            foreach ($fileNameFormats as $fileNameFormat)
            {
                $path = $directory.sprintf($fileNameFormat, $className);
                if (file_exists($path) || is_file($path))
                {
                    require_once($path);
                    return;
                }
            }
        }
    }
} // end-if
/*
 * Dies ist der Startpunkt
 * Die Hauptklasse wird importiert. 
 * Der Aufruf erfolgt über gpEasy (Anpassung der Startklasse in Addon.ini)
 */

import('de_brb_hvl_wur_stumml_Main');
import('gadgets_*');

?>
