<?php
namespace org\fktt\bstlist\util;

\import('util_AbstractFileFilter');

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

    /**
     * @return array string
     */
    protected function getDropFileFilter()
    {
        return array();
    }
}
