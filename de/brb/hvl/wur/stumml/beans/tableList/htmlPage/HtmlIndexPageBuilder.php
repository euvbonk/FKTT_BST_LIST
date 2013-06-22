<?php

import('de_brb_hvl_wur_stumml_beans_tableList_htmlPage_AbstractHtmlPageBuilder');

class HtmlIndexPageBuilder extends AbstractHtmlPageBuilder
{
    protected function getTemplateFileName()
    {
        return "template_index.php";
    }

    protected function getActions()
    {
        return array("EPOCHS" => '$this->getEpochString()');
    }

    public function doCommandBefore(){}

    protected function getEpochString()
    {
        $epochString = "";
        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            if (strlen($epochString) > 0)
            {
                $epochString .= "&nbsp;|&nbsp;";
            }
            $epochString .= '<a href="javascript:show(\''.$epoch.'\')">Epoche '.$epoch.'</a>';
        }
        return $epochString;
    }
}