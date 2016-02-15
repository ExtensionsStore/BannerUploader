<?php

/**
 * Template collection 
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */
class ExtensionsStore_BannerUploader_Model_Resource_Template_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	protected function _construct() {
		parent::_construct ();
		$this->_init ( 'extensions_store_banneruploader/template' );
	}
	
	/**
	 * Templates option array
	 *
	 * @return array
	 */
	public function toOptionArray() {
		$options = parent::_toOptionArray ( 'id', 'name' );
		
		array_unshift ( $options, array (
				'value' => null,
				'label' => Mage::helper ( 'extensions_store_banneruploader' )->__ ( '-- Load Template --' ) 
		) );
		
		return $options;
	}
}