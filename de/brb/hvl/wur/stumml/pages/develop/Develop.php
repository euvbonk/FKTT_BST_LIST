<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_Settings');

import('de_brb_hvl_wur_stumml_util_ZipTest');
//import('de_brb_hvl_wur_stumml_util_ZipBundleFileFilter');
//import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');
//import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');

interface DevelopPageContent
{
    public function content();
}

class Develop extends Frame implements DevelopPageContent
{
    public function __construct()
    {
        parent::__construct(Settings::addonTemplateBaseDir()."/develop.php");
    }

    public function content()
    {
        ob_start();
        //$this->doRun();
        $this->doBuildZipBundle();
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    public function getLastChangeTimestamp()
    {
    }

    protected function doRun()
    {
        /*$fileManager = new FileManagerImpl();
        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            echo $epoch."<br/>";
            $files = $fileManager->getFilesFromEpochWithFilter($epoch);
            foreach ($files as $xmlFile)
            {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".dirname($xmlFile).DIRECTORY_SEPARATOR.basename($xmlFile, "xml")."<br/>";
                $htmlFile = dirname($xmlFile).DIRECTORY_SEPARATOR.basename($xmlFile, "xml")."html";
                if (!file_exists($htmlFile) || filemtime($htmlFile) < filemtime($xmlFile))
                {
                    echo "Datei muss angelegt werden!<br/>";
                }
            }
        }*/
    }

    protected function doBuildZipBundle()
    {
        try
        {
            new ZipTest();
        }
        catch (Exception $e)
        {
            print $e->getMessage();
            //print "<pre>".print_r($e, true)."</pre>";
        }
    }
}
?>
