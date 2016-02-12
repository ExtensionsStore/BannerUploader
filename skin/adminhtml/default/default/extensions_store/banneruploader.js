/**
 * Banneruploader Wysiwyg Plugin
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_BannerUploader
 * @author     ExtensionsStore <Extensions Store <admin@extensions-store.com>>
 */

var bannerUploaderDialog = {
    getDivHtml: function(id, html) {
        if (!html) html = '';
        return '<div id="' + id + '">' + html + '</div>';
    },

    onAjaxSuccess: function(transport) {
        if (transport.responseText.isJSON()) {
            var response = transport.responseText.evalJSON()
            if (response.error) {
                throw response;
            } else if (response.ajaxExpired && response.ajaxRedirect) {
                setLocation(response.ajaxRedirect);
            }
        }
    },

    openDialog: function(bannerUploaderUrl) {
        if ($('bannerUploader_window') && typeof(Windows) != 'undefined') {
            Windows.focus('bannerUploader_window');
            return;
        }
        this.dialogWindow = Dialog.info(null, {
            draggable:true,
            resizable:true,
            closable:true,
            className:'magento',
            windowClassName:"popup-window",
            title:Translator.translate('Banner Uploader'),
            top:50,
            width:950,
            height:600,
            zIndex:1000,
            recenterAuto:false,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:'bannerUploader_window',
            onClose: this.closeDialog.bind(this)
        });
        new Ajax.Updater('modal_dialog_message', bannerUploaderUrl, {evalScripts: true});
    },
    closeDialog: function(window) {
        if (!window) {
            window = this.dialogWindow;
        }
        if (window) {
            // IE fix - hidden form select fields after closing dialog
            WindowUtilities._showSelect();
            window.close();
        }
    }
};

function BannerUploader($)
{
	var bannerId;
	var uploadUrl;
	var removeUrl;
	var insertUrl;
	var popupFormKey;
	var templateFormKey;
	var templateUrl;
	
	var displayError = function(message)
	{
		if (typeof console == 'object'){
			console.error(message);
		} else {
			alert(message);
		}
		
	};
	
	var loadTemplate = function(e)
	{
		var templateId = $(this).val();
		templateId = parseInt(templateId);
		
		if (!isNaN(templateId) && templateId>0){
			
			var data = { 
				template_id : templateId,
				form_key : templateFormKey
			};
			
			$.post(templateUrl, data, function(res){
				
				if (!res.error){
					
					var content = res.data;
					$('#banner_content_store_default_content').val(content);
					
				} else {
					
					displayError(res.data);
				}
				
			});
						
		}
		
	};
	
	var saveTemplate = function(e)
	{
		if (this.checked){
			$('#banner_content_template_name').parents('tr').show();
		} else {
			$('#banner_content_template_name').parents('tr').hide();
		}
	};
	
	var _getPath = function(currentNode)
	{
		var path = '';
		var parentNode = currentNode.parentNode;
		
		if (parentNode){
			
			path += _getPath(parentNode);
		}
		
		path += '/' + currentNode.text;
		
		return path;
		
	};
	
	var uploadFile = function(filename, imageData, $inputFile, $preview)
	{
		if (typeof uploadUrl != 'undefined'){
			
			var path = _getPath(MediabrowserInstance.currentNode);
			
			var data = {
				form_key : popupFormKey,
				banner_id : bannerId,
				filename : filename,
				image_data : imageData,
				path : path
			};
			
			$.post(uploadUrl, data, function(res){
				
				if (!res.error){
					
					var url = res.data.url;
					var media = res.data.media;
					
					$preview.attr('src', url);
					$preview.attr('title', url);
					$preview.attr('alt', url);
					$preview.parent('a').attr('href', url);
					$inputFile.attr('value',url);
					var id = $inputFile.attr('id');
					var index = id.substr(12);//'banner_file_X
					$('#banner_image_'+index).val(media);
					
				} else {
					
					displayError(res.data);
				}
				
			});	
			
		} else {
			
			displayError('Upload url not set');
		}
	};
	
	var choseFile = function()
	{		
     	var file = this.files[0];

		if (file) {
			
			var $inputFile = $(this);
			var id = $inputFile.attr('id');
			var previewImageId = id+'_image';
			var $preview = $('#'+previewImageId);
			
			var reader  = new FileReader();
			reader.onloadend = function (e) {
				var filename = file.name;
				var imageData = reader.result;
				$preview.attr('src', imageData);
				uploadFile(filename, imageData, $inputFile, $preview);
			}
		
		    reader.readAsDataURL(file);
		} 
	};
	
	var removeFile = function()
	{
		if (typeof removeUrl != 'undefined'){
			
			if (this.checked){
				
				var id = $(this).attr('id');
				var matches = id.match(/banner_file_(.+)_delete/);
				var index = matches[1];
				var $inputFile = $('#banner_file_'+index);
				var $preview = $('#banner_file_'+index+'_image');
				var fileUrl = $preview.attr('src');
				
				var data = {
					form_key : popupFormKey,
					banner_id : bannerId,
					file_url : fileUrl,
				};
				
				$.post(removeUrl, data, function(res){
					
					if (!res.error){
						
						$inputFile.attr('value',null);
						$preview.attr('src', null);
						$preview.attr('title', null);
						$preview.attr('alt', null);
						
					} else {
						
						displayError(res.data);
					}
					
				});	
				
			}
			
		} else {
			
			displayError('Remove url not set');
		}		
	};
	
	var insertBannerImages = function()
	{
		var data = $('#banner_uploader_form').serialize();
		
		$.post(insertUrl, data, function(res){
			
			if (!res.error){
				
				$('#banner_content_store_default_content').val(res.data);
				bannerUploaderDialog.closeDialog();
						
			} else {
				
				displayError(res.data);
			}			
			
		});
		
	};	
	
	return {
		
		init : function(formKeyVal, templateUrlVal)
		{
			if (formKeyVal){
				templateFormKey = formKeyVal;
			} else {
				displayError('No banner form key');
			}	
			
			if (templateUrlVal){
				templateUrl = templateUrlVal;
			} else {
				displayError('No template url');
			}	
			
			$(function(){
				
				$('#banner_content_template_name').parents('tr').hide();
				$('#banner_content_save_template').click(saveTemplate);
				$('#banner_content_load_template').change(loadTemplate);
				
			});
		},
		
		initPopup : function(formKeyVal, bannerIdVal, uploadUrlVal, removeUrlVal, insertUrlVal)
		{			
			if (formKeyVal){
				popupFormKey = formKeyVal;
			} else {
				displayError('No form key');
			}
			
			if (bannerIdVal){
				bannerId = bannerIdVal;
			} else {
				displayError('No banner id');
			}	
			
			if (uploadUrlVal){
				uploadUrl = uploadUrlVal;
			} else {
				displayError('No upload url');
			}
			
			if (removeUrlVal){
				removeUrl = removeUrlVal;
			} else {
				displayError('No remove url');
			}			
			
			if (insertUrlVal){
				insertUrl = insertUrlVal;
			} else {
				displayError('No insert url');
			}				
			
			$(function(){
				
				$('#banner_uploader_form input[type=file]').change(choseFile);
				$('#banner_uploader_form input[type=checkbox]').change(removeFile);
				
			});
		},
		
		insertBannerImages : function()
		{
			insertBannerImages();
		}
		
	};

}

if (!window.jQuery){
	document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">\x3C/script><script>jQuery.noConflict();</script>');	
	document.write('<script>var bannerUploader = BannerUploader(jQuery);</script>');	
} else {
	var bannerUploader = BannerUploader(jQuery);
}





