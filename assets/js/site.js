function getSiteRoot()
{
	switch (document.location.hostname)
	{              
		case 'localhost' :
			var root = '/'; break;
		case 'dev.bigspring.co.uk' :
			var root = '/'; break;
	    default :  
	    	var root = ''; break;
	}
	
	return root;
}

$(document).ready(function() {
	
	$('input[placeholder], textarea[placeholder]').placeholder();
	$('.chzn-select').chosen();
	var placeholders = {}; // used for submit and invalid handlers for validator
	$.validator.setDefaults({
        onkeyup: false,
        onfocusout: function(element) { $(element).valid(); },
        ignore: [],
        errorElement: 'span',
        errorPlacement: function(error, element) {
			if(element.hasClass('inline-valid')) {
            	return false;
            } else if(element.hasClass('group-valid')) {
            	var container = element.closest('.control-group');
            	$('span.success', container).remove();
            	if($('span.error', container).length > 0)
            		return false;
            } else {
            	var container = element.closest('.controls');	
            }
            
            error.addClass('error help-inline').appendTo(container);
        },
        highlight: function(label) {
            if($(label).hasClass('inline-valid'))
            {
            	$(label).next('.chzn-container').removeClass('success').addClass('error');
            	$(label).removeClass('success').addClass('error');
            	$(label).closest('tr').removeClass('success').addClass('error');
            }
            else
            {
            	$(label).closest('.control-group').addClass('error').removeClass('success');
            }
        },
        unhighlight: function(label) {
        	if($(label).hasClass('inline-valid'))
            {
            	$(label).next('.chzn-container').addClass('success').removeClass('error');
            	$(label).addClass('success').removeClass('error');
            	$(label).closest('tr').removeClass('error');
            }
        },
        success: function(label) {

        	if($(label).hasClass('inline-valid'))
            {
            	$(label).next('.chzn-container').addClass('success').removeClass('error');
            	$(label).addClass('success').removeClass('error');
            	$(label).closest('tr').removeClass('error');
            }
            else
            {
            	label.html('<i class="icon-ok"></i>').closest('.control-group').addClass('success').removeClass('error').closest('span .error').removeClass('error');	
            }
        }
    });
        
	$.validator.addMethod("multiemail", function(value, element) {
        if (this.optional(element)) {
            return true;
        }
        var emails = value.split( new RegExp( "\\s*,\\s*", "gi" ) );
        valid = true;
        for(var i in emails) {
            value = emails[i];
            valid=valid && jQuery.validator.methods.email.call(this, value,element);
        }
        return valid;}, "Please check the email addresses you entered");   
	
	$.validator.addMethod("complete_url", function(val, elem) {
	    // if no url, don't do anything
	    if (val.length == 0) { return true; }
	 
	    // if user has not entered http:// https:// or ftp:// assume they mean http://
	    if(!/^(https?|ftp):\/\//i.test(val)) {
	        val = 'http://'+val; // set both the value
	        $(elem).val(val); // also update the form element
	    }
	    // now check if valid url
	    // http://docs.jquery.com/Plugins/Validation/Methods/url
	    // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
	    return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
	}, 'Please enter a valid URL');
	
	// http://stackoverflow.com/questions/4904017/jquery-validate-how-to-ignore-placeholder-text-produce-error-on-default-blank
	// custom hack to stop IE thinking the placeholder text should be validated
	$.validator.addMethod("notDefaultText", function (value, element) { 
	   if (value == $(element).attr('placeholder')) {
	      return false;
	   } else {
	       return true;
	     }
	});
	
	$.validator.addClassRules({
		  required: {
		  	required: true,
		  	notDefaultText: true
		  },
		  longstring: {
		  	minlength: 2,
		  	maxlength: 255,
		  	notDefaultText: true
		  },
		  medstring: {
		  	minlength: 2,
		  	maxlength: 100,
		  	notDefaultText: true
		  },
		  shortstring: {
		  	minlength: 2,
		  	maxlength: 50,
		  	notDefaultText: true
		  },
		  ministring: {
		  	minlength: 2,
		  	maxlength: 20,
		  	notDefaultText: true
		  },
		  tinystring: {
		  	maxlength: 10,
		  	notDefaultText: true
		  },
		  postcode: {
			minlength: 6,
			maxlength: 12,
		  	notDefaultText: true	
		  },
		  phone: {
		  	minlength: 6,
		  	maxlength: 12,
		  	notDefaultText: true
		  },
		  userpassword: {
		  	minlength: 6,
          	maxlength: 50,
		  	notDefaultText: true
		  },
		  matchpassword: {
		  	equalTo: '#password'
		  },
		  multiemail: {
			notDefaultText: true,
		  	multiemail: true
		  }
	});
	
	$('form').validate();	
});