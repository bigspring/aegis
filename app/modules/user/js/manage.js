$(document).ready(function() {
		var validationRules = { 
		rules: {
	      firstname: {
	        minlength: 2,
		  	maxlength: 255,
	        required: true
	      },
	      lastname: {
	        minlength: 2,
		  	maxlength: 255,
	        required: true
	      },
	      email: {
	      	required: true,
	      	email: true
	      },
	      password: {
	      	required: true,
	      	password: true,
	        minlength: 6,
          	maxlength: 50
	      },
	      confirmpassword: {
	        minlength: 6,
	        maxlength: 50,
	        required: true,
	        equalTo: 'input[name="password"]'
	      }
	    }
	 };
	 
	 if($('input[name="password"]').hasClass('exists'))
	 {
		validationRules.rules.password = {
	        minlength: 6,
	        maxlength: 50
	     };
	    validationRules.rules.confirmpassword = {
	        minlength: 6,
	        maxlength: 50,
	        equalTo: 'input[name="password"]'
	      }
	 }
	
     $('form.user-details').validate(validationRules);
     
     $('form.user-details :input:visible:first').focus();
     	
	 var fileuploadOptions = {
            autoUpload: true,
            url: getSiteRoot() + '/user/account/upload_avatar',
            maxFileSize: 5000000,
            maxNumberOfFiles: 50,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
   	        dataType: 'json',
            process: [
                {
                    action: 'load',
                    fileTypes: /^image\/(gif|jpeg|png)$/,
                    maxFileSize: 5000000 // 5MB
                },
                {
                    action: 'resize',
                    maxWidth: 500,
                    maxHeight: 500
                },
                {
                    action: 'save'
                }
            ]
    	};
    	
	// Initialize the jQuery File Upload widget:
    $('.fileupload').fileupload(fileuploadOptions);
    
    // event handler when upload complete    
    $('.fileupload').fileupload().bind('fileuploaddone', function (e, data) {
    	$('.template-download:first').remove();
    	$('.fileupload-buttonbar .error').remove();
    	var fu = $('.fileupload').data('blueimpFileupload'); // set the maximum back to 2
    	fu._adjustMaxNumberOfFiles(2);
	});
    
    // get the current avatar
  	$.getJSON($('.fileupload').fileupload('option', 'url'), function (files) {
    var fu = $('.fileupload').data('blueimpFileupload');
    fu._adjustMaxNumberOfFiles(-files.length);
    fu._renderDownload(files)
        .appendTo($('.files', '.fileupload'))
        .fadeIn(function () {
            // Fix for IE7 and lower:
            $(this).show();
        });
	});     
});
