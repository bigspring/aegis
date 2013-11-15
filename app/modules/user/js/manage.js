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
});
