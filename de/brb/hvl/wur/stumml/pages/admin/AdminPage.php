<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');
import('de_brb_hvl_wur_stumml_pages_admin_AdminPageSettings');

import('de_brb_hvl_wur_stumml_cmd_BackupZipBundleCmd');
import('de_brb_hvl_wur_stumml_cmd_SendFileForDownloadCmd');

class AdminPage extends Frame implements FrameForm
{
    public function __construct()
    {
        parent::__construct(AdminPageSettings::getInstance()->getTemplateFile());
        $cmd = common::GetCommand();
        switch ($cmd)
        {
            case 'export' : $b = new BackupZipBundleCmd();
                            if ($b->doCommand())
                            {
                                $c = new SendFileForDownloadCmd($b->getFileName());
                                $c->doCommand();
                                exit;
                            }
                            break;
        }
    }

    /**
     * @see Interface FrameForm
     */
    public function getFormActionUri()
    {
        return common::GetUrl(common::WhichPage());
    }

    public function getLastChangeTimestamp()
    {
        return AdminPageSettings::getInstance()->lastAddonChange();
    }

    public function content()
    {
        /* Not used regularly */
        return "";
    }
}
?>
