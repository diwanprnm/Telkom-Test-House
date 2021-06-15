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
<!-- Page Title ============================================= -->
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
					<form role="form" action="{{url('submitPermohonan')}}" method="post" class="material" id="form-permohonan" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div id="wizard">
							<h2>First Step</h2>
							<fieldset>
								<input type="hidden" name="kode_jenis_pengujian" value="{{$examintaionType[$jns_pengujian]['number'] ?? ''}}"/>
								<input type="hidden" name="hide_company_id" id="hide_company_id" value="{{$userData->company_id ?? ''}}">
								<input type="hidden" name="hide_exam_id" id="hide_exam_id" value="{{$userData->id ?? ''}}"/>
								<input type="hidden" name="hide_device_id" id="hide_device_id" value="{{$userData->device_id ?? ''}}"/> 

								<div class="form-group">
									<label for="f1-jns-perusahaan" class="text-bold required">{{ trans('translate.service_company_type') }}: </label>
									<input type="radio" name="jns_perusahaan" value="Agen" placeholder="{{ trans('translate.service_company_agent') }}"  checked>
									<input type="radio" name="jns_perusahaan" value="Pabrikan" placeholder="{{ trans('translate.service_company_branch') }}" >
								</div>
								<div class="form-group txt-ref-perangkat">
									<label for="test_reference">{{ trans('translate.service_device_test_reference') }} *</label>
									<select @unless($jns_pengujian == 'qa') multiple @endunless name="test_reference[]" placeholder="{{ trans('translate.service_device_test_reference') }}" id="test_reference" class="chosen-select"> 
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
									<label for="device_made_in">{{ trans('translate.service_device_manufactured_by') }} *</label>
									<input type="text" name="device_made_in" placeholder="Jakarta" id="device_made_in" class="required">
								</div>
								<div class="form-group">
									<label for="device_serial_number">{{ trans('translate.service_device_serial_number') }} *</label>
									<input type="text" name="device_serial_number" placeholder="123456789456"  id="device_serial_number" class="required">
								</div>
								<div class="form-group">
									<label for="device_model">{{ trans('translate.service_device_model') }} *</label>
									<input type="text" name="device_model" placeholder="L123456"   id="device_model" class="required">
								</div>
								<div class="form-group"> 
									<label for="examination_location" class="text-bold required">{{ trans('translate.service_label_testing_site') }}: </label>
									<input type="radio" name="examination_location" value="0" placeholder="{{ trans('translate.service_lab_testing') }}" checked>
									<input type="radio" name="examination_location" value="1" placeholder="{{ trans('translate.service_loc_testing') }}">
								</div>
							</fieldset>

							<h2>Second Step</h2>
							<fieldset>
								<legend></legend>
								@unless ($jns_pengujian == 'cal' || $jns_pengujian == 'qa')
									<div class="form-group col-xs-12">
										<label>{{ trans('translate.service_upload_reference_test') }}<span class="text-danger"></span></label>
										<input class="data-upload-berkas f1-file-ref-uji" id="refUjiFile" name="refUjiFile" type="file" accept="application/pdf,image/*">
										<div id="ref-uji-file"></div>
										<div id="attachment-file">
											*{{ trans('translate.maximum_filesize') }}
										</div>
									</div> 
								@endunless
								@if ($jns_pengujian == 'ta')
									<div class="dv-srt-sp3">
										<div class="form-group col-xs-12">
											<label>{{ trans('translate.service_upload_sp3') }}<span class="text-danger required">*</span></label>
											<input class="data-upload-berkas f1-file-sp3 required" id="sp3File" name="sp3File" type="file" accept="application/pdf,image/*">
											<div id="sp3-file"></div>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div>
									</div> 
								@endif
								<div class="dv-dll">
									<div class="form-group col-xs-12">
										<label>{{ trans('translate.service_upload_another_file') }}</label><span class="text-danger required">*</span></label>
										<input class="data-upload-berkas f1-file-dll required" id="dllFile" name="dllFile" type="file" accept="application/pdf,image/*" >
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
								{{-- <a class="button button3d btn-green" href="@php echo url('/pengujian');@endphp">Finish</a> --}}
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
 	const jns_pengujian = "{{$jns_pengujian}}";
	const jns_pengujian_number = "{{$examintaionType[$jns_pengujian]['number']}}"
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


	$( document ).ready(function() {
		$('.chosen-container .search-field').focusout(()=>{
			$('.chosen-choices .search-choice').length && $('.chosen-choices .search-field input').removeClass('error');
			$('.chosen-choices .search-field input').removeAttr('style');
		});

		setTimeout(() => {
			const textNormal = [
				'jns_perusahaan-1',
				'jns_perusahaan-2',
				'jns_perusahaan-3',
				'examination_location-11',
				'examination_location-12'
			]
			document.querySelectorAll("label").forEach((x) => {
				textNormal.includes(x.htmlFor) && x.classList.add("text-normal");
			});

			$('#f1_referensi_perangkat_chosen .search-field input').removeAttr("style");
		},1000);
	});



	
	var formWizard = form.children("div").steps({
	    headerTag: "h2",
	    bodyTag: "fieldset",
	    autoFocus: true,
	    transitionEffect: "slideLeft",
	    onStepChanging: function (event, currentIndex, newIndex){  
	    	if(!form.valid() && (newIndex > currentIndex)){ 

	    	}
			//UI
	    	form.trigger("focus"); 
	        form.validate().settings.ignore = ":disabled,:hidden";

			if (currentIndex == 0){
				
				let result = false;
				$('.chosen-choices .search-field input').removeAttr('style');
				if (jns_pengujian != 'qa' && !$('.chosen-choices .search-choice span').length){
					$('.chosen-choices .search-field input').addClass('error');
					return false;
				}else if(!form.valid()){
					return false;
				}else{
					$("body").addClass("loading")
				}

				$.ajax({
					async: false,
					type:'POST',
					url : "../cekPermohonan",
					data: {
						'_token':"{{csrf_token()}}",
						'examinationType' : jns_pengujian_number,
						'nama_perangkat' : $('#device_name').val(),
						'model_perangkat' : $('#device_model').val(),
						'merk_perangkat' : $('#device_mark').val(),
						'kapasitas_perangkat' : $('#device_capacity').val()
						//'serialNumber_perangkat' : $('#device_serial_number').val(),
					},
					//dataType: 'json',	
					//contentType: "application/json; charset=utf-8",
					beforeSend: () => {
						console.log('loading dulu ya');
						$("body").addClass("loading");
					},
					success:(response) => {
						$("body").removeClass("loading");  
						result = response['status'];
						if(response['code'] == 1){
							alert("{{trans('translate.service_device_already_exist') }}");
						}else if (response['code'] == 2){
							alert("{{ trans('translate.service_device_not_6_months_yet') }}");
						}else{
							//Add below 2 lines for every Index(Steps).   
							result = true;                         
							//is_async_step = true;
							//form.steps("next");
						}
					},
					error:(response)=>{
						$("body").removeClass("loading"); 
						alert('Oops! Terjadi kesalahan pada server.');
						result = false;
					}
				});
				if (!result) return result;
				
			}

			if (currentIndex == 1 && newIndex == 2){
				if(!form.valid()){
					return false;
				}
				let isUploaded = false;
				let examinationReferenceField = '';
				//extracting examination test reference;
				if(jns_pengujian != 'qa' ){
					examinationReferencePool = [];
					choice = $('.chosen-choices .search-choice span');
					choice.each((i, element) => {
						examinationReferencePool.push( element.innerHTML.split('||')[0].trim() )
					});
					examinationReferenceField = examinationReferencePool.join(',');
				}else{
					choice = $('.chosen-single span');
					examinationReferenceField = choice[0].innerHTML.split('||')[0].trim();
				}
				//setupform and set examintion referencefield
				formPermohonan = new FormData($("#form-permohonan")[0]);
				formPermohonan.set('test_reference',examinationReferenceField);
				//uplaoding form
				$.ajax({
					async: false,
					type:'POST',
					url : "../submitPermohonan",
					data: formPermohonan,
					dataType: 'json',
					processData: false,
					contentType: false,		
					//contentType: "application/json; charset=utf-8",
					beforeSend: function(){
						$("body").addClass("loading");  
					},
					success:function(response){
						$("body").removeClass("loading");  
						console.log('berhasil', {response});
						isUploaded = true;
					},
					error:function(response){
						console.log({response});
						$("body").removeClass("loading"); 
						alert('Oops! Terjadi kesalahan pada server.');
						isUploaded = false;
					}
				});
				if (!isUploaded) return isUploaded;
			}

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
		onStepChanged: (event, currentIndex, priorIndex) =>{
			if (currentIndex == 0 ){
				$( '#formBTNprevious' ).hide(); $( '#formBTNnext' ).show(); $( '#formBTNfinish' ).hide();
				$( '#formBTNnext' ).html("Next");
			} else if (currentIndex == 1 ){
				$( '#formBTNprevious' ).show(); $( '#formBTNnext' ).show(); $( '#formBTNfinish' ).hide();
				$( '#formBTNnext' ).html("Save");
			} else if (currentIndex == 2 ){
				$( '#formBTNprevious' ).hide(); $( '#formBTNnext' ).hide(); $( '#formBTNfinish' ).show();
			}
		},
	    onFinishing: function (event, currentIndex){
	        form.validate().settings.ignore = ":disabled";
	        return form.valid();
	    },
	    onFinished: function (event, currentIndex){
	        window.location.href = '@php echo url("/pengujian");@endphp';
	    }
	});
  	$('ul[role="tablist"]').hide();  
	
	$(".chosen-select").chosen({width: "100%"}); 
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
	$("#test_reference").change(function(){
		$(".chosen-select").trigger("chosen:updated");
		var e = document.getElementById("test_reference");
		var strUser = e.options[e.selectedIndex].text;
		var res = strUser.split('||');
		var deviceName = res[1].replace(/spesifikasi telekomunikasi |spesifikasi telekomunikasi perangkat |telecommunication specification |spesifikasi perangkat |perangkat /gi,"");
		deviceName = deviceName.trim();
		$('#device_name').val(deviceName);
	});
 </script>
@endsection