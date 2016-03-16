<?php

/**
 * Banneruploader observer
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */

class ExtensionsStore_BannerUploader_Model_Observer
{
    /**
     * Add banneruploader wysiwyg plugin config
     *
     * @see cms_wysiwyg_config_prepare
     * @param Varien_Event_Observer $observer
     * @return Varien_Event_Observer
     */
    public function prepareWysiwygPluginConfig(Varien_Event_Observer $observer)
    {
        $controllerName = Mage::app()->getRequest()->getControllerName();
    	if ($controllerName=='banner'){
    		$bannerId = (int)Mage::app()->getRequest()->getParam('id');
    		
    		if ($bannerId){
    			$config = $observer->getEvent()->getConfig();
    		
    			$settings = Mage::getModel('extensions_store_banneruploader/banneruploader_config')->getWysiwygPluginSettings($config);
    			$config->addData($settings);
    		}
    	}
    	
        return $observer;
    }
    
    /**
     * Add template fields to content tab
     * 
     * @see adminhtml_block_html_before
     * @param Varien_Event_Observer $observer
     * @return Varien_Event_Observer
     */
    public function addTemplateFields(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        
        if (get_class($block)=='Enterprise_Banner_Block_Adminhtml_Banner_Edit_Tab_Content'){
            
            $banner = Mage::registry('current_banner');
            if ($banner && $banner->getId()){
                $template = Mage::getModel('extensions_store_banneruploader/template')->load($banner->getId(),'banner_id');
                $bannerContent = Mage::getModel('extensions_store_banneruploader/banner')->load($banner->getId(),'banner_id');
            }
            
            $form = $block->getForm();
            $formKey = $block->getFormKey();
            $fieldset = $form->getElement('default_fieldset');
            
            $saveTemplate = $fieldset->addField('save_template', 'checkbox', array(
                    'label'                 => Mage::helper('extensions_store_banneruploader')->__('Save Banner Content as Template'),
                    'title'                 => Mage::helper('extensions_store_banneruploader')->__('Save Banner Content as Template'),
                    'name'                  => 'save_template',
                    'value'                 => 1,
                    'required'              => false,
                    'after_element_html' => '<label for="' . $form->getHtmlIdPrefix()
                    . 'save_template">'
                            . Mage::helper('extensions_store_banneruploader')->__('Save Template') . '</label>',
                    
            ));   
            
            if ($template && $template->getId()){
                $deleteTemplate = $fieldset->addField('delete_template', 'checkbox', array(
                        'label'                 => Mage::helper('extensions_store_banneruploader')->__('Delete Template Created from this Banner'),
                        'title'                 => Mage::helper('extensions_store_banneruploader')->__('Delete Template Created from this Banner'),
                        'name'                  => 'delete_template',
                        'value'                 => $template->getId(),
                        'required'              => false,
                        'after_element_html' => '<label for="' . $form->getHtmlIdPrefix()
                        . 'delete_template">'
                        . Mage::helper('extensions_store_banneruploader')->__('Delete Template') . '</label>',
                
                ));                
            }
            
            $templateName = $fieldset->addField('template_name', 'text', array(
                    'label'                 => Mage::helper('extensions_store_banneruploader')->__('Template Save Name'),
                    'title'                 => Mage::helper('extensions_store_banneruploader')->__('Template Save Name'),
                    'name'                  => 'template_name',
                    'required'              => false,
                    'value'                 => ($template && $template->getId()) ? $template->getName() : '',
            ));            
            
            $templateUrl = Mage::getUrl('*/banneruploader/template');
            
            $loadTemplate = $fieldset->addField('load_template', 'select', array(
                    'label'                 => Mage::helper('extensions_store_banneruploader')->__('Load Template'),
                    'title'                 => Mage::helper('extensions_store_banneruploader')->__('Load Template'),
                    'name'                  => 'load_template',
                    'required'              => false,
                    'values'                => Mage::getResourceModel('extensions_store_banneruploader/template_collection')->toOptionArray(),
                    'after_element_html' => '<script>bannerUploader.init("'.$formKey.'", "'.$templateUrl.'")</script>'
            ));            

            //new background style field
            $backgroundStyle = $fieldset->addField('background_style', 'text', array(
            		'label'                 => Mage::helper('extensions_store_banneruploader')->__('Background Style'),
            		'title'                 => Mage::helper('extensions_store_banneruploader')->__('Background Style'),
            		'name'                  => 'background_style',
            		'required'              => false,
                    'value'                 => ($bannerContent && $bannerContent->getId()) ? $bannerContent->getBackgroundStyle() : '',
            ));
            
            //reorder elements
            $elements = $fieldset->getElements();
            
            $contentField = $elements[1];
            $elements[1] = $saveTemplate;
            if (@$deleteTemplate){
                $elements[2] = $deleteTemplate;
                $elements[3] = $templateName;
                $elements[4] = $loadTemplate;
                $elements[5] = $backgroundStyle;
                $elements[6] = $contentField;
            } else{
                $elements[2] = $templateName;
                $elements[3] = $loadTemplate;
                $elements[4] = $backgroundStyle;
                $elements[5] = $contentField;
            }
            
        }
        
        return $observer;
        
    }
    
