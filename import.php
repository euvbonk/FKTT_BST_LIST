<?php
\defined('is_running') or die('Not an entry point...');

if (\version_compare(PHP_VERSION, '5.4.0', '<')) die('Addon requires at least PHP Version 5.4.0+');

// extend allowed upload file extensions used in this plugin
// This does not work as expected!
//global $upload_extensions_allow;
//$upload_extensions_allow = array_merge($upload_extensions_allow, array('jar','jnlp','dtd','css','xsl'));

/* Datei definiert die 'import' Funktion, die notwendig ist für das
 * gesamte Addon, da mittels dieser Funktion alle notwendigen Klassen
 * importiert werden, um aufgerufen werden zu können.
 */ 
if (!\function_exists('import'))
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
        $path = \str_ireplace('_', '/', $className);
        if (\strrpos($className, "*") !== false)
        {
            $bd = \dirname(__FILE__)."/";
            $su = ".php";
            foreach (\glob($bd.$path.$su) as $directory)
            {
                $directory = \str_replace($bd, "", $directory);
                $directory = \str_replace($su, "", $directory);
                \import($directory);
            }
            return;
        }
        else
        {
            if (@include_once($path.".php"))
            {
                return;
            }
            else
            {
                $path .= ".php";
                foreach ($directories as $directory)
                {
                    $ipath = \dirname(__FILE__)."/".$directory.$path;
                    if (\file_exists($ipath) || \is_file($ipath))
                    {
                        require_once($ipath);
                        return;
                    }
                }
            }
        }

        foreach ($directories as $directory)
        {
            foreach ($fileNameFormats as $fileNameFormat)
            {
                $path = $directory.\sprintf($fileNameFormat, $className);
                if (\file_exists($path) || \is_file($path))
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
\import('io_File');
use org\fktt\bstlist\io\File;
File::setPaths(\getenv('HTTP_HOST'), \getenv('DOCUMENT_ROOT'), \getenv('SCRIPT_NAME'), __FILE__);

\import('Main');
\import('gadgets_*');
