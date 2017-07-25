 
$( document ).ready(function() {
	var isCompanyFormActive = false;
	var isActive = $("#hide_is_company_too").val();
	if($('#hide_is_company_too').val() == 1){
		$("#btn-new-company").trigger("click");
	}
   	$("#btn-new-company").on("click",function(){
   		var inputs = $('.new-company-field');
   		if(isCompanyFormActive){
   			$("#cmb-perusahaan").attr("required",true);
   			$("#cmb-perusahaan").attr("disabled",false);
			$('#cmb-perusahaan').trigger("chosen:updated");
   			for(var i = 0;i < inputs.length; i++) {
				inputs[i].disabled = true;
			}
			$("#hide_is_company_too").val(0);
   			$(".new-company-form").css({display:"none"});
   		}else{
   			$("#cmb-perusahaan").val("");
   			$("#cmb-perusahaan").attr("required",false);
   			$("#cmb-perusahaan").attr("disabled",true);
			$('#cmb-perusahaan').trigger("chosen:updated");
			for(var i = 0;i < inputs.length; i++) {
				inputs[i].disabled = false;
			}
			$("#hide_is_company_too").val(1);
   			$(".new-company-form").css({display:"block"});
   		} 
		isCompanyFormActive = !isCompanyFormActive;
	
	});
	
	$( ".pass" ).keyup(function() {
		if ($(".pass").val() == null || $(".pass").val() == "") {
	  	// alert("Wrong Type");
	  	$(".error_text").hide();
	  	$("#confnewPass").removeClass("error");
	  }
	  if ($(".pass").val() != $("#newPass").val()) {
	  	// alert("Wrong Type");
	  	$(".error_text").show();
	  	$("#confnewPass").addClass("error");
	  }
	  if ($(".pass").val() == $("#newPass").val()) {
	  	// alert("Wrong Type");
	  	$(".error_text").hide();
	  	$("#confnewPass").removeClass("error");
	  }
	});
	
 	$("#register-form-submit").on("click",function(e){  
 		var password = $("#newPass").val();
 		var confirmPassword = $("#confnewPass").val();
 		var emailStatus = false;
 		if(password != confirmPassword){
 			console.log("password TIDAK SAMA");
 			return false;
 		}else{ 
 			if(!$(this).attr('validated'))
    		{
				$.ajax({
				   url: 'checkRegisterEmail',
				   type: 'POST',
				   data: {
				      email: $("#email").val()
				   },
				   beforeSend: function(){ 
						$("body").addClass("loading");	
					},
				   error: function() { 
				   		return false; 
				   },
				   	success: function(response) {
					   	console.log(response.status); 
				   		if(response.status){ 
				   			$("body").removeClass("loading");	
				   			console.log("REDIRECT");
				   		 	$('#register-form-submit').attr('validated',true);
		                   	$('#register-form-submit').trigger("click");
					   	} else{
					   		$("body").removeClass("loading");	
					   		$("#email").focus();
					   		$("#emailError").css("display","block");
					   	}
				   	} 
				});  
				return false;  
			}else{
				return true;
			}
			console.log("oke");
 		}   
 		return true;
 		
 	});

 	 $('#newPass, #confnewPass').bind("cut copy paste",function(e) {
	     e.preventDefault();
	 });
}); 
 