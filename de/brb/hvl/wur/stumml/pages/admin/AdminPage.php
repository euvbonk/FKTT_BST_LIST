<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');

import('de_brb_hvl_wur_stumml_cmd_BackupZipBundleCmd');
import('de_brb_hvl_wur_stumml_io_GlobIterator');

import('de_brb_hvl_wur_stumml_util_QI');

final class AdminPage extends Frame implements FrameForm
{
    private $oZipListEntries = "";

    public function __construct()
    {
        parent::__construct('admin');
        /** @var $b BackupZipBundleCmd */
        $b = new BackupZipBundleCmd();
        $cmd = QI::getCommand();
        switch ($cmd)
        {
            case 'export' :
                $b->doCommand();
                break;
        }

        $this->oZipListEntries = "";
        $iterator = new MyGlobIterator($b->getFile()->getParentFile()->getPathname()."/*.zip");
        $iterator->setInfoClass('File');
        if ($iterator->count() == 0)
        {
            $this->oZipListEntries .= "<li>Keine</li>";
        }
        /** @var $file File */
        foreach ($iterator as $file)
        {
            $this->oZipListEntries .= "<li>".$file->toDownloadLink($file->getBasename())."</li>";
        }

        return $this;
    }

    protected function getCallableMethods()
    {
        return array('getZipList', 'getFormActionUri');
    }

    public function getZipList()
    {
        return $this->oZipListEntries;
    }

    /**
     * @see Interface FrameForm
     */
    public function getFormActionUri()
    {
        return QI::getPageUri();
    }
}
