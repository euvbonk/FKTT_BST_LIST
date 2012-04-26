<?php
class Frame
{
    private $templateFileName;

    public function __construct($fileName)
    {
        $this->setTemplateFile($fileName);
    }    

    public function setTemplateFile($file)
    {
        $this->templateFileName = $file;
    }
    
    public function showContent()
    {
        $f = $this->templateFileName;
        if (file_exists($f) && is_file($f))
        {
            require_once($f);
        }
    }
}
?>
