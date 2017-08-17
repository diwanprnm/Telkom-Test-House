	jQuery(document).ready(function() {
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
	});
	
	function filter(){
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_stsel").value,
			type:document.getElementById("cmb-category").value
		};
		document.location.href = 'STSELclient?'+jQuery.param(params);
	}