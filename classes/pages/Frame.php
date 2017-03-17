<?php
namespace org\fktt\bstlist\pages;

\import('io_TemplateFile');

use ReflectionException;
use ReflectionMethod;
use org\fktt\bstlist\io\TemplateFile;

abstract class Frame
{
    private $templateFileName;

    /**
     * @param String|null $fileName [optional]
     */
    public function __construct($fileName = null)
    {
        if ($fileName != null && \is_string($fileName) && \strlen($fileName) > 0)
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

    public final function LastChange()
    {
        return '18. Februar 2017 12:00:00';
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
    public final function printValue($methodName, $args = null)
    {
        if (\in_array($methodName, \array_merge(array('LastChange'), $this->getCallableMethods())))
        {
            try
            {
                $rf = new ReflectionMethod(\get_called_class(), $methodName);
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
            catch (ReflectionException $e)
            {
                // do nothing
                return false;
            }
        }
        return false;
    }
}
