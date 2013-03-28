<?php

abstract class AbstractFileFilter extends RecursiveFilterIterator
{
    /**
     * @return bool
     */
    public function accept()
    {
        // a directory is always accepted
        if (is_dir($this->getCurrent()))
        {
            return true;
        }
        else if (is_file($this->getCurrent()))
        {
            return in_array($this->getFileExtension($this->getCurrent()->getFilename()), $this->getFileFilter(), true);
        }
        else
        {
            return false;
        }
    }

    /**
     * @return SplFileInfo
     */
    protected final function getCurrent()
    {
        return $this->current();
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getFileExtension($fileName)
    {
        $ext = explode(".", $fileName);
        $l = count($ext);
        return strtolower($ext[$l-1]);
        //return strtolower(substr($fileName, strrpos($fileName, '.') + 1))
    }

    /**
     * @abstract
     * @return array string
     */
    abstract protected function getFileFilter();
}
