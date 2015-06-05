<?php

/**
 * Template collection 
 *
 * @category   Aydus
 * @package    Aydus_BannerUploader
 * @author     Aydus <davidt@aydus.com>
 */
	
class Aydus_BannerUploader_Model_Resource_Template_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract 
{

	protected function _construct()
	{
        parent::_construct();
		$this->_init('aydus_banneruploader/template');
	}
	
	/**
	 * Templates option array
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
	    $options = parent::_toOptionArray('id', 'name');
	    
	    array_unshift($options, array('value' => null, 'label' => Mage::helper('aydus_banneruploader')->__('-- Load Template --') ));
	    
	    return $options;
	}	
	
}