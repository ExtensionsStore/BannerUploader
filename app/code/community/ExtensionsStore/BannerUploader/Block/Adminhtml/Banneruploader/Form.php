<?php
/**
 * Banneruploader widget plugin form
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */
class ExtensionsStore_BannerUploader_Block_Adminhtml_Banneruploader_Form extends Mage_Adminhtml_Block_Widget_Form {
	/**
	 * Banner images edit form
	 */
	protected function _prepareForm() {
		$form = new Varien_Data_Form ();
		
		$fieldset = $form->addFieldset ( 'base_fieldset', array (
				'legend' => $this->helper ( 'extensions_store_banneruploader' )->__ ( 'Images' ) 
		) );
		
		$bannerId = ( int ) $this->getRequest ()->getParam ( 'banner_id' );
		$image = $fieldset->addField ( 'banner_id', 'hidden', array (
				'name' => 'banner_id',
				'value' => $bannerId 
		) );
		
		$banner = Mage::getModel ( 'enterprise_banner/banner' )->load ( $bannerId );
		
		$content = $banner->getStoreContent ();
		
		$content = $this->helper ( 'cms' )->getBlockTemplateProcessor ()->filter ( $content );
		
		$doc = new DOMDocument ();
		$doc->loadHTML ( $content );
		
		$xpath = new DOMXPath ( $doc );
		$imgs = $xpath->query ( "//*[contains(@class, '".ExtensionsStore_BannerUploader_Model_Banner::BANNER_IMAGE_CLASS."')]" );
		$anchors = $xpath->query ( "//*[contains(@class, '".ExtensionsStore_BannerUploader_Model_Banner::BANNER_LINK_CLASS."')]" );
		$baseUrl = Mage::getBaseUrl ();
		$baseUnsecureUrl = str_replace('https:','http:', $baseUrl);
		$baseSecureUrl = str_replace('http:','https:', $baseUrl);
		
		$i = 0;
		
		foreach ( $imgs as $img ) {
			
			$increment = ($i) ? ' '.$i + 1 : '';
			$anchor = $anchors->item ( $i );
			
			if ($anchor) {
									
				$href = $anchor->getAttribute ( 'href' );
				$href = str_replace ( array($baseUnsecureUrl,$baseSecureUrl), '', $href );
				$anchorTitle = $anchor->getAttribute ( 'title' );
				$label = ($anchorTitle) ? $anchorTitle . ' Link' : $this->helper ( 'extensions_store_banneruploader' )->__ ( 'Banner Link' . $increment );
				$title = $label;
					
				$file = $fieldset->addField ( 'banner_link_' . $i, 'text', array (
						'label' => $label,
						'title' => $title,
						'name' => 'banner_link[' . $i . ']',
						'required' => false,
						'value' => $href
				) );
			}
			
			$src = $img->getAttribute ( 'src' );
			$src = ($src) ? $src : $img->getAttribute ( 'srcset' );
			$src = ($src) ? $src : $img->getAttribute ( 'data-src' );
			$alt = $img->getAttribute ( 'alt' );
			$label = ($alt) ? $alt . ' Image' : $this->helper ( 'extensions_store_banneruploader' )->__ ( 'Banner Image' . $increment  );
			$title = $label;
			
			$image = $fieldset->addField ( 'banner_image_' . $i, 'hidden', array (
					'name' => 'banner_image[' . $i . ']' 
			) );
			
			$file = $fieldset->addField ( 'banner_file_' . $i, 'image', array (
					'label' => $label,
					'title' => $title,
					'name' => 'banner_file[' . $i . ']',
					'required' => false,
					'value' => $src 
			) );	
			
			$i ++;
		}
		
		if ($i == 0) {
			
			$fieldset->addField ( 'no-banner-images', 'label', array (
					
					'label' => $this->helper ( 'extensions_store_banneruploader' )->__ ( 'No banner images available (image must have class banner-image).' ),
					'title' => $this->helper ( 'extensions_store_banneruploader' )->__ ( 'No banner images available (image must have class banner-image).' ),
					'colspan' => '2' 
			) );
		}
		
		$form->setUseContainer ( true );
		$form->setId ( 'banner_uploader_form' );
		$form->setMethod ( 'post' );
		$form->setEnctype ( 'multipart/form-data' );
		$form->setAction ( $this->getUrl ( '*/*/save', array (
				'banner_id' => $bannerId 
		) ) );
		$this->setForm ( $form );
	}
}
