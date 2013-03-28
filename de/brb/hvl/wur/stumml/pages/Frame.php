<?php
import('de_brb_hvl_wur_stumml_io_File');

abstract class Frame
{
    private $templateFileName;

    /**
     * @param File|null $file [optional]
     */
    public function __construct(File $file = null)
    {
        if ($file != null && $file instanceof File)
        {
            $this->setTemplateFile($file);
        }
    }

    /**
     * @param File $file
     */
    public function setTemplateFile(File $file)
    {
        $this->templateFileName = $file;
    }

    /**
     * @return File
     */
    protected function getTemplateFile()
    {
        return $this->templateFileName;
    }

    /**
     * Includes the template file
     */
    public function showContent()
    {
        $f = $this->getTemplateFile();
        if ($f != null && $f->exists() && $f->isFile())
        {
            require_once($f->getPathname());
        }
    }

    /**
     * @abstract
     * @return String
     */
    public abstract function getLastChangeTimestamp();
}
