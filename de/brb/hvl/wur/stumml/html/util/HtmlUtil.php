<?php
namespace org\fktt\bstlist\html\util;

import('de_brb_hvl_wur_stumml_html_Html');
import('de_brb_hvl_wur_stumml_html_charset_LocaleCharacter');

use org\fktt\bstlist\html\charset\LocaleCharacter;
use org\fktt\bstlist\html\Html;

class HtmlUtil implements Html
{
    private static $INSTANCE = null;
    private static $MAP = null;

    /**
     * @static instance
     * @return HtmlUtil
     */
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

    /**
     * @static
     * @param string $string
     * @return string
     */
    public static function toUtf8($string)
    {
        if (null === self::$MAP)
        {
            self::$MAP = LocaleCharacter::getInstance()->MAP;
        }
        return \str_replace(\array_keys(self::$MAP), \array_values(self::$MAP), $string);
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $ret = "The following characters are mapped to their utf-8 equivalent by the static function \"".__CLASS__."::toUtf8(\$string)\":<br/>";
        foreach(LocaleCharacter::getInstance()->MAP as $key => $value)
        {
            $ret .= $key." => ".$value."<br/>";
        }
        return $ret;
    }

    private function __construct(){}
    private function __clone(){}
}
