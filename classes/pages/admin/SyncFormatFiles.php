<?php
namespace org\fktt\bstlist\pages\admin;

\import('beans_datasheet_FileManagerImpl');
\import('io_GlobIterator');
\import('pages_Frame');
\import('pages_FrameForm');
\import('util_QI');

use ZipArchive;
use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\io\GlobIterator;
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\pages\FrameForm;
use org\fktt\bstlist\util\QI;

final class SyncFormatFiles extends Frame implements FrameForm
{
    private static $availCommands = array('sync');

    private $oListEntries = "";
    private $oMessage = "&nbsp;";

    public function __construct()
    {
        parent::__construct('sync_format_files');

        $this->doCommand(QI::getCommand(), $_POST);
        $this->buildList();

        return $this;
    }

    protected function doCommand($cmd, $DATA = array())
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && \in_array($cmd, self::$availCommands) && \sizeof($DATA) > 2)
        {
            $files = array();
            foreach ($DATA as $key => $value)
            {
                if ($this->startsWith($key, "bahnhof"))
                {
                    $files[] = new File($value);
                }
            }
            $fmg = new FileManagerImpl();
            $msg = array();
            /** @var $file File */
            foreach ($files as $file)
            {
                $cc = $fmg->getAllCountryCodes();
                $success = 0;
                /** @var $countrySubFolder File */
                foreach ($cc as $countrySubFolder)
                {
                    $targetFile = new File("db/".$countrySubFolder."/".$file->getBasename());
                    if (\copy($file->getPathname(), $targetFile->getPathname()))
                    {
                        $success++;
                    }
                }
                $msg[$file->getBasename()] = array($success, sizeof($cc));
            }
            $this->buildMessage($msg);
            // update zip format_files.zip
            $zipF = new File("db/format_files.zip");
            if ($zipF->exists())
            {
                // Provide just one back up file
                if (\file_exists($zipF->getPathname().".old"))
                {
                    \unlink($zipF->getPathname().".old");
                }
                \copy($zipF->getPathname(), $zipF->getPathname().".old");
            }
            $zip = new ZipArchive();
            $zip->open($zipF->getPathname(), ZipArchive::CHECKCONS);
            /** @var $file File */
            foreach ($files as $file)
            {
                $zip->deleteName($file->getBasename());
                $zip->addFile($file->getPathname(), $file->getBasename());
            }
            $zip->close();
            $zipF->changeFileRights(0666);
            $this->oMessage .= "Archiv \"{$zipF->getBasename()}\" erfolgreich aktualisiert.";
        }
    }

    protected function buildMessage($msgs)
    {
        if (sizeof($msgs) > 0)
        {
            $this->oMessage = "";
            foreach ($msgs as $key => $value)
            {
                $this->oMessage .= "Synchronisation von \"{$key}\" f&uuml;r {$value[0]}/{$value[1]} erfolgreich.<br>";
            }
        }
    }

    protected function buildList()
    {
        $ret = array();
        $f = new File("db");
        $it = new GlobIterator($f->getPathname()."/bahnhof*.*");
        $it->setInfoClass('org\fktt\bstlist\io\File');
        /** @var $file File */
        foreach ($it as $file)
        {
            $a = \explode("_", $file->getBasename());
            if (!isset($a[1]) || $a[1] != 'tpl.xsl')
            {
                $ret[$file->getBasename()] = $file;
            }
        }

        foreach ($ret as $key => $value)
        {
            $this->oListEntries .= "<li><input type=\"checkbox\" id=\"{$key}\" name=\"{$key}\" value=\"{$value}\">";
            $this->oListEntries .= "<label for=\"{$key}\">{$key}</label></li>";
        }
    }

    protected function startsWith($haystack, $needle)
    {
        return \substr($haystack, 0, \strlen($needle)) === $needle;
    }

    protected function getCallableMethods()
    {
        return array('ListEntries', 'FormActionUri', 'CmdMessages');
    }

    public final function CmdMessages()
    {
        return $this->oMessage;
    }

    public final function ListEntries()
    {
        return $this->oListEntries;
    }

    /**
     * @see Interface FrameForm
     */
    public final function FormActionUri()
    {
        return QI::getPageUri();
    }
}
