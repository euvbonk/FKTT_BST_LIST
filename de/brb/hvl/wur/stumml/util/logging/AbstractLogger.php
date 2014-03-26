<?php
namespace org\fktt\bstlist\util\logging;

import('de_brb_hvl_wur_stumml_util_QI');
use org\fktt\bstlist\util\QI;

abstract class AbstractLogger
{
    private static $oDateFormat = "Y-m-d G:i:s";

    private $oClassName;

    /**
     * @param string $className
     * @return AbstractLogger
     */
    public function __construct($className)
    {
        $this->oClassName = $className;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebugEnabled()
    {
        return QI::isGpeasyDebugEnabled();
    }

    /**
     * @param string $message
     */
    public function debug($message)
    {
        if ($this->isDebugEnabled())
        {
            $this->buildMessageString("DEBUG", $message);
        }
    }

    /**
     * @param string $level
     * @param string $message
     */
    protected function buildMessageString($level, $message)
    {
        $this->writeMessage(\date(self::$oDateFormat)." ".$level." ".$this->oClassName." - ".$message);
    }

    /**
     * @abstract
     * @param string $message
     * @return void
     */
    protected abstract function writeMessage($message);
}
