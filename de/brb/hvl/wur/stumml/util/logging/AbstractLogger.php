<?php
import('de_brb_hvl_wur_stumml_util_QI');

abstract class AbstractLogger
{
    private static $oDateFormat = "Y-m-d G:i:s";

    private $oClassName;

    public function __construct($className)
    {
        $this->oClassName = $className;
        
    }

    public function isDebugEnabled()
    {
        return QI::isGpeasyDebugEnabled();
    }

    public function debug($message)
    {
        if ($this->isDebugEnabled())
        {
            $this->buildMessageString("DEBUG", $message);
        }
    }

    protected function buildMessageString($level, $message)
    {
        $this->writeMessage(date(self::$oDateFormat)." ".$level." ".$this->oClassName." - ".$message);
    }

    protected abstract function writeMessage($message);
}
?>
