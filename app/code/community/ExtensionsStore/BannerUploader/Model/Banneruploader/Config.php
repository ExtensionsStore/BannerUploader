<?php

/**
 * Banneruploader Wysiwyg Plugin Config
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */

class ExtensionsStore_BannerUploader_Model_Banneruploader_Config
{
    /**
     * Prepare banneruploader wysiwyg plugin config
     *
     * @param Varien_Object $config
     * @return array
     */
    public function getWysiwygPluginSettings($config)
    {
        $bannerUploaderConfig = array();
        
        $bannerId = Mage::app()->getRequest()->getParam('id');

        $bannerUploaderWysiwygPlugin = array(array('name' => 'banneruploader',
            'options' => array(
                'title' => Mage::helper('adminhtml')->__('Banner Images'),
                'onclick' => "bannerUploaderDialog.openDialog('".Mage::getSingleton('adminhtml/url')->getUrl('*/banneruploader', array('banner_id'=>$bannerId , 'target_id' => ''))."')",
                'class'   => 'add-image banner-uploader plugin'
        )));
        $configPlugins = $config->getData('plugins');
        $bannerUploaderConfig['plugins'] = array_merge($configPlugins, $bannerUploaderWysiwygPlugin);
        return $bannerUploaderConfig;
    }

}
