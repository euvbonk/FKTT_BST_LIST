<?php

import('de_brb_hvl_wur_stumml_io_File');

class TemplateFile extends File
{
    /**
     * Loads a template file from addon template directory
     *
     * @param string $fileName
     * @return TemplateFile
     */
    public function __construct($fileName)
    {
        parent::__construct(self::getAddonTemplateDirectory().$fileName);
        return $this;
    }
}
