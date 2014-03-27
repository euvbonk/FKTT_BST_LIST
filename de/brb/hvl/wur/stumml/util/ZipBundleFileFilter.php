<?php
namespace org\fktt\bstlist\util;

\import('de_brb_hvl_wur_stumml_util_AbstractFileFilter');

/**
 * Uses this class to get all Datasheets and their current data additional
 * all YellowPages
 */
class ZipBundleFileFilter extends AbstractFileFilter
{
    /**
     * @return array string
     */
    //@Override
    protected function getFileFilter()
    {
        return array("dtd", "xsl", "css", "xml", "png", "gif", "jpg", "html", "jpeg", "ods", "pdf");
    }

    /**
     * @return array string
     */
    //@Override
    protected function getDropDirFilter()
    {
        // drop "unsorted" directory which contains files just for testing purposes
        return array("unsorted");
    }

    /**
     * @return array string
     */
    protected function getDropFileFilter()
    {
        return array("bahnhof_tpl.xsl");
    }
}
