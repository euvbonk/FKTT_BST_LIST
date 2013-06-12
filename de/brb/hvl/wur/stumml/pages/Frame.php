<?php

import('de_brb_hvl_wur_stumml_io_TemplateFile');

abstract class Frame
{
    private $templateFileName;

    /**
     * @param String|null $fileName [optional]
     */
    public function __construct($fileName = null)
    {
        if ($fileName != null && is_string($fileName) && strlen($fileName) > 0)
        {
            $this->setTemplateFile(new TemplateFile($fileName.".php"));
        }
    }

    /**
     * @param TemplateFile $file
     */
    public function setTemplateFile(TemplateFile $file)
    {
        $this->templateFileName = $file;
    }

    /**
     * @return TemplateFile
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

    public final function getLastChangeTimestamp()
    {
        print '13. Juni 2013 20:00:00';
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
