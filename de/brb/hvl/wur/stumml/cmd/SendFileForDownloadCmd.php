<?php

final class SendFileForDownloadCmd
{
    private $oFile;
    private $oFileName;

    public function __construct($file, $fileName = 'foo.csv')
    {
        $this->oFile = $file;
        if (is_file($this->oFile))
        {
            $this->oFileName = basename($file);
        }
        else
        {
            $this->oFileName = $fileName;
        }
    }

    public function doCommand()
    {
        if ((file_exists($this->oFile) && is_readable($this->oFile)) || strlen($this->oFile) > 0)
        {
            header('Content-Description: File Transfer');
            if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
            {
                header('Content-Type: application/force-download');
            }
            else
            {
                header('Content-Type: application/octet-stream');
            }

            if (headers_sent())
            {
                echo 'Some data has already been output to browser, can\'t send file!';
            }
            header('Content-Disposition: attachment; filename='.$this->oFileName);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            if (is_file($this->oFile))
            {
                header('Content-Length: '.filesize($this->oFile));
            }
            else
            {
                header('Content-Length: '.strlen($this->oFile));
            }
            ob_clean();
            flush();
            if (is_file($this->oFile))
            {
                readfile($this->oFile);
            }
            else
            {
                echo $this->oFile;
            }

            return true;
        }
        else
        {
            return false;
        }
    }
}
