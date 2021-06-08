@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>TA - Telkom DDB</title>
@section('content')
 	<link rel="stylesheet" href="{{url('vendor/jquerystep/main.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('vendor/jquerystep/jquery.steps.css')}}" type="text/css" /> 
  	<link rel="stylesheet" href="{{url('vendor/chosen/chosen.css')}}">
  	<style type="text/css">
	  	ul[role="tablist"] {
	    	display: none;
		}

		.wizard .content {
		    min-height: 100px;
		}
		.wizard .content > .body {
		    width: 100%;
		    height: auto;
		    padding: 15px;
		    position: relative;
		}
        .text-normal{
			top: 4px;
            font-weight: normal !important;
        }
        .text-bold{
            font-weight: bold !important;
        }
	 </style>
  <div class="overlay"></div>
<!-- Page Title
		============================================= -->
		@php
			$examintaionType = [
				'qa' => [
					'number' => 1,
					'name' => 'QUALITY ASSURANCE TESTING PROCESS'
				],
				'ta' => [
					'number' => 2,
					'name' => 'TYPE APPROVAL TESTING PROCESS'
				],
				'vt' => [
					'number' => 3,
					'name' => 'VOLUNTARY TEST TESTING PROCESS',
				],
				'cal' => [
					'number' => 4,
					'name' => 'CALIBRATION TESTING PROCESS',
				]
			]
		@endphp
		<section id="page-title">
			<div class="container clearfix">
				<h1>{{ $examintaionType[$jns_pengujian]['name'] ?? 'TESTING PROCESS' }}</h1>
				<ol class="breadcrumb">
					<li><a href="{{ url('/') }}">Home</a></li>
					<li>Testing</li>
					<li class="active">Process</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">  
				<div class="container">  
				    <div class="content">
				         <div class="col-md-12">
							<div class="step-process step ">
								<div class="garis"></div>
								<ul class="number">
									<li>
										<button class="step-fill active">1</button>
										<p>Form</p>
									</li>
									<li>
										<button class="step-fill">2</button>
										<p>Data Pendukung</p>
									</li>
									<li>
										<button class="step-fill">3</button>
										<p>Selesai</p>
									</li>
								</ul>
							</div>
						</div>
						<form role="form" action="" method="post" class="material" id="form-permohonan" enctype="multipart/form-data">
						{{ csrf_field() }}
				        <div id="wizard">
				        	
					            <h2>First Step</h2>
					            <fieldset>
									<div class="form-group">
										<label for="f1-jns-perusahaan" class="text-bold">{{ trans('translate.service_company_type') }}: </label>
										<input type="radio" name="jns_perusahaan" value="Agen" placeholder="{{ trans('translate.service_company_agent') }}"  checked>
										<input type="radio" name="jns_perusahaan" value="Pabrikan" placeholder="{{ trans('translate.service_company_branch') }}" >
									</div>


									<input type="hidden"   id="f1-fjns-referensi-perangkat" name="f1-jns-referensi-perangkat" value='0' > 
									<div class="form-group txt-ref-perangkat">
										<label for="f1-referensi-perangkat">{{ trans('translate.service_device_test_reference') }} *</label>
										<select multiple name="f1-referensi-perangkat[]" placeholder="{{ trans('translate.service_device_test_reference') }}" id="f1-referensi-perangkat" class="chosen-select required error"> 
											@foreach($data_stels as $item)
												@if(in_array($item->lab,$data_layanan_not_active))
													<option value="" disabled>{{ $item->stel }} || {{ $item->device_name }}</option>
												@else
													<option value="">{{ $item->stel }} || {{ $item->device_name }}</option>
												@endif
											@endforeach
										</select>
									</div>
									  <div class="form-group">
										<label for="device_name">{{ trans('translate.service_device_equipment') }} *</label>
										<input type="text" name="device_name" placeholder="Laptop/Phone, Etc." id="device_name" class="required">
									</div>
									<div class="form-group">
										<label for="device_mark">{{ trans('translate.service_device_mark') }} *</label>
										<input type="text" name="device_mark" placeholder="{{ trans('translate.service_device_mark') }}"  id="device_mark" class="required">
									</div>
									<div class="form-group">
										<label for="device_capacity">{{ trans('translate.service_device_capacity') }} *</label>
										<input type="text" name="device_capacity" placeholder="10 GHz"   id="device_capacity" class="required">
									</div>
									<div class="form-group">
										<label for="f1-pembuat-perangkat">{{ trans('translate.service_device_manufactured_by') }} *</label>
										<input type="text" name="f1-pembuat-perangkat" placeholder="Jakarta" id="f1-pembuat-perangkat" class="required">
									</div>
									<div class="form-group">
										<label for="serial_number">{{ trans('translate.service_device_serial_number') }} *</label>
										<input type="text" name="serial_number" placeholder="123456789456"  id="serial_number" class="required">
									</div>
									<div class="form-group">
										<label for="device_model">{{ trans('translate.service_device_model') }} *</label>
										<input type="text" name="device_model" placeholder="L123456"   id="device_model" class="required">
									</div>


									<div class="form-group"> 
										<label for="lokasi_pengujian" class="text-bold">{{ trans('translate.service_label_testing_site') }}: </label>
										<input type="radio" name="lokasi_pengujian" value="0" placeholder="{{ trans('translate.service_lab_testing') }}" checked>
										<input type="radio" name="lokasi_pengujian" value="1" placeholder="{{ trans('translate.service_loc_testing') }}">
									</div>

					            </fieldset> 

					            <h2>Second Step</h2>
					            <fieldset>
									<legend></legend>

									@unless ($jns_pengujian == 'cal' || $jns_pengujian == 'qa')
										<div class="form-group col-xs-12">
											<label>{{ trans('translate.service_upload_reference_test') }}<span class="text-danger"></span></label>
											<input class="data-upload-berkas f1-file-ref-uji" id="fileInput-ref-uji" name="fuploadrefuji" type="file" accept="application/pdf,image/*">
											<div id="ref-uji-file"></div>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div> 
									@endunless

									@if ($jns_pengujian == 'ta')
										<div class="dv-srt-sp3">
											<div class="form-group col-xs-12">
												<label>{{ trans('translate.service_upload_sp3') }}<span class="text-danger">*</span></label>
												<input class="data-upload-berkas f1-file-sp3 required" id="fileInput-sp3" name="fuploadsp3" type="file" accept="application/pdf,image/*">
												<div id="sp3-file"></div>
												<div id="attachment-file">
													*{{ trans('translate.maximum_filesize') }}
												</div>
											</div>
										</div> 
									@endif

									<div class="dv-dll">
										<div class="form-group col-xs-12">
											<label>{{ trans('translate.service_upload_another_file') }}</label><span class="text-danger">*</span></label>
											<input class="data-upload-berkas f1-file-dll required" id="fileInput-dll" name="fuploaddll" type="file" accept="application/pdf,image/*" >
											<div id="dll-file"></div>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div>
									</div>
					            </fieldset>

					            <h2>Third Step</h2>
					            <fieldset>
									<legend></legend>

									<h4 class="judulselesai">{{ trans('translate.service_thanks') }}</h4> 
									<a class="button button3d btn-green" href="@php echo url('/pengujian');@endphp">Finish</a>

					            </fieldset>
						
				        </div>
				        	</form>
				    </div>
				</div> 
			</div> 
		</section><!-- #content end -->


