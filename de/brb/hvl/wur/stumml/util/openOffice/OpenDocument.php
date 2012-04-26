<?php

abstract class OpenDocument
{
    private $oDocumentContent = null;
    private $oArchiveFileName = null;

    public function __construct()
    {
    }

    public abstract function openDocumentFromFile($file);
    public abstract function setDocumentFileName($file);
    public abstract function getDocumentFileName();
    public abstract function saveDocument();

    public function saveDocumentToFile($file)
    {
        if ($this->oArchiveFileName == null) return;
    	$charset = ini_get('default_charset');
	    ini_set('default_charset', 'UTF-8');
        copy($this->oArchiveFileName, $file);
        $zip = new ZipArchive();
        $zip->open($file);
        $zip->deleteName('content.xml');
        $zip->addFromString('content.xml', $this->oDocumentContent->asXML());
        $zip->close();
    	ini_set('default_charset',$charset);
    }

    public function debug()
    {
        print "\n<pre>";
        var_dump($this->oDocumentContent->asXML());
        print "</pre>\n";
    }

    protected function loadDocument($file)
    {
        $this->oArchiveFileName = $file;
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
?>
