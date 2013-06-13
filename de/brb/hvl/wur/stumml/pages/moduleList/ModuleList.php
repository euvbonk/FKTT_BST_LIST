<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');

import('de_brb_hvl_wur_stumml_cmd_ListBuilderCmd');
import('de_brb_hvl_wur_stumml_cmd_SendFileForDownloadCmd');

import('de_brb_hvl_wur_stumml_util_QI');

class ModuleList extends Frame implements FrameForm
{
    private $content;
    
    public function __construct()
    {
        parent::__construct('module_list');
        $this->content = (!empty($_POST['content'])) ? $_POST['content'] : "";
        
        if (!empty($this->content))
        {
            $cmd = QI::getCommand();
            switch ($cmd)
            {
                case 'create' : $b = new ListBuilderCmd($this->content);
                                $b->doCommand();
                                $s = new SendFileForDownloadCmd($b->getCsvString());
                                if ($s->doCommand())
                                {
                                    exit;
                                }
                                break;
            }
        }
        return $this;
    }

    protected function getCallableMethods()
    {
        return array('getPostContent','getFormActionUri');
    }

    public function getPostContent()
    {
        return $this->content;
    }

    /**
     * @see Interface FrameForm
     * @return String
     */
    public function getFormActionUri()
    {
        return QI::getPageUri();
    }
}