@endsection 

@section('content_js')

 <script type="text/javascript" src="{{url('vendor/jquerystep/jquery.steps.js')}}"></script>
 <script>
 	$(window).bind('beforeunload',function(){
	    return 'are you sure you want to leave and your data will be lost?';
	});  
  	var form = $("#form-permohonan");
	form.validate({
	    errorPlacement: function errorPlacement(error, element) { element.before(error); },
	    rules: { 
	        required: true,extension: "jpeg|jpg|png|pdf"
	    }
	});
	var formWizard = form.children("div").steps({
	    headerTag: "h2",
	    bodyTag: "fieldset",
	    autoFocus: true,
	    transitionEffect: "slideLeft",
	    onStepChanging: function (event, currentIndex, newIndex)
	    {  
	    	console.log('curret: '+currentIndex+' ** newIndex: '+newIndex);
	    	if(!form.valid() && (newIndex > currentIndex)){ 
				if(currentIndex == 0 && !$('#f1_referensi_perangkat_chosen ul li.search-choice').length){
					$('#f1_referensi_perangkat_chosen .search-field input').addClass( "error" );
				}
				return false;
	    	}

			//UI
			$( '#formBTNfinish' ).hide();
	    	form.trigger("focus"); 
	        form.validate().settings.ignore = ":disabled,:hidden"; 

			if(currentIndex == 0 && newIndex == 1){
				let status = true;
				$('#f1_referensi_perangkat_chosen .search-field input').removeAttr("style")
				if(!$('#f1_referensi_perangkat_chosen ul li.search-choice').length){
					$('#f1_referensi_perangkat_chosen .search-field input').addClass( "error" );
					status = false;
					return status;
				}

				let dataTaken = {
						'_token':"{{ csrf_token() }}",
						'examinationType':{{ $examintaionType[$jns_pengujian]['number']}},
						'serialNumber_perangkat':$('#serial_number').val(),
						'nama_perangkat':$('#device_name').val(),
						'model_perangkat':$('#device_model').val(),
						'merk_perangkat':$('#device_mark').val(),
						'kapasitas_perangkat':$('#device_capacity').val()
					};

				console.log(dataTaken);
				$("body").addClass("loading");
				$.ajax({
					async: false,
					type: "POST",
					url : "../cekPermohonan",
					data: dataTaken,
					type:'post',
					success: function (resp){
						status = resp['status'];
						status == false && resp['code'] == 1 && alert(" {{ trans('translate.service_device_already_exist') }} ");
						status == false && resp['code'] == 2 && alert(" {{ trans('translate.service_device_not_6_months_yet') }} ");
						status == true &&  $( "#formBTNprevious" ).show();
					}
				});
				setTimeout( $("body").removeClass("loading"), 3000); 
				return status;
			}

			if (currentIndex == 1 && newIndex == 0){
				$( '#formBTNprevious' ).hide();
				$( "#formBTNnext" ).show();
			}

			if(currentIndex == 1 && newIndex == 2){
				$( "#formBTNnext" ).hide();
			}

			if (currentIndex == 2 && newIndex == 1){
				$( '#formBTNprevious' ).show();
				$( "#formBTNnext" ).show();
			}




	       	// if(newIndex == 4){ 
	       	// 	$('.actions > ul > li:nth-child(2) a').text("Save");
	       	//  	$("#f2-preview-7").html($("#f1-plg_id-perusahaan").val());
	       	//  	$("#f2-preview-8").html($("#f1-nib-perusahaan").val());

	       	//  	$("#f3-preview-1").html($("#f1-nama-perangkat").val());
			// 	$("#f3-preview-2").html($("#f1-merek-perangkat").val());
			// 	$("#f3-preview-3").html($("#f1-model-perangkat").val());
			// 	$("#f3-preview-4").html($("#f1-kapasitas-perangkat").val());
			// 		var stel = $(".chosen-select").val().join(",");
			// 	$("#f3-preview-5").html(stel);
			// 	// $("#f3-preview-5").html($("#f1-referensi-perangkat").val());
			// 	$("#f3-preview-6").html($("#f1-pembuat-perangkat").val());
			// 	$("#f3-preview-7").html($("#f1-serialNumber-perangkat").val());

			// 	$("#f4-preview-2").html($("#f1-no-siupp").val());
			// 	$("#f4-preview-1").html($('#hide_siupp_file').val());
			// 	$("#f4-preview-3").html($("#f1-tgl-siupp").val()); 
			// 	$("#f4-preview-5").html($("#f1-sertifikat-sistem-mutu").val());
			// 	$("#f4-preview-6").html($("#hide_sertifikat_file").val());
			// 	$("#f4-preview-7").html($("#f1-batas-waktu").val());
			// 	$("#f4-preview-11").html($("#hide_npwp_file").val());
			// 	$("#f4-preview-file-ref-uji").html($(".f1-file-ref-uji").val());
			// 	$("#f4-preview-file-sp3").html($(".f1-file-sp3").val());
			// 	$("#f4-preview-12").html($(".f1-file-dll").val());
	       	// }  
	        // if(newIndex == 5){
				
	        // }

	        // if(newIndex == 3){
			// 	$('.actions > ul > li:nth-child(2) a').text("Next");
				
			// 	var e = document.getElementById("f1-referensi-perangkat");
			// 	if(e.value==''){
			// 		alert("Please Choose Test Reference");
			// 		e.focus();
			// 		return false;
			// 	}

	        // 	var jnsPelanggan = $('#hide_jns_pengujian').val();
			// 	var serialNumber_perangkat = $('#f1-serialNumber-perangkat').val();
			// 	var nama_perangkat = $('#f1-nama-perangkat').val();
			// 	var model_perangkat = $('#f1-model-perangkat').val();
			// 	var merk_perangkat = $('#f1-merek-perangkat').val();
			// 	var kapasitas_perangkat = $('#f1-kapasitas-perangkat').val();
			// 	$.ajax({
			// 		type: "POST",
			// 		url : "../cekPermohonan",
			// 		data: {'_token':"{{ csrf_token() }}", 'jnsPelanggan':jnsPelanggan, 'serialNumber_perangkat':serialNumber_perangkat, 'nama_perangkat':nama_perangkat, 'model_perangkat':model_perangkat, 'merk_perangkat':merk_perangkat, 'kapasitas_perangkat':kapasitas_perangkat},
			// 		// dataType:'json',
			// 		type:'post',
			// 		success: function(data){
			// 			console.log(data);
			// 			$('#hide_cekSNjnsPengujian').val(data); 
			// 		}
			// 	});
	        // }  


	        if(newIndex < currentIndex ){ 
		        if(newIndex > 0) $( ".number li:eq("+(newIndex-1)+") button" ).removeClass("active").addClass("done");
		        $( ".number li:eq("+(newIndex)+" ) button" ).removeClass("done").addClass("active");
		        $( ".number li:eq("+(newIndex+1)+" ) button" ).removeClass("active");

		        $(".form-group input").removeClass("error");
		        $(".form-group span").removeClass("material-bar");
		        $('body').scrollTop(10);
	        	return true;
	        }else{
	        	if(form.valid()){
	        		$('body').scrollTop(10);
	        		if(newIndex > 0) $( ".number li:eq("+(newIndex-1)+") button" ).removeClass("active").addClass("done");
			        $( ".number li:eq("+(newIndex)+" ) button" ).removeClass("done").addClass("active");
			        $( ".number li:eq("+(newIndex+1)+" ) button" ).removeClass("active");
			        if (newIndex == 6) {
			        	$( ".number li:eq("+(newIndex)+" ) button" ).removeClass("active").addClass("done");
			        	$( ".number li:eq("+(newIndex+1)+" ) button" ).removeClass("active").addClass("done");
			        }
	        	}
	        	return form.valid();	
	        } 
	    },
	    onFinishing: function (event, currentIndex)
	    {
	        form.validate().settings.ignore = ":disabled";
	        return form.valid();
	    },
	    onFinished: function (event, currentIndex)
	    {
	        window.location.href = '@php echo url("/pengujian");@endphp';
	    }
	});
  	$('ul[role="tablist"]').hide();  


	$("#sertifikat-file").click(function() {
		var file = $('#hide_sertifikat_file').val();
		downloadFile(file);
	});
	
	$("#npwp-file").click(function() {
		var file = $('#hide_npwp_file').val();
		downloadFile(file);
	});

	$("#siupp-file").click(function() {
			var file = $('#hide_siupp_file').val();
			downloadFile(file);
		});
	$('.upload-form').click(function(){
		$.ajax({
			url : "../uploadPermohonan",
			data:new FormData($("#form-permohonan")[0]),
			// dataType:'json', 
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				$("body").addClass("loading");  
			},
			success:function(response){
				$("body").removeClass("loading");  
				formWizard.steps("next"); 
			},
			error:function(response){
				$("body").removeClass("loading");   
			}
		});
	});
	
	$(".chosen-select").chosen({width: "95%"}); 
	$(".upload_later, #next").on("click",function(){
		formWizard.steps("next"); 
	});

	function downloadFile(file){
		var path = "{{ \Storage::disk('minio')->url('company')}}";
		// var id_user = $('#hide_id_user').val();
		var company_id = $('#hide_company_id').val();
		//Get file name from url.
		var url = path+'/'+company_id+'/'+file;
		var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
		var xhr = new XMLHttpRequest();
		xhr.responseType = 'blob';
		xhr.onload = function() {
			if (this.status === 404) {
			   // not found, add some error handling
			   alert("File Tidak Ada!");
			   return false;
			}
			var a = document.createElement('a');
			a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
			a.download = filename; // Set the file name.
			a.style.display = 'none';
			document.body.appendChild(a);
			a.click();
			delete a;
		};
		xhr.open('GET', url);
		xhr.send();
	}

	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd', 
	    autoclose: true,
	    numberOfMonths: 2 ,
	    showButtonPanel: true

	});
	
	$('#company_type').html("{{ trans('translate.service_company') }} (Agen)");
	$('input[type=radio][name=jns_perusahaan]').change(function() {
		$('#company_type').html("{{ trans('translate.service_company') }} ("+this.value+")");
    });
	$(".telkom_test").show();
    $(".location_test").hide();
	$('input[type=radio][name=lokasi_pengujian]').change(function() {
        if (this.value == '1') {
           $(".location_test").show();
           $(".telkom_test").hide();
        }
        else {
			$(".telkom_test").show();
            $(".location_test").hide();
        }
    });
</script>

  <script src="{{url('vendor/chosen/chosen.jquery.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
	$("#f1-referensi-perangkat").change(function(){
		$(".chosen-select").trigger("chosen:updated");
		var e = document.getElementById("f1-referensi-perangkat");
		var strUser = e.options[e.selectedIndex].text;
		var res = strUser.split('||');
		var deviceName = res[1].replace(/spesifikasi telekomunikasi |spesifikasi telekomunikasi perangkat |telecommunication specification |spesifikasi perangkat |perangkat /gi,"");
		$('#device_name').val(deviceName);
	});

	setTimeout(() => {
		const textNormal = [
			'jns_perusahaan-1',
			'jns_perusahaan-2',
			'lokasi_pengujian-11',
			'lokasi_pengujian-12'
		]
		document.querySelectorAll("label").forEach((x) => {
			textNormal.includes(x.htmlFor) && x.classList.add("text-normal");
		});

		$('#f1_referensi_perangkat_chosen .search-field input').removeAttr("style");
	},1000);
 </script>
@endsection