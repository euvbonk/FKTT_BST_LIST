<?php
namespace org\fktt\bstlist\pages\moduleList;

\import('cmd_ListBuilderCmd');
\import('cmd_SendFileForDownloadCmd');
\import('pages_Frame');
\import('pages_FrameForm');
\import('util_QI');

use org\fktt\bstlist\cmd\ListBuilderCmd;
use org\fktt\bstlist\cmd\SendFileForDownloadCmd;
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\pages\FrameForm;
use org\fktt\bstlist\util\QI;

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
        return array('PostContent','FormActionUri');
    }

    public final function PostContent()
    {
        return $this->content;
    }

    /**
     * @see Interface FrameForm
     * @return String
     */
    public final function FormActionUri()
    {
        return QI::getPageUri();
    }
}