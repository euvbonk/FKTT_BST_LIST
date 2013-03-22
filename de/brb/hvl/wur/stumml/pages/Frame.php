<?php
import('de_brb_hvl_wur_stumml_io_File');

abstract class Frame
{
    private $templateFileName;

    public function __construct(File $file = null)
    {
        if ($file != null && $file instanceof File)
        {
            $this->setTemplateFile($file);
        }
    }    

    public function setTemplateFile(File $file)
    {
        $this->templateFileName = $file;
    }
    
    public function showContent()
    {
        $f = $this->templateFileName;
        if ($f != null && $f->exists() && $f->isFile())
        {
            require_once($f->getPathname());
        }
    }

    public abstract function getLastChangeTimestamp();
}
