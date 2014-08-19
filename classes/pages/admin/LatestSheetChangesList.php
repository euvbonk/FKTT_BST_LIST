<?php
namespace org\fktt\bstlist\pages;

\import('beans_datasheet_FileManagerImpl');
\import('beans_datasheet_xml_BaseElement');
\import('io_File');
\import('pages_Frame');

use SimpleXMLElement;
use org\fktt\bstlist\beans\datasheet\xml\BaseElement;
use /** @noinspection PhpUnusedAliasInspection */ org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use org\fktt\bstlist\io\File;

class LatestSheetChangesList extends Frame
{
    private $cMessage = "";

    /**
     * @return LatestSheetChangesList
     */
    public function __construct()
    {
        parent::__construct();
        $this->buildList();
        return $this;
    }

    protected function getCallableMethods()
    {
        return array();
    }

    protected function buildList()
    {
        $fm = new FileManagerImpl();
        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            $this->cMessage .= "Letzte &Auml;nderungen f&uuml;r die Epoche {$epoch}:\n\n";
            /** @var $sheet File */
            foreach ($fm->getFilesFromEpochWithOrder($epoch, "ORDER_LAST") as $sheet)
            {
                $el = new BaseElement(new SimpleXMLElement($sheet->getPathname(), null, true));
                $this->cMessage .= $el->getValueForTag("name");
                $this->cMessage .= "\t\t".$el->getValueForTag("kuerzel");
                $this->cMessage .= "\t\t".$el->getValueForTag("typ");
                $this->cMessage .= "\t".\strftime("%a, %d. %b %Y %H:%M", $sheet->getMTime())."\n";
            }
            $this->cMessage .= "\n\n";
        }
    }

    /**
     * Shows given error message
     */
    //@Override
    public function showContent()
    {
        print "<pre>{$this->cMessage}</pre>";
    }
}
