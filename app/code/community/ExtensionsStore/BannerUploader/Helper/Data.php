<?php

/**
 * Standard helper
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */
class ExtensionsStore_BannerUploader_Helper_Data extends Mage_Core_Helper_Abstract {
	/**
	 * Get background style for banner
	 * 
	 * @param int $bannerId        	
	 */
	public function getBackgroundStyle($bannerId) {
		$backgroundStyle = '';
		$bannerContent = Mage::getModel ( 'extensions_store_banneruploader/banner' )->load ( $bannerId, 'banner_id' );
		if ($bannerContent->getId ()) {
			$helper = Mage::helper('cms');
			$processor = $helper->getPageTemplateProcessor();
			$backgroundStyle = $processor->filter($bannerContent->getBackgroundStyle ());
		}
		return $backgroundStyle;
	}
}