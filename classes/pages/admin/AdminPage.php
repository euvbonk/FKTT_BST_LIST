<?php
namespace org\fktt\bstlist\pages\admin;

\import('cmd_BackupZipBundleCmd');
\import('io_GlobIterator');
\import('pages_Frame');
\import('pages_FrameForm');
\import('util_QI');

use org\fktt\bstlist\cmd\BackupZipBundleCmd;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\io\GlobIterator;
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\pages\FrameForm;
use org\fktt\bstlist\util\QI;

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
        $iterator = new GlobIterator($b->getFile()->getParentFile()->getPathname()."/*.zip");
        $iterator->setInfoClass('org\fktt\bstlist\io\File');
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
        return array('ZipList', 'FormActionUri');
    }

    public final function ZipList()
    {
        return $this->oZipListEntries;
    }

    /**
     * @see Interface FrameForm
     */
    public final function FormActionUri()
    {
        return QI::getPageUri();
    }
}