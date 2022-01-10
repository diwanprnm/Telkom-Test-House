
function validateEmail(sEmail) {
	var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	if (filter.test(sEmail)) {
		return true;
	}
	else {
		return false;
	}
}

function scroll_to_class(element_class, removed_height) {
	var scroll_to = $(element_class).offset().top - removed_height;
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 0);
	}
}

function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	if(direction == 'right') {
		new_value = now_value + ( 100 / number_of_steps );
	}
	else if(direction == 'left') {
		new_value = now_value - ( 100 / number_of_steps );
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

jQuery(document).ready(function() {
	$('#fileInput-SIUP').change(function() {
        var filename = $('#fileInput-SIUP').val();
        $('#f4-preview-1').html(filename);
		var siup_berkas = filename;
    });
    $('#fileInput-lampiran').change(function() {
        var filename = $('#fileInput-lampiran').val();
        $('#f4-preview-6').html(filename);
		var lampiran_berkas = filename;
    });
	$('#fileInput-NPWP').change(function() {
        var filename = $('#fileInput-NPWP').val();
        $('#f4-preview-11').html(filename);
		var npwp_berkas = filename;
    });
    $('#fileInput-ref-uji').change(function() {
        var filename = $('#fileInput-ref-uji').val();
        $('#f4-preview-file-ref-uji').html(filename);
		var ref_uji_berkas = filename;
    });
    $('#fileInput-prinsipal').change(function() {
        var filename = $('#fileInput-prinsipal').val();
        $('#f4-preview-8').html(filename);
		var prinsipal_berkas = filename;
    });
    $('#fileInput-sp3').change(function() {
        var filename = $('#fileInput-sp3').val();
        $('#f4-preview-file-sp3').html(filename);
		var sp3_berkas = filename;
    });
    /*
        Fullscreen background
    */
    $.backstretch("assets/img/backgrounds/1.jpg");
    
    $('#top-navbar-1').on('shown.bs.collapse', function(){
    	$.backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function(){
    	$.backstretch("resize");
    });
	
    $('#myModal').on('shown.bs.modal', function(){
    	$("#f1-nama-pemohon").focus();
    });
	
    $('#myModallogin').on('shown.bs.modal', function(){
    	$("#f1-username-login").focus();
    });
	
    /*
        Form
    */
    $('.f1 fieldset:first').fadeIn('slow');
    
    $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    // next step
	
	$(".input-submit").keydown(function(event){
		if(event.keyCode == 13){
			
		}
	});
	
    $('.f1 .btn-next').on('click', function() {
		var class_name = this.className;
    	var parent_fieldset = $(this).parents('fieldset');
    	var next_step = true;
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	
    	// fields validation
    	parent_fieldset.find('input[type="text"], input[type="password"], input[type="file"], input[type="hidden"], textarea, img').each(function() {
			// if( $(this).val() == "" ) {
				// alert("Periksa Kembali Form!");$(this).focus();
    			// $(this).addClass('input-error');
    			// next_step = false;
    		// }
			// if($(this).hasClass('f1-email-pemohon')){
				// var email_pemohon = document.getElementById("f1-email-pemohon");
				// if( email_pemohon.value != "" ){						
					// if (validateEmail(email_pemohon.value)) {
					// }else{
						// alert("Format Email tidak benar!");email_pemohon.focus();
						// $(this).addClass('input-error');next_step = false;
					// }
				// }
			// }
			// $(this).removeClass('input-error');
			
			//Data Pemohon
			if($(this).hasClass('data-pemohon')){
				if($(this).hasClass('f1-nama-pemohon')){
					var term = document.getElementById("f1-nama-pemohon");
					// if( term.value == "" ){
						// alert("Nama Pemohon Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f1-preview-1').html(term.value);
				}
				if($(this).hasClass('f1-alamat-pemohon')){
					var term = document.getElementById("f1-alamat-pemohon");
					// if( term.value == "" ){
						// alert("Alamat Pemohon Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f1-preview-2').html(term.value);
					var alamat_pemohon = term.value;
				}
				if($(this).hasClass('f1-telepon-pemohon')){
					var term = document.getElementById("f1-telepon-pemohon");
					// if( term.value == "" ){
						// alert("Telepon Pemohon Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f1-preview-3').html(term.value);
					var telepon_pemohon = term.value;
				}
				var term = document.getElementById("f1-faksimile-pemohon");
				$('#f1-preview-4').html(term.value);
				var faksimile_pemohon = term.value;
				// if($(this).hasClass('f1-faksimile-pemohon')){
					// var term = document.getElementById("f1-faksimile-pemohon");
					// if( term.value == "" ){
						// alert("Faksimile Pemohon Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
				// }
				if($(this).hasClass('f1-email-pemohon')){
					var term = document.getElementById("f1-email-pemohon");
					// if( term.value == "" ){
						// alert("Email Pemohon Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					// if (validateEmail(term.value)) {
					// }else{
						// alert("Format Email tidak benar!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f1-preview-5').html(term.value);
					var email_pemohon = term.value;
					// $("#f1-nama-perusahaan").focus();
				}
				$(this).removeClass('input-error');
			}
			//Data Perusahaan
			else if($(this).hasClass('data-perusahaan')){
				$('#f2-preview-6').html($('input[name="jns_perusahaan"]:checked').val());
				if($(this).hasClass('f1-nama-perusahaan')){
					var term = document.getElementById("f1-nama-perusahaan");
					// if( term.value == "" ){
						// alert("Nama Perusahaan Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f2-preview-1').html(term.value);
					var nama_perusahaan = term.value;
				}
				if($(this).hasClass('f1-alamat-perusahaan')){
					var term = document.getElementById("f1-alamat-perusahaan");
					// if( term.value == "" ){
						// alert("Alamat Perusahaan Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f2-preview-2').html(term.value);
					var alamat_perusahaan = term.value;
				}
				if($(this).hasClass('f1-telepon-perusahaan')){
					var term = document.getElementById("f1-telepon-perusahaan");
					// if( term.value == "" ){
						// alert("Telepon Perusahaan Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f2-preview-3').html(term.value);
					var telepon_perusahaan = term.value;
				}
				var term = document.getElementById("f1-faksimile-perusahaan");
				$('#f2-preview-4').html(term.value);
				var faksimile_perusahaan = term.value;
				// if($(this).hasClass('f1-faksimile-perusahaan')){
					// var term = document.getElementById("f1-faksimile-perusahaan");
					// if( term.value == "" ){
						// alert("Faksimile Perusahaan Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
				// }
				if($(this).hasClass('f1-email-perusahaan')){
					var term = document.getElementById("f1-email-perusahaan");
					// if( term.value == "" ){
						// alert("Email Perusahaan Wajib diisi!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					// if (validateEmail(term.value)) {
					// }else{
						// alert("Format Email tidak benar!");term.focus();
						// $(this).addClass('input-error');next_step = false;
						// return false;
					// }
					$('#f2-preview-5').html(term.value);
					var email_perusahaan = term.value;
				}
				$(this).removeClass('input-error');
			}
			//Data Perangkat
			else if($(this).hasClass('data-perangkat')){
				if($(this).hasClass('f1-nama-perangkat')){
					var term = document.getElementById("f1-nama-perangkat");
					if( term.value == "" ){
						alert("Nama Perangkat Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f3-preview-1').html(term.value);
					var nama_perangkat = term.value;
				}
				if($(this).hasClass('f1-merek-perangkat')){
					var term = document.getElementById("f1-merek-perangkat");
					if( term.value == "" ){
						alert("Merek/Pabrik Perangkat Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f3-preview-2').html(term.value);
					var merek_perangkat = term.value;
				}
				if($(this).hasClass('f1-kapasitas-perangkat')){
					var term = document.getElementById("f1-kapasitas-perangkat");
					if( term.value == "" ){
						alert("Kapasitas/Kecepatan Perangkat Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f3-preview-4').html(term.value);
					var kapasitas_perangkat = term.value;
				}
				if($(this).hasClass('f1-pembuat-perangkat')){
					var term = document.getElementById("f1-pembuat-perangkat");
					if( term.value == "" ){
						alert("Pembuat Perangkat Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f3-preview-6').html(term.value);
					var pembuat_perangkat = term.value;
				}
				if($(this).hasClass('f1-serialNumber-perangkat')){
					var term = document.getElementById("f1-serialNumber-perangkat");
					if( term.value == "" ){
						alert("Serial Number Perangkat Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f3-preview-7').html(term.value);
				}
				if($(this).hasClass('f1-model-perangkat')){
					var term = document.getElementById("f1-model-perangkat");
					if( term.value == "" ){
						alert("Model Perangkat Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f3-preview-3').html(term.value);
					var model_perangkat = term.value;
				}
				if($(this).hasClass('f1-jns-referensi-perangkat')){
					var term = document.getElementById("f1-jns-referensi-perangkat");
					if( term.value == "1" ){
						var term_value = document.getElementById("f1-cmb-ref-perangkat");
						if(term_value.value == ""){
							alert("Referensi Uji Perangkat Wajib diisi!");term_value.focus();
							$(this).addClass('input-error');next_step = false;
							return false;
						}
					}else{
						var term_value = document.getElementById("f1-referensi-perangkat");
						if(term_value.value == ""){
							alert("Referensi Uji Perangkat Wajib diisi!");term_value.focus();
							$(this).addClass('input-error');next_step = false;
							return false;
						}
					}
					$('#f3-preview-5').html(term_value.value);
					var referensi_perangkat = term_value.value;
				}
				$(this).removeClass('input-error');
			}
			//Data Upload Berkas
			else if($(this).hasClass('data-upload-berkas')){
				
				if( document.getElementById("fileInput-SIUP").files.length == 0 ){
					var siup_file = document.getElementById("siup-file").innerHTML;
					if(siup_file == ''){
						alert("Tidak Ada File SIUP!");
						$(this).addClass('input-error');next_step = false;
						return false;
					}
				}
				
				if($(this).hasClass('f1-no-siup')){
					var term = document.getElementById("f1-no-siup");
					if( term.value == "" ){
						alert("No. SIUP Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f4-preview-2').html(term.value);
					var no_siup_berkas = term.value;
				}
				if($(this).hasClass('f1-tgl-siup')){
					var term = document.getElementById("f1-tgl-siup");
					if( term.value == "" ){
						alert("Tanggal SIUP Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f4-preview-3').html(term.value);
					var tgl_siup_berkas = term.value;
				}
				
				if($(this).hasClass('f1-sertifikat-sistem-mutu')){
					var term = document.getElementById("f1-sertifikat-sistem-mutu");
					if( term.value == "" ){
						alert("Sertifikat Sistem Mutu Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f4-preview-5').html(term.value);
					var sertifikat_sistem_mutu = term.value;
				}
				
				if( document.getElementById("fileInput-lampiran").files.length == 0 ){
					var sertifikat_file = document.getElementById("sertifikat-file").innerHTML;
					if(sertifikat_file == ''){
						alert("Tidak Ada Lampiran Sertifikat Sistem Mutu!");
						$(this).addClass('input-error');next_step = false;
						return false;
					}
				}
				
				if($(this).hasClass('f1-batas-waktu')){
					var term = document.getElementById("f1-batas-waktu");
					if( term.value == "" ){
						alert("Batas Waktu Sistem Mutu Wajib diisi!");term.focus();
						$(this).addClass('input-error');next_step = false;
						return false;
					}
					$('#f4-preview-7').html(term.value);
					var batas_waktu_berkas = term.value;
				}
				
				if( document.getElementById("fileInput-NPWP").files.length == 0 ){
					var npwp_file = document.getElementById("npwp-file").innerHTML;
					if(npwp_file == ''){
						alert("Tidak Ada Lampiran Scan NPWP!");
						$(this).addClass('input-error');next_step = false;
						return false;
					}
				}
				
				if( document.getElementById("fileInput-ref-uji").files.length == 0 ){
					var ref_uji_file = document.getElementById("ref-uji-file").innerHTML;
					if(ref_uji_file == ''){
						alert("Tidak Ada Refensi Uji!");
						$(this).addClass('input-error');next_step = false;
						return false;
					}
				}
					
				var jns_pengujian = document.getElementById("hide_jns_pengujian");
				if(jns_pengujian.value == '1'){
					jns_perusahaan = $('input[name="jns_perusahaan"]:checked').val();
					if(jns_perusahaan != 'Pabrikan'){
						if( document.getElementById("fileInput-prinsipal").files.length == 0 ){
							var prinsipal_file = document.getElementById("prinsipal-file").innerHTML;
							if(prinsipal_file == ''){
								alert("Tidak Ada Surat Dukungan Prinsipal!");
								$(this).addClass('input-error');next_step = false;
								return false;
							}
						}
					}
					
				}else if(jns_pengujian.value == '2'){
					
					if( document.getElementById("fileInput-sp3").files.length == 0 ){
						var sp3_file = document.getElementById("sp3-file").innerHTML;
						if(sp3_file == ''){
							alert("Tidak Ada PLG ID dan NIB!");
							$(this).addClass('input-error');next_step = false;
							return false;
						}
					}
					
				}
				
				$(this).removeClass('input-error');
			}
			//Upload Form
			else if($(this).hasClass('data-upload-detail-pengujian')){
				if(class_name == 'btn btn-next upload-form'){
					if( document.getElementById("fileInput-detail-pengujian").files.length == 0 ){
						alert("Tidak Ada File Terpilih!");
						$(this).addClass('input-error');next_step = false;
						return false;
					}
				}
				$(this).removeClass('input-error');
			}
			// if(class_name == 'btn btn-next img1'){
				// $('#hide_jns_pengujian').val('1');
				// $('#f5-jns-pengujian').html('Jenis Pengujian : QA');
			// }else if(class_name == 'btn btn-next img2'){
				// $('#hide_jns_pengujian').val('2');
				// $('#f5-jns-pengujian').html('Jenis Pengujian : TA');
			// }else if(class_name == 'btn btn-next img3'){
				// $('#hide_jns_pengujian').val('3');
				// $('#f5-jns-pengujian').html('Jenis Pengujian : UP');
			// }else if(class_name == 'btn btn-next img4'){
				// $('#hide_jns_pengujian').val('4');
				// $('#f5-jns-pengujian').html('Jenis Pengujian : CAL');
			// }
			
			if(class_name == 'btn btn-next save-permohonan'){
				var term = document.getElementById("hide_cekSNjnsPengujian");
				if( term.value > 0 ){
					alert("Perangkat[Nama, Model] dan Jenis Pengujian sudah ada!");
					$(this).addClass('input-error');next_step = false;
					return false;
				}
				$(this).removeClass('input-error');
			}
			
			if(class_name == 'btn btn-next update-permohonan'){
				var term = document.getElementById("hide_cekSNjnsPengujian_edit");
				if( term.value > 0 ){
					alert("Perangkat[Nama, Model] dan Jenis Pengujian sudah ada!");
					$(this).addClass('input-error');next_step = false;
					return false;
				}
				$(this).removeClass('input-error');
			}
    	});
		
    	// fields validation
    	
    	if( next_step ) {
    		parent_fieldset.fadeOut(400, function() {
    			// change icons
    			current_active_step.removeClass('active').addClass('activated').next().addClass('active');
    			// progress bar
    			bar_progress(progress_line, 'right');
    			// show next step
	    		$(this).next().fadeIn();
	    		// scroll window to beginning of the form
    			scroll_to_class( $('.f1'), 20 );
	    	});
    	}
    });
    
    // previous step
    $('.f1 .btn-previous').on('click', function() {
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	
    	$(this).parents('fieldset').fadeOut(400, function() {
    		// change icons
    		current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
    		// progress bar
    		bar_progress(progress_line, 'left');
    		// show previous step
    		$(this).prev().fadeIn();
    		// scroll window to beginning of the form
			scroll_to_class( $('.f1'), 20 );
    	});
    });
    
    // submit
    $('.f1').on('submit', function(e) {
    	
    	// fields validation
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function() {
    		if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).addClass('input-error');
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});
    	// fields validation
    	
    });
    
    
});
