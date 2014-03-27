<?php
namespace org\fktt\bstlist\beans\tableList\htmlPage;

\import('de_brb_hvl_wur_stumml_io_TemplateFile');

use org\fktt\bstlist\io\TemplateFile;

abstract class AbstractHtmlPageBuilder extends TemplateFile
{
    private $oContents = null;
    private static $BASE_ACTIONS = array("DATE" => '\strftime("%d. %B %Y", time())', "GP_VERSION" => 'gpversion',
                "BST_VERSION" => '$this->getAddonVersion()');

    public function __construct()
    {
        parent::__construct($this->getTemplateFileName());
    }

    public final function doCommand()
    {
        $this->doCommandBefore();
        return $this->replaceValues($this->getFileContents(), \array_merge(self::$BASE_ACTIONS, $this->getActions()));
    }

    protected abstract function doCommandBefore();

    protected abstract function getTemplateFileName();

    protected abstract function getActions();

    protected final function getAddonVersion()
    {
        $array = \parse_ini_file($this->getAddonIni());
        return $array['Addon_Version'];
    }

    protected final function getFileContents()
    {
        if (!$this->exists() || !$this->isFile() || !$this->isReadable())
        {
            return "";
        }
        if ($this->oContents == null)
        {
            \ob_start();
            require_once($this->getPathname());
            $this->oContents = \ob_get_contents();
            \ob_end_clean();
        }
        return $this->oContents;
    }

    protected final function replaceValues($string, $replaceArray)
    {
        if (!\is_array($replaceArray) || (\strlen($string) == 0))
        {
            return $string;
        }
        $result = $string;
        foreach ($replaceArray as $key => $value)
        {
            $result = \str_replace('{'.$key.'}', eval("return ".$value.";"), $result);
        }
        return $result;
    }
}