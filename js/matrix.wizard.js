$(document).ready(function(){
	$("#form-wizard").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : true,
		disableUIStyles : true,
	
		formOptions :{
			success: function(data){$("#status").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#submitted").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			dataType: 'json',
			resetForm: true
		},
		validationOptions : {
			rules: {
				nomedopax: { required: true },
				qtdedepax: { required: true },
				qtdedecomidas: { required: true },
				idservicios: { required: true },
				password: { required: true },
				password2: { equalTo: "#password" },
				email: { required: true, email: true },
				eula: { required: true }
			},
			messages: {
				nomedopax: "Es requerido el nome do pax",
				qtdedepax: "Es requerida la cantidad de pax",
				qtdedecomidas: "Es requerida la cantidad total de servicios",
				idservicios: "Debe ingresar un tipo de servicio.",
				password: "You must enter the password",
				password2: { equalTo: "Password don't match" },
				email: { required: "Please, enter your email", email: "Correct email format is name@domain.com" },
				eula: "You must accept the eula"
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
			}
		}
	});	
	$("#form-reservas").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : true,
		disableUIStyles : true,
	
		formOptions :{
			success: function(data){$("#status").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#submitted").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			dataType: 'json',
			resetForm: true
		},
		validationOptions : {
			rules: {
				ignore: '',
				nomedopax: { required: false },
				qtdedepax: { required: false },
				password: { required: true },
				password2: { equalTo: "#password" },
				email: { required: true, email: true },
				eula: { required: true }
			},
			messages: {
				nomedopax: "Es requerido el nome do pax",
				qtdedepax: "Es requerida la cantidad de pax",
				password: "You must enter the password",
				password2: { equalTo: "Password don't match" },
				email: { required: "Please, enter your email", email: "Correct email format is name@domain.com" },
				eula: "You must accept the eula"
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
			}
		}
	});	
	
	$("#user-changepassword").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : true,
		disableUIStyles : true,
	
		formOptions :{
			success: function(data){$("#status").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#submitted").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			dataType: 'json',
			resetForm: true
		},
		validationOptions : {
			rules: {
				oldpassword: { required: true },
				password: { required: true },
				password2: { equalTo: "#password" },
			},
			messages: {
				oldpassword: "Ingrese sua antiga senha",
				password: "Debe ingresar a senha que você quiser ter",
				password2: { equalTo: "Senhas não coinciden" },
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
			}
		}
	});	
});
