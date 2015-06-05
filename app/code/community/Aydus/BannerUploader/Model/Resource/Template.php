<?php

/**
 * Template resource model
 *
 * @category   Aydus
 * @package    Aydus_BannerUploader
 * @author     Aydus <davidt@aydus.com>
 */

class Aydus_BannerUploader_Model_Resource_Template extends Mage_Core_Model_Resource_Db_Abstract
{
	
	protected function _construct()
	{
		$this->_init('aydus_banneruploader/template', 'id');
	}
	
}

