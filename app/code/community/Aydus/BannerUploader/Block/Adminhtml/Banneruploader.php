<?php

/**
 * Banneruploader Wysiwyg Plugin uploader
 *
 * @category   Aydus
 * @package    Aydus_BannerUploader
 * @author     Aydus <davidt@aydus.com>
 */

class Aydus_BannerUploader_Block_Adminhtml_Banneruploader extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_blockGroup = 'aydus_banneruploader';
        $this->_controller = 'adminhtml';
        $this->_mode = 'banneruploader';
        $this->_headerText = $this->helper('widget')->__('Upload Banner Images');

        $this->removeButton('reset');
        $this->removeButton('back');
        
        $this->_updateButton('save', 'label', $this->helper('aydus_banneruploader')->__('Insert Banner Images'));
        $this->_updateButton('save', 'class', 'set-image');
        $this->_updateButton('save', 'id', 'insert_button');
        $this->_updateButton('save', 'onclick', 'bannerUploader.insertBannerImages()');
        
        $formKey = $this->getFormKey();
        $bannerId = (int)$this->getRequest()->getParam('banner_id');
        $uploadUrl = $this->getUrl('*/*/upload');
        $removeUrl = $this->getUrl('*/*/remove');
        $insertUrl = $this->getUrl('*/*/insert');
        
        $this->_formInitScripts[] = "bannerUploader.initPopup('$formKey','$bannerId','$uploadUrl','$removeUrl','$insertUrl')";
    }
}
