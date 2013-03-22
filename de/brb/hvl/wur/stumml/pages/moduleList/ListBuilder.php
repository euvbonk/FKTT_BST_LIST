<?php

class ListBuilder
{
	private $dataArray;
	private $dataString;

    public function __construct($content)
    {
        $this->dataArray = $this->string2array($content);
        $this->dataString = "";

    }
    
	public function buildCsvString()
	{
		$arr = $this->prepareArrayData();
		$num = 1;
		$final_string = "";

		if (!empty($arr))
		{
			foreach ($arr as $key=>$value)
			{
				// gibt nur die Buchstaben der Modulkennung zurueck
				#preg_match( "/[A-Za-zÄÖÜäüö. ]*/", $value, $first );
				// Grossbuchstabe(1x)Kleinbuchstabe(0|1x)Grossbuchstabe(1x)Kleinbuchstabe(2,)
				preg_match("/([A-Z]{1}[a-z]{0,}[A-Z]{1}[a-zäüö]{2}|[A-Z]{4,5}|[a-z]{5})/", $value, $first);

				// gibt die Zahlen der Modulkennung zurueck
				preg_match("/[0-9]{1,}[A-Za-z]?/", $value, $second);
				if (empty($second[0])) { $second[0] = "XXX"; }

                if (empty($first[0]) || !isset($first[0])) { $first[0] = ""; }
				// zusammensetzung des Strings:
				// "lfd. Nummer","Buchstaben","Zahlen",""
				$final_string .= "\"".$num."\",\"".$first[0]."\",\"".$second[0]."\",\"\"\n";
				$num++;
			}
		}
		$this->dataString = $this->convert($final_string, 2);
		return;
	}

    public function getCsvString()
    {
        return $this->dataString;
    }
    
	private function prepareArrayData()
	{
		// entfernt doppelte Eintraege
		$_first = array_unique($this->dataArray);

		// ordnet die Elemente mit neuem fortlaufendem Index an
		$_first = array_values($_first);

		// entfernt den noch letzten Eintrag "Druecken Sie die Eingabetaste"
		array_splice($_first, $this->array_search_regexp("/^(Druecken|Press)/", $_first), 1);

		// solange array noch nicht leer ist
		while (count($_first) > 0)
		{	// nehme ersten Array-Eintrag
			$value = array_shift($_first);
			// pruefe, ob Eintrag mit dem Wort "Referenz" beginnt
			if (preg_match("/^(Referenz =|Handle =)/", $value))
			{
				// Wenn ja, steht jetzt an "null"ter Stelle unser
				// gewuenschter Wert, diesen also sichern
				$_three[] = $_first[0];
				// und dann gleich entfernen
				array_shift($_first);
			}
		}

		$_three = str_replace("\"", "", $_three);
		$_three = str_replace("\\", "", $_three);

		return $_three;
	}

	private function array_search_regexp($needle, $haystack)
	{
		foreach ($haystack as $key => $value)
		{
			if (preg_match($needle, $value)) { return $key; }
		}
		return FALSE;
	}

	private function string2array($str)
	{
		// Konvertiert umlaute, 
		// explodiert den String in ein Array unter entfernung von Leereintraegen
		// durchlaeuft Array und entfernt Leerzeichen vor und hinter den einzelnen Eintraegen
		return $this->trimArray(preg_split("/\r\n/", $this->convert($str, 1), -1, PREG_SPLIT_NO_EMPTY));
	}

	private function trimArray($one)
	{
		foreach ($one as $key=>$value) { $two[] = trim($value); }
		return $two;
	}

	private function convert($str, $way)
	{
		$umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü");
		$ersetzt = array("ae", "oe", "ue", "Ae", "Oe", "Ue");
		if ($way == 1)
		{
			return str_replace($umlaute, $ersetzt, $str);
		}
		elseif ($way == 2)
		{
			return str_replace($ersetzt, $umlaute, $str);
		}
		else
		{
			return FALSE;
		}
	}
}
