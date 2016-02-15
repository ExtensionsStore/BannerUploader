<?php

/**
 * Template resource model
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */
class ExtensionsStore_BannerUploader_Model_Resource_Template extends Mage_Core_Model_Resource_Db_Abstract {
	protected function _construct() {
		$this->_init ( 'extensions_store_banneruploader/template', 'id' );
	}
}

