	jQuery(document).ready(function() {
		$('#search_charge').keydown(function(event) {
			if (event.keyCode == 13) {
				var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_charge").value,
					category:document.getElementById("cmb-category").value
				};
				document.location.href = 'NewChargeclient?'+jQuery.param(params);
			}
		});
	});
	
	function filter(){
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_charge").value,
			category:document.getElementById("cmb-category").value
		};
		document.location.href = 'NewChargeclient?'+jQuery.param(params);
	}