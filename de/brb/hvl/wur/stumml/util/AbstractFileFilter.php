<?php

abstract class AbstractFileFilter extends RecursiveFilterIterator
{
    public function accept()
    {
        // a directory is always accepted
        if (is_dir($this->current()))
        {
            return true;
        }
        else if (is_file($this->current()))
        {
            return in_array($this->getFileExtension($this->current()->getFileName()), $this->getFileFilter(), true);
        }
        else
        {
            return false;
        }
    }

    protected function getFileExtension($fileName)
    {
        $ext = explode(".", $fileName);
        $l = count($ext);
        return strtolower($ext[$l-1]);
        //return strtolower(substr($fileName, strrpos($fileName, '.') + 1))
    }    

    abstract protected function getFileFilter();
}
?>
