	jQuery(document).ready(function() {
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
	});
	
	function filter(){
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_stel").value,
			type:document.getElementById("cmb-category").value
		};
		document.location.href = 'STELclient?'+jQuery.param(params);
	}