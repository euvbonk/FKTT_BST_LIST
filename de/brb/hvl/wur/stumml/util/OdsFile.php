<?php

import('lib_ods-php_ods');
//import('');

class OdsFile extends ods
{
    private $fileName = null;
    private $fileStatus = null;
    private $oCellPosition = null;

    public function __construct($fileName)
    {
        $this->ods();
        $this->fileName = $fileName;
    }
    
    public static function openFile($filename)
    {
        //self::$fileName = $fileName;
        $zip = new ZipArchive();
        $zip->open($filename);
        $contentFile = $zip->getFromIndex($zip->locateName('content.xml'));
        print "Name of Archive: ".$filename."<br/>";

        $SXE = new SimpleXMLElement($contentFile);
        $ns = $SXE->getDocNamespaces(true);
//        print "Namespaces in Document:<pre>".print_r($ns, true)."</pre>";

        $office = $SXE->children($ns['office']);
        // mit diesem X-Path kommt man an das Spreadsheet Dokument und dann später auch
        // an die Tabelle, die Frage ist, wie man dort "richtig" neue Tabellenzeilen und
        // Tabellenzellen einhängt
        $body = $office->xpath("//office:body/office:spreadsheet");
        // body is an array and contains Objects of type SimpleXMLElements
        // if we have just one sheet $body contains just one entry otherwise
        // it contains a Object for each sheet
        $table = $body[0]->children($ns['table']);
        // eine table row sieht immer folgendermaßen aus:
        // <table:table-row table:style-name="ro1">
        // </table:table-row>
        // daziwschen sind die Tabellenzellen
        //print "<pre>".print_r($table, true)."</pre><br/>";
        $trow = $table->xpath("table:table-row");
        //print "<pre>".print_r($trow, true)."</pre><br/>";
        if (count($trow) == 1)
        {
            print "Es existiert nur eine Tabellenzeile<br/>";
        }
        $newTableRow = $table->addChild("table:table-row", null);
        $trow = $table->xpath("table:table-row");
        if (count($trow) == 1)
        {
            print "Es existiert nur eine Tabellenzeile (".count($trow).")<br/>";
        }
        else
        {
            print "Es existieren mehrere Tabellenzeilen (".count($trow).")<br/>";
        }
        print $table->children($ns['table'])->count().">".$newTableRow->nodeName."<<br/>";
        //$trow[count($trow)-1]->addAttribute("table:style-name", "ro1");
        // Boah was für ein Akt ein Attribut da einzufügen!!!!!
        $foo = $table->children($ns['table']);
        $foo[$table->children($ns['table'])->count()-1]->addAttribute("table:style-name", "ro1", $ns['table']);
        foreach ($foo as $key => $value)
        {
            print $key." => ".print_r($value, true)."<br/>";
            /*if ($key == 'body')
            {
                print $key." => ".print_r($value, true)."<br/>";
                foreach($value as $kay => $val)
                {
                    print $kay." => ".print_r($val, true)."<br/>";
                    $nk = $kay->children($ns['table']);
                }
            }*/
                
        }
        print "<pre>".print_r($table, true)."</pre><br/>";
        //print "<pre>".print_r($trow, true)."</pre><br/>";
        //print $trow->asXML();
        
        //$body = $kids->xpath("//office:body/office:spreadsheet");
        //$table = $body[0]->children($ns['table']);
//        print "<pre>".print_r($table, true)."</pre><br/>";
        //print_r($trow);
        //print "<pre>".print_r($kids, true)."</pre>";
        //print $kids['body']['spreadsheet']."<br/>";
        //$body = $kids['body'];//->xpath("office:body/office:spreadsheet")->children($ns['table']);
        //$body->children($ns['office']);
        //print "FP<pre>".print_r($nk, true)."</pre>";
        
        /*foreach ($SXE->item as $item)
         {
            $ns_dc = $item->children('http://www.w3.org/2001/xml-events');
            echo $ns_dc;
        }*/
        //$DD = new domDocument();
        //$DD->load($contentFile);
        //print "<pre>".print_r($SXE, true)."</pre>";
        print "<pre>".print_r($contentFile, true)."</pre>";
        $obj = new OdsFile($filename);
        //$obj->parse(file_get_contents($contentFile));
        $obj->parse($contentFile);
        $zip->close();
        return $obj;
    }
    
    public function saveFileTo($filename)
    {
    	$charset = ini_get('default_charset');
	    ini_set('default_charset', 'UTF-8');
        if ($this->fileName == null) return;
        copy($this->fileName, $filename);
        $zip = new ZipArchive();
        $zip->open($filename);
        //$contentFile = $zip->getFromIndex($zip->locateName('content.xml'));
        //file_put_contents($contentFile, $this->array2ods());
        $zip->deleteName('content.xml');
        print "<pre>".print_r($this->array2ods(), true)."</pre>";
        $zip->addFromString('content.xml', $this->array2ods());
        $zip->close();
    	ini_set('default_charset',$charset);
    }
    
    public function closeFile()
    {
        $this->fileName = null;
        $this->fileStatus = null;
    }
}

/*
$object = newOds(); //create a new ods file
$object->addCell(0,0,0,1,'float'); //add a cell to sheet 0, row 0, cell 0, with value 1 and type float
$object->addCell(0,0,1,2,'float'); //add a cell to sheet 0, row 0, cell 1, with value 1 and type float
$object->addCell(0,1,0,1,'float'); //add a cell to sheet 0, row 1, cell 0, with value 1 and type float
$object->addCell(0,1,1,2,'float'); //add a cell to sheet 0, row 1, cell 1, with value 1 and type float
saveOds($object,StationDatasheetSettings::uploadDir().'/new.ods'); //save the object to a ods file
*/
?>
