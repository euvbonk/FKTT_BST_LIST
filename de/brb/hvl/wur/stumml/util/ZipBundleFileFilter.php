<?php

//import('');
import('de_brb_hvl_wur_stumml_util_AbstractFileFilter');

/**
 * Uses this class to get all Datasheets and their current data additional
 * all YellowPages
 */
class ZipBundleFileFilter extends AbstractFileFilter
{
    protected function getFileFilter()
    {
        return array("dtd", "xsl", "css", "xml", "png", "gif", "jpg", "html", "csv", "ods", "pdf");
    }
}
?>
