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
        // TODO Klasse TemplateFile genau hier mit dem Dateinamen aufrufen
        $this->templateFileName = $file;
    }

    /**
     * @return File
     */
    // TODO Return TemplateFile Objekt
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
    //TODO Funktion als final deklarieren und Datum zurueckgeben
    public abstract function getLastChangeTimestamp();

    // TODO abstrakte Funktion definieren, die die zu implementierenden Klassen
    //      zwingen ein Array von Funktionsnamen zu definieren, die dann im Template
    //      ueber eine spezielle Ausgabe Funktion aufgerufen werden koennen
    /**
     * Beispielaufruf in Template Datei: <?php $this->callF('FunktionName'); ?>
     * public function callF($funcName)
     * {
     *      get_called_class if class has method $funcName print $this->$funcName;
     *
     * }
     */
}
