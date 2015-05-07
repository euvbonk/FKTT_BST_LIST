<?php
namespace org\fktt\bstlist\cmd;

\import('io_File');
\import('util_PhpConfigFileUtils');

use org\fktt\bstlist\io\File;
use org\fktt\bstlist\util\PhpConfigFileUtils;

class RilConfigCmd
{
    private static $CSV_FILE_NAME = null;
   	private static $CONFIG_FILE_NAME = null;
   	private $rcLocal = array();
   	private $rcRemote = array();

   	public function __construct()
   	{
        self::$CONFIG_FILE_NAME = new File("RilConfig.php");
   		if (!self::$CONFIG_FILE_NAME->exists())
   		{
            throw new \Exception("File not found (".self::$CONFIG_FILE_NAME->getName().")");
   		}
   		$this->rcLocal = PhpConfigFileUtils::getArrayFromFile(self::$CONFIG_FILE_NAME);
   		self::$CSV_FILE_NAME = new File(\basename($this->rcLocal['url'], 'ods').'csv');
   		return $this;
   	}

   	public function checkUpdate()
   	{
        $today = new \DateTime();
        ## do a monthly update check only if a local file exists
        if ($this->rcLocal['last-modified'] != 0 && $this->rcLocal['update-ts'] >= $today->getTimestamp()) return false;
   		## open remote file for checking
   		$fp = \fopen($this->rcLocal['url'], 'r');
   		if ($fp !== false)
   		{
   			## only meta data are needed first
   			$data = \stream_get_meta_data($fp);
   			## close remote connection
   			\fclose($fp);
   			$update = false;
   			if (\array_key_exists('wrapper_data', $data))
   			{
   				## elements in wrapper_data have no special order
   				foreach ($data['wrapper_data'] as $value)
   				{
   					## most string values are of the form "Type: Value"
   					## explode is used to divide these into only two matching values
   					## limit parameter neccessary because date time string contains
   					## more than one :
   					$match = \explode(":", $value, 2);
   					## remove whitespace
   					$match = \array_map('trim', $match);
   					switch (\strtolower($match[0])) ## easier handling
   					{
   						## interesting cases, all others are ignored
   						case 'last-modified':
   							$lm = new \DateTime($match[1]);
   							$this->rcRemote['last-modified'] = $lm->getTimeStamp();
   							if ($this->rcRemote['last-modified'] > $this->rcLocal['last-modified'])
   							{
   								$update = true;
   							}
   							break;
   						case 'content-length':
   							$this->rcRemote['content-length'] = \intval($match[1]);
   							if ($this->rcRemote['content-length'] != $this->rcLocal['content-length'])
   							{
   								$update = true;
   							}
   							break;
   						case 'content-type':
   							$this->rcRemote['content-type'] = $match[1];
   							break;
   					}
   				}
   			}
   			if ($update)
   			{
   				if (\array_key_exists('uri', $data))
   				{
   					$this->rcRemote['url'] = $data['uri'];
   				}
   				else
   				{
   					$this->rcRemote['url'] = $this->rcLocal['url'];
   				}
   				self::$CSV_FILE_NAME = new File(\basename($this->rcRemote['url'], 'ods').'csv');
   				return true;
   			}
   		}
        ## In case also the remote file has not been changed setup new date check next month
        $next = new \DateTime('0:00 first day of next month');
        $this->rcLocal['update-ts'] = $next->getTimeStamp();
        PhpConfigFileUtils::putArrayToFile(self::$CONFIG_FILE_NAME, $this->rcLocal);
        return false;
   	}

   	public function runUpdate()
   	{
   		## get file extension from given uri
   		$ext = \pathinfo($this->rcRemote['url'], \PATHINFO_EXTENSION);
   		## create temporary file because zip stream can only be used
   		## from local file system
   		$temp = \tempnam(\sys_get_temp_dir(), $ext);
   		## download the file from given url to local temporary file
   		\copy($this->rcRemote['url'], $temp);
   		$this->createLocalList($temp);
   		\unlink($temp);
        $next = new \DateTime('0:00 first day of next month');
        $this->rcRemote['update-ts'] = $next->getTimeStamp();
   		PhpConfigFileUtils::putArrayToFile(self::$CONFIG_FILE_NAME, $this->rcRemote);
   	}

   	protected function createLocalList($file)
   	{
   		## as ODS is just an zip archive container for xml files
   		## create a XML Reader...
   		$reader = new \XMLReader();
   		## ... and open the content.xml file in the zip stream of
   		## the temporary file
   		$reader->open("zip://{$file}#content.xml");
   		## create new XMLDocument
   		$dom = new \DOMDocument();
   		## read the elements
   		while ($reader->read())
   		{
   			if ($reader->localName == 'table')
   			{
   				## extract whole table from reader only
   				$node = $dom->importNode($reader->expand(), true); ## deep import
   				## and append to the new one
   				$dom->appendChild($node);
   				## we just want this xml as content
   				#$content = $dom->saveXML();
   				break;
   			}
   		}
   		## reader is not needed anymore
   		$reader->close();

   		## getting all information by using xpath queries
   		$xpath = new \DOMXpath($dom);
   		## first all table rows are needed, they contain the cells with information
   		#$elements = $xpath->query("//table:table-row");
   		$res = '';
   		foreach ($xpath->query("//table:table-row") as $key => $row)
   		{
   			## first row element is the head row, which can be skipped
   			if ($key == 0) continue;
   			## the next are the content ones
   			$cells = $xpath->query(".//table:table-cell", $row);
   			if ($cells->length > 1)
   			{
   				$foo = array(); ## array contains text information
   				foreach ($cells as $cellKey => $cell)
   				{
   					$value = null;
   					switch ($cellKey)
   					{
   						case 1 :
   							## sometimes there is a point after the type, remove this
   						    $pos = \strrpos($cell->textContent, '.');
   						    $value = ($pos !== false) ? \substr($cell->textContent, 0, $pos) : $cell->textContent;
   							break;
   						case 0 :
   							## short name of the station
   						case 2 :
   							## name of station
   						case 4 :
   						    ## station company; optional at the moment
   							$value = $cell->textContent;
   							break;
   					}
   					if ($value != null)
   					{
   						$foo[] = "\"{$value}\"";
   					}
   				}
   				## build csv string using windows line ending
   				$res .= \implode(',', $foo)."\r\n";
   			}
   		}
   		\file_put_contents(self::$CSV_FILE_NAME->getPathname(), $res);
        self::$CSV_FILE_NAME->changeFileRights(0600);
   	}

   	public function getAsArray()
   	{
   		if (!self::$CSV_FILE_NAME->exists() || !self::$CSV_FILE_NAME->isReadable()) return array();
   		$array = \array_map('str_getcsv', \file(self::$CSV_FILE_NAME->getPathname()));
   		$foo = array();
   		foreach ($array as $key => $value)
   		{
   			## use short as key and remove it from the other array
   			$foo[$value[0]] = \array_slice($value, 1);
   		}
   		## change all keys to lower case
   		$foo = \array_change_key_case($foo);
   		return $foo;
   	}
}
