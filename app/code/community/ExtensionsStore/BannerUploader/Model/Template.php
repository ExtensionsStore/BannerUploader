<?php

/**
 * Template model
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */
class ExtensionsStore_BannerUploader_Model_Template extends Mage_Core_Model_Abstract {
	protected $_options;
	
	/**
	 * Initialize resource model
	 */
	protected function _construct() {
		parent::_construct ();
		
		$this->_init ( 'extensions_store_banneruploader/template' );
	}
}