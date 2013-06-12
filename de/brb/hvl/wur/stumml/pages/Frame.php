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

    public final function getLastChangeTimestamp()
    {
        print '12. Juni 2013 20:00:00';
        return;
    }

    /**
     * @abstract
     * @return array
     */
    protected abstract function getCallableMethods();

    /**
     * @param string $methodName
     * @param null   $args
     * @return bool
     */
    public final function printFunc($methodName, $args = null)
    {
        //print "Betroffene Klasse: ".get_called_class()."<br>";
        //$func_array = get_class_methods(get_called_class());
        //print "Funktionen: <pre>".print_r($func_array, true)."<pre>";
        if (in_array($methodName, $this->getCallableMethods()))
        {
            $rf = new ReflectionMethod(get_called_class(), $methodName);
            if ($rf->isPublic())
            {
                if ($args != null)
                {
                    print $rf->invoke($this, $args);
                }
                else
                {
                    print $rf->invoke($this);
                }
                return true;
            }
        }
        return false;
    }
}
