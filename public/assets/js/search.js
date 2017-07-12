$('#search_device').keydown(function(event) {
	if (event.keyCode == 13) {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_device").value
		};
		document.location.href = 'Devclient?'+jQuery.param(params);
	}
}); 

$('#search_stel').keydown(function(event) {
	if (event.keyCode == 13) {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_stel").value,
			type:document.getElementById("cmb-category").value
		};
		document.location.href = 'STELclient?'+jQuery.param(params);
	}
});


$('#search_stsel').keydown(function(event) {
	if (event.keyCode == 13) {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_stsel").value,
			type:document.getElementById("cmb-category").value
		};
		document.location.href = 'STSELclient?'+jQuery.param(params);
	}
}); 
$('#search_charge').keydown(function(event) {
	if (event.keyCode == 13) {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_charge").value,
			category:document.getElementById("cmb-category").value
		};
		document.location.href = 'Chargeclient?'+jQuery.param(params);
	}
});

$('#search_stel_product').keydown(function(event) {
	if (event.keyCode == 13) {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_stel_product").value
		};
		document.location.href = 'payment_status?'+jQuery.param(params);
	}
});