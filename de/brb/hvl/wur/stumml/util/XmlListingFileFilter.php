<?php
namespace org\fktt\bstlist\util;

import('de_brb_hvl_wur_stumml_util_AbstractFileFilter');

/**
 * Uses this class to get all Datasheets and their current data additional
 * all YellowPages
 */
class XmlListingFileFilter extends AbstractFileFilter
{
    /**
     * @return array string
     */
    //@Override
    protected function getFileFilter()
    {
        return array("xml");
    }

    /**
     * @return array string
     */
    //@Override
    protected function getDropDirFilter()
    {
        // drop "unsorted" directory which contains files just for testing purposes
        return array("unsorted", ".", "..");
    }
}
