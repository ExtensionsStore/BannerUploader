<?php

/**
 * Banner collection 
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */
class ExtensionsStore_BannerUploader_Model_Resource_Banner_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	protected function _construct() {
		parent::_construct ();
		$this->_init ( 'extensions_store_banneruploader/banner' );
	}
}