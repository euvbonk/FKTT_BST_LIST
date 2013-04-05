<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');
import('de_brb_hvl_wur_stumml_pages_admin_AdminPageSettings');

import('de_brb_hvl_wur_stumml_cmd_BackupZipBundleCmd');
import('de_brb_hvl_wur_stumml_cmd_SendFileForDownloadCmd');

import('de_brb_hvl_wur_stumml_util_QI');

final class AdminPage extends Frame implements FrameForm
{
    public function __construct()
    {
        parent::__construct(AdminPageSettings::getInstance()->getTemplateFile());
        $cmd = QI::getCommand();
        switch ($cmd)
        {
            case 'export' : $b = new BackupZipBundleCmd();
                            if ($b->doCommand())
                            {
                                $c = new SendFileForDownloadCmd($b->getFile());
                                $c->doCommand();
                                exit;
                            }
                            break;
        }
        return $this;
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
