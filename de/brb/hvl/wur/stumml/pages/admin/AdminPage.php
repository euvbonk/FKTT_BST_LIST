<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');
import('de_brb_hvl_wur_stumml_pages_admin_AdminPageSettings');

import('de_brb_hvl_wur_stumml_cmd_BackupZipBundleCmd');

import('de_brb_hvl_wur_stumml_util_QI');

final class AdminPage extends Frame implements FrameForm
{
    private $oZipListEntries = "";

    public function __construct()
    {
        parent::__construct(AdminPageSettings::getInstance()->getTemplateFile());
        /** @var $b BackupZipBundleCmd */
        $b = new BackupZipBundleCmd();
        $cmd = QI::getCommand();
        switch ($cmd)
        {
            case 'export' : $b->doCommand();
                            break;
        }
        $iterator = new GlobIterator($b->getFile()->getParentFile()->getPathname()."/*.zip");
        $this->oZipListEntries = "";
        if ($iterator->count() == 0)
        {
            $this->oZipListEntries .= "<li>--</li>";
        }
        /** @var $file File */
        foreach ($iterator as $file)
        {
            $this->oZipListEntries .= "<li>".AdminPageSettings::getDownloadLinkForFile($file->getPathname(),
                        $file->getBasename())."</li>";
        }
        return $this;
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

    /**
     * @see abstract class Frame
     */    
    public function getLastChangeTimestamp()
    {
        return AdminPageSettings::getInstance()->lastAddonChange();
    }
}
