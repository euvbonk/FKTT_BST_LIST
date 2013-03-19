<?php

import('de_brb_hvl_wur_stumml_io_File');

abstract class OpenDocument
{
    private $oDocumentContent = null;
    private $oArchiveFileName = null;

    public function __construct()
    {
    }

    /**
     * @abstract
     * @param File $file
     * @return void
     */
    public abstract function openDocumentFromFile(File $file);

    /**
     * @abstract
     * @param File $file
     * @return void
     */
    public abstract function setDocumentFile(File $file);

    /**
     * @abstract
     * @return File
     */
    public abstract function getDocumentFile();

    public abstract function saveDocument();

    /**
     * @param File $file
     * @return void
     */
    public function saveDocumentToFile(File $file)
    {
        if ($this->oArchiveFileName == null)
        {
            return;
        }
        $charset = ini_get('default_charset');
        ini_set('default_charset', 'UTF-8');
        copy($this->oArchiveFileName, $file->getPathname());
        $zip = new ZipArchive();
        $zip->open($file->getPathname());
        $zip->deleteName('content.xml');
        $zip->addFromString('content.xml', $this->oDocumentContent->asXML());
        $zip->close();
        $file->changeFileRights(0666);
        ini_set('default_charset', $charset);
    }

    public function debug()
    {
        print "\n<pre>";
        var_dump($this->oDocumentContent->asXML());
        print "</pre>\n";
    }

    protected function loadDocument(File $file)
    {
        $this->oArchiveFileName = $file->getPathname();
        $zip = new ZipArchive();
        $zip->open($this->oArchiveFileName);
        $content = new SimpleXMLElement($zip->getFromIndex($zip->locateName('content.xml')));
        $zip->close();
        return $content;
    }

    protected function setDocument($content)
    {
        $this->oDocumentContent = $content;
    }

    protected function getDocument()
    {
        return $this->oDocumentContent;
    }

    public function closeDocument()
    {
        $this->oDocumentContent = null;
        $this->oArchiveFileName = null;
    }
}
