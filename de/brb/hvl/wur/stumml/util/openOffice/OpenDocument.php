<?php

import('de_brb_hvl_wur_stumml_io_File');

abstract class OpenDocument
{
    private $oDocumentContent = null;
    private $oArchiveFileName = null;

    /**
     * @return OpenDocument
     */
    public function __construct()
    {
        return $this;
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

    /**
     * @abstract
     * @return void
     * @throws Exception
     */
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
        $zip->addFromString('content.xml', $this->getDocument()->asXML());
        $zip->close();
        $file->changeFileRights(0666);
        ini_set('default_charset', $charset);
    }

    /**
     * @return void
     */
    public function debug()
    {
        print "\n<pre>";
        var_dump($this->getDocument()->asXML());
        print "</pre>\n";
    }

    /**
     * @param File $file
     * @return SimpleXMLElement
     */
    protected function loadDocument(File $file)
    {
        $this->oArchiveFileName = $file->getPathname();
        $zip = new ZipArchive();
        $zip->open($this->oArchiveFileName);
        $content = new SimpleXMLElement($zip->getFromIndex($zip->locateName('content.xml')));
        $zip->close();
        return $content;
    }

    /**
     * @param SimpleXMLElement $content
     */
    protected function setDocument(SimpleXMLElement $content)
    {
        $this->oDocumentContent = $content;
    }

    /**
     * @return SimpleXMLElement
     */
    protected function getDocument()
    {
        return $this->oDocumentContent;
    }

    /**
     * @return void
     */
    public function closeDocument()
    {
        $this->oDocumentContent = null;
        $this->oArchiveFileName = null;
    }
}
