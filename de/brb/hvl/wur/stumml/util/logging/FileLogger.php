<?php

import('de_brb_hvl_wur_stumml_io_File');

/**
 * Class FileLogger
 * Writes a message to the given logfile
 */
class FileLogger extends \File
{
    private static $DATE_FORMAT = "Y-m-d H:i:s";
    private $oHandle;

    /**
     * @param string $fileName
     * @return FileLogger
     */
    public function __construct($fileName)
    {
        parent::__construct($fileName);
        return $this;
    }

    /**
     * @return resource|false
     */
    public function open()
    {
        $this->oHandle = fopen($this->getPathname(), "ab");
        return $this->oHandle;
    }

    /**
     * @param string $message
     * @return int|false
     */
    public function write($message)
    {
        $str = "[".date(self::$DATE_FORMAT)."] ".$message."\r\n";
        return fwrite($this->oHandle, $str, strlen($str));
    }

    /**
     * @return bool
     */
    public function close()
    {
        return fclose($this->oHandle);
    }
}
