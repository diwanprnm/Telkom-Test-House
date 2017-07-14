 
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
}); 
 