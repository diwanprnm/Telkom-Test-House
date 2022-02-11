	jQuery(document).ready(function() {
		$('#search_value').keydown(function(event) {
			if (event.keyCode == 13) {
				var baseUrl = "{{URL::to('/')}}";
				var params = {
					// search:document.getElementById("search_value").value,
					jns:document.getElementById("cmb-jns-pengujian").value,
					status:document.getElementById("cmb-jns-status").value
				};
				document.location.href = 'pengujian?'+jQuery.param(params);
			}
		});
	});
	
	function filter(){
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			// search:document.getElementById("search_value").value,
			jns:document.getElementById("cmb-jns-pengujian").value,
			status: document.getElementById("cmb-jns-status").value,
			search: $('#filter_search_input').val(),
		};
		document.location.href = 'pengujian?'+jQuery.param(params);
	}