<?php
namespace org\fktt\bstlist\cmd;

\import('beans_datasheet_FileManager');
\import('beans_datasheet_xml_StationElement');
\import('io_File');

use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use SimpleXMLElement;
use org\fktt\bstlist\beans\datasheet\FileManager;
use org\fktt\bstlist\beans\datasheet\xml\StationElement;
use org\fktt\bstlist\io\File;

final class JsonListCmd
{
    public static $FILE_NAME = "bst_list.json";
    private static $EPOCH = "IV";
    private $oTargetFile;

    /**
     * @param FileManager $fm
     * @return JsonListCmd
     */
    public function __construct(FileManager $fm)
    {
        $this->oFileManager = $fm;
        $this->oTargetFile = new File("db/".self::$FILE_NAME);
        return $this;
    }

    /**
     * @return bool
     */
    public function doCommand()
    {
        /** @var $latest File */
        $latest = null;
        foreach (FileManagerImpl::$EPOCHS as $epoch)
        {
            /** @var $current File */
            $current = $this->oFileManager->getLatestFileFromEpoch($epoch);
            //echo "{$epoch} => {$current->getName()} => ".\strftime("%a, %d. %b %Y %H:%M", $current->getMTime())."<br>\n";
            if ($latest == null || $latest->getMTime() < $current->getMTime())
            {
                $latest = $current;
            }
        }
        //echo "=> {$latest->getName()} => ".\strftime("%a, %d. %b %Y %H:%M", $latest->getMTime())."<br>\n";
        if ($latest == null)
        {
            return false;
        }
        if (\strlen($latest->getPathname()) > 0 &&
                (!$this->oTargetFile->exists() || $this->oTargetFile->getMTime() < $latest->getMTime())
        )
        {
            $csvArray = array();
            foreach (FileManagerImpl::$EPOCHS as $epoch)
            {
                $list = $this->oFileManager->getFilesFromEpochWithOrder($epoch);
                // setDatasheetFileList && loadDatasheets && generate
                if (\count($list) > 0)
                {
                    /** @var $value File */
                    foreach ($list as $value)
                    {
                        // load as file url
                        $station = new StationElement(new SimpleXMLElement($value->getPathname(), null, true));
                        $path = explode("-", $value->getBasename(".xml"));
                        if (!\array_key_exists($station->getShort(), $csvArray))
                        {
                            $csvArray[$station->getShort()] = array(
                                "name" => $station->getName(),
                                "abb" => $station->getShort(),
                                "epochs" => array($path[0]."/".$value->getName()));
                        }
                        else
                        {
                            if ($epoch == JsonListCmd::$EPOCH)
                            {
                                $csvArray[$station->getShort()]["name"] = $station->getName();
                            }
                            $csvArray[$station->getShort()]["epochs"][] = $path[0]."/".$value->getName();
                        }
                    }
                }
            }
            \ksort($csvArray);

            $json = "[";
            foreach ($csvArray as $key => $value)
            {
                if (\strlen($key) > 0 && $key != "CS Pool")
                {
                    if (\strlen($json) > 3)
                    {
                        $json .= ",";
                    }
                    $json .= \json_encode($value, \JSON_UNESCAPED_UNICODE|\JSON_UNESCAPED_SLASHES);
                }
            }
            $json .= "]";

            if ($this->oTargetFile->exists())
            {
                $this->oTargetFile->delete();
            }

            \file_put_contents($this->oTargetFile->getPathname(), $json);
            return true;
        }
        return false;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->oTargetFile;
    }
}