    /**
     * Save template from banner content
     * 
     * @see enterprise_banner_save_after
     * @param Varien_Event_Observer $observer
     * @return Varien_Event_Observer
     */
    public function saveTemplate(Varien_Event_Observer $observer)
    {
        $banner = $observer->getBanner();
        $saveTemplate = (int)Mage::app()->getRequest()->getParam('save_template');
        $deleteTemplate = (int)Mage::app()->getRequest()->getParam('delete_template');
        
        if ($saveTemplate){
            
            $content = $banner->getStoreContent();
            $templateName = Mage::app()->getRequest()->getParam('template_name');
            $templateName = ($templateName) ? $templateName : $banner->getName() . ' '.Mage::helper('extensions_store_banneruploader')->__('Template');
            
            try {
                
                $template = Mage::getModel('extensions_store_banneruploader/template')->load($banner->getId(),'banner_id');
                
    			$date = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
                
                $template->setBannerId($banner->getId())
                    ->setName($templateName)
                    ->setContent($content)
                    ->setDateCreated($date)
                    ->save();
                
            } catch(Exception $e){
                
                Mage::log($e->getMessage(), null, 'extensions_store_banneruploader.log');
            }
            
        }
        
        if (!$saveTemplate && $deleteTemplate){
            
            try {
                
                $template = Mage::getModel('extensions_store_banneruploader/template')->load($banner->getId(),'banner_id');
                $template->delete();
                
            } catch(Exception $e){
                
                Mage::log($e->getMessage(), null, 'extensions_store_banneruploader.log');
            }
        }
        
        return $observer;
    }
    
    /**
     * Save background style
     *
     * @see enterprise_banner_save_after
     * @param Varien_Event_Observer $observer
     * @return Varien_Event_Observer
     */    
    public function saveBackgroundStyle(Varien_Event_Observer $observer)
    {
        $banner = $observer->getBanner();
    	$backgroundStyle = Mage::app()->getRequest()->getParam('background_style');
    	if ($backgroundStyle){
    	            
            try {
                
                $model = Mage::getModel('extensions_store_banneruploader/banner')->load($banner->getId(),'banner_id');
                
    			$datetime = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
                
                if (!$model->getId()){
                	$model->setDateCreated($datetime);                	
                }
                $model->setBannerId($banner->getId())
                    ->setBackgroundStyle($backgroundStyle)
                    ->setDateUpdated($datetime)
                    ->save();
                
            } catch(Exception $e){
                
                Mage::log($e->getMessage(), null, 'extensions_store_banneruploader.log');
            }
    	}    	
        return $observer;
    }
    
}
