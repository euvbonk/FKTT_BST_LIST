<?php
import('de_brb_hvl_wur_stumml_html_Html');
import('de_brb_hvl_wur_stumml_html_charset_LocaleCharacter');

class HtmlUtil implements Html
{
    private static $INSTANCE = null;
    private static $MAP = null;

    public static function getInstance()
    {
        if (null === self::$INSTANCE)
        {
            self::$INSTANCE = new self;
            if (null === self::$MAP)
            {
                self::$MAP = LocaleCharacter::getInstance()->MAP;
            }
        }
        return self::$INSTANCE;
    }

	/*private function convert($str, $way)
	{
		$umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß", "ó", "Ó", "Ż", "ż", "ę");
		$ersetzt = array("&auml;", "&ouml;", "&uuml;", "&Auml;", "&Ouml;", "&Uuml;", "&szlig;", "&oacute;", "&Oacute;", "&#379;", "&#380;", "&#281;");
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
	}*/

    public static function toUtf8($string)
    {
        if (null === self::$MAP)
        {
            self::$MAP = LocaleCharacter::getInstance()->MAP;
        }
        return str_replace(array_keys(self::$MAP), array_values(self::$MAP), $string);
    }

    public function getHtml()
    {
        $ret = "The following characters are mapped to their utf-8 equivalent by the static function \"".__CLASS__."::toUtf8(\$string)\":<br/>";
        foreach(LocaleCharacter::getInstance()->MAP as $key => $value)
        {
            $ret .= $key." => ".$value."<br/>";
        }
        return $ret;
    }
}
?>
