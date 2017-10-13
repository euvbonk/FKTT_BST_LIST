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

final class LatestSheetChangesList extends Frame
{
    protected function getCallableMethods()
    {
        return array();
    }

    public final function showContent()
    {
        print "<pre>";
        $fm = new FileManagerImpl();
        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            print "Last changes for epoch {$epoch}:\n\n";
            /** @var $sheet File */
            foreach ($fm->getFilesFromEpochWithOrder($epoch, "ORDER_LAST") as $sheet)
            {
                $el = new BaseElement(new SimpleXMLElement($sheet->getPathname(), null, true));
                print $el->getValueForTag("name");
                print "\t\t".$el->getValueForTag("kuerzel");
                print "\t\t".$el->getValueForTag("typ");
                print "\t".\strftime("%a, %d. %b %Y %H:%M", $sheet->getMTime())."\n";
            }
            print "\n\n";
        }
        print "</pre>";
    }
}
