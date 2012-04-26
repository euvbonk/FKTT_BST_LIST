<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_BasicDirectory');

import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageSpreadsheetGenerator');
import('de_brb_hvl_wur_stumml_beans_yellowPage_FkttYellowPage');

import('de_brb_hvl_wur_stumml_util_ZipTest');

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
        $this->doRun();
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    public function getLastChangeTimestamp()
    {
    }

    protected function doRun()
    {
        /*$f = OdsFile::openFile(Settings::uploadDir()."/fktt-yellow-page.ots");
        $f->editCell("Tabelle1", 2, 1, "TEST");
        $f->saveFileTo(Settings::uploadDir()."/test.ods");
        $f->closeFile();*/
        
        //$this->doBuildYellowPages();
    }

    protected function doBuildZipBundle()
    {
        /*try
        {
            new ZipTest();
        }
        catch (Exception $e)
        {
            print $e->getMessage();
            //print "<pre>".print_r($e, true)."</pre>";
        }*/
    }

    protected function doBuildYellowPages()
    {
        $page = new FkttYellowPage();
        $page->setDatasheetFileList(BasicDirectory::scanDirectories(Settings::uploadDir(), array("xml")));
        $page->generate();

        if (file_exists(Settings::uploadDir()."/test.ods"))
        {
            unlink(Settings::uploadDir()."/test.ods");
        }

        $calc = new YellowPageSpreadsheetGenerator();
        $calc->openDocumentFromFile(Settings::uploadDir()."/yellow-page.ots");
        //print "<pre>".print_r($page->getYellowPage(), true)."</pre>";
        //print "<br/>\n".$page->getAsOpenOfficeFormat()."\n<br/>";
        $calc->setYellowPage($page->getAsOpenOfficeFormat());
        $calc->generate();
        //$calc->setTextAtCellPositionByIndex("Foo", 3, 2);
        $calc->saveDocumentToFile(Settings::uploadDir()."/test.ods");
        $calc->closeDocument();
    }
}
?>
