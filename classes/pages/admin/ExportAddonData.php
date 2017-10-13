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

final class ExportAddonData extends Frame implements FrameForm
{
    private $backupZipBundleCmd;

    public function __construct()
    {
        parent::__construct();
        /** @var $b BackupZipBundleCmd */
        $this->backupZipBundleCmd = new BackupZipBundleCmd();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && QI::getCommand() == "export" && \sizeof($_POST) > 0)
        {
            $this->backupZipBundleCmd->doCommand();
        }
        return $this;
    }

    public final function showContent()
    {
        print "<h3>Save addon data directory to zip file:</h3>";
        print "<p>";
        print "The following archives are available and ";
        print "<span style=\"text-decoration:underline;font-weight:bold;font-style:italic;\">will be removed before the new zip is created!</span>:";
        print "<ul>";
        $iterator = new GlobIterator($this->backupZipBundleCmd->getFile()->getParentFile()->getPathname()."/*.zip");
        $iterator->setInfoClass('org\fktt\bstlist\io\File');
        if ($iterator->count() == 0)
        {
            print "<li>Nothing</li>";
        }
        /** @var $file File */
        foreach ($iterator as $file)
        {
            print "<li>".$file->toDownloadLink($file->getBasename())."</li>";
        }
        print "</ul>";
        print "<form action=\"".$this->FormActionUri()."\" method=\"post\">";
        print "<input type=\"hidden\" name=\"cmd\" value=\"export\" />";
        print "<input type=\"submit\" value=\"Start export\" />";
        print "</form>";
        print "</p>";
    }

    protected function getCallableMethods()
    {
        return array();
    }

    /**
     * @see Interface FrameForm
     */
    public final function FormActionUri()
    {
        return QI::getPageUri();
    }
}
