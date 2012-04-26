<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');
import('de_brb_hvl_wur_stumml_pages_moduleList_ListBuilder');
import('de_brb_hvl_wur_stumml_pages_moduleList_ModuleListSettings');

class ModuleList extends Frame implements FrameForm
{
    private $content;
    
    public function __construct()
    {
        parent::__construct(ModuleListSettings::getInstance()->getTemplateFile());
        $this->content = (!empty($_POST['content'])) ? $_POST['content'] : "";
        
        if (!empty($this->content))
        {
            $cmd = common::GetCommand();
            switch ($cmd)
            {
                case 'create' : $b = new ListBuilder($this->content);
                                $b->buildCsvString();
                                $this->echoContentForDownload($b->getCsvString());
                                exit;
                                break;
            }
        }
    }
    
    public function getPostContent()
    {
        return $this->content;
    }

    public function getLastChangeTimestamp()
    {
        return ModuleListSettings::getInstance()->lastAddonChange();
    }

    /**
     * @see Interface FrameForm
     */
    public function getFormActionUri()
    {
        return common::GetUrl(common::WhichPage());
    }

	private function echoContentForDownload($data, $fileName='foo.csv')
	{
		if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
			header('Content-Type: application/force-download');
		else
			header('Content-Type: application/octet-stream');

		if (headers_sent())
				echo 'Some data has already been output to browser, can\'t send CSV file';

		header('Content-Length: '.strlen($data));
		header('Content-disposition: attachment; filename='.$fileName);
 		echo $data;

		return;
	}
}
?>
