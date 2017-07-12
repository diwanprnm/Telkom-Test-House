	jQuery(document).ready(function() {
		$('#search_value').keydown(function(event) {
			if (event.keyCode == 13) {
				var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value
				};
				document.location.href = 'Devclient?'+jQuery.param(params);
			}
		});
	});