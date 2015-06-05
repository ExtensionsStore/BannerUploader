<?php

/**
 * Template model
 *
 * @category   Aydus
 * @package    Aydus_BannerUploader
 * @author     Aydus <davidt@aydus.com>
 */

class Aydus_BannerUploader_Model_Template extends Mage_Core_Model_Abstract
{
	protected $_options;	
    
	/**
	 * Initialize resource model
	 */
	protected function _construct()
	{
        parent::_construct();
        
		$this->_init('aydus_banneruploader/template');
	}	
		
}