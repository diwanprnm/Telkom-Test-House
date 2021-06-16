@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>VT - Telkom DDB</title>
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
							<div class="step-process-edit step ">
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
										<p>Preview</p>
									</li>
									<li>
										<button class="step-fill">4</button>
										<p>Unggal Form Testing</p>
									</li>
									<li>
										<button class="step-fill">5</button>
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
									<legend></legend>
					            	<input type="hidden" name="kode_jenis_pengujian" id="kode_jenis_pengujian" value="{{$examintaionType[$jns_pengujian]['number']}}"/>
									<input type="hidden" name="hide_exam_id" id="hide_exam_id" value="{{$userData->id}}"/>
					            	<input type="hidden" name="hide_id_user" id="hide_id_user" value="{{$userData->user_id}}">
									<input type="hidden" name="hide_company_id" id="hide_company_id" value="{{$userData->company_id}}">
									<input type="hidden" name="hide_ref_uji_file" id="hide_ref_uji_file" value="{{$userData->fileref_uji}}">
									<input type="hidden" name="hide_sp3_file" id="hide_sp3_file" value="{{$userData->filesrt_sp3}}">
									<input type="hidden" name="hide_dll_file" id="hide_dll_file" value="{{$userData->filedll}}">
									<div class="form-group">
										<label for="f1-jns-perusahaan" class="text-bold required">{{ trans('translate.service_company_type') }}: </label>
										<input type="radio" name="jns_perusahaan" value="Agen" placeholder="{{ trans('translate.service_company_agent') }}" @if ($userData->jnsPerusahaan == 'Agen') checked  @endif >
										<input type="radio" name="jns_perusahaan" value="Pabrikan" placeholder="{{ trans('translate.service_company_branch') }}" @if ($userData->jnsPerusahaan == 'Pabrikan') checked  @endif >
									</div>
									<div class="form-group txt-ref-perangkat">
										<label for="test_reference">{{ trans('translate.service_device_test_reference') }} *</label>
										<select multiple class="chosen-select" id="test_reference" name="test_reference[]" placeholder="{{ trans('translate.service_device_test_reference') }}"> 
											@foreach($data_stels as $item)
												@if(in_array($item->lab,$data_layanan_not_active))
													<option value="{{ $item->stel }}" disabled>{{ $item->stel }} || {{ $item->device_name }}</option>
												@else
													<option value="{{ $item->stel }}">{{ $item->stel }} || {{ $item->device_name }}</option>
												@endif
											@endforeach
										</select>
									</div>

										<div class="form-group">
										<label for="device_name">{{ trans('translate.service_device_equipment') }} *</label>
										<input type="text" name="device_name" placeholder="Laptop/Phone, Etc." id="device_name" class="required" value="{{$userData->nama_perangkat}}">
									</div>
									<div class="form-group">
										<label for="device_mark">{{ trans('translate.service_device_mark') }} *</label>
										<input type="text" name="device_mark" placeholder="{{ trans('translate.service_device_mark') }}"  id="device_mark" class="required" value="{{$userData->merk_perangkat}}">
									</div>
									<div class="form-group">
										<label for="device_capacity">{{ trans('translate.service_device_capacity') }} *</label>
										<input type="text" name="device_capacity" placeholder="10 GHz"   id="device_capacity" class="required" value="{{$userData->kapasitas_perangkat}}">
									</div>
									<div class="form-group">
										<label for="device_made_in">{{ trans('translate.service_device_manufactured_by') }} *</label>
										<input type="text" name="device_made_in" placeholder="Jakarta" id="device_made_in" class="required" value="{{$userData->pembuat_perangkat}}">
									</div>
									<div class="form-group">
										<label for="device_serial_number">{{ trans('translate.service_device_serial_number') }} *</label>
										<input type="text" name="device_serial_number" placeholder="123456789456"  id="device_serial_number" class="required" value="{{$userData->serialNumber}}">
									</div>
									<div class="form-group">
										<label for="device_model">{{ trans('translate.service_device_model') }} *</label>
										<input type="text" name="device_model" placeholder="L123456"   id="device_model" class="required" value="{{$userData->model_perangkat}}">
									</div>
									<div class="form-group"> 
										<label for="examination_location" class="text-bold required">{{ trans('translate.service_label_testing_site') }}: </label>
										<input type="radio" name="examination_location" value="0" placeholder="{{ trans('translate.service_lab_testing') }}" @if ($userData->is_loc_test == 0) checked  @endif >
										<input type="radio" name="examination_location" value="1" placeholder="{{ trans('translate.service_loc_testing') }}" @if ($userData->is_loc_test == 1) checked  @endif >
									</div>
					            </fieldset> 

					            <h2>Second Step</h2>
					            <fieldset>
					            	<legend></legend>
									@unless ($jns_pengujian == 'cal' || $jns_pengujian == 'qa')
										<div class="form-group col-xs-12">
											<label>{{ trans('translate.service_upload_reference_test') }}<span class="text-danger"></span></label>
											<input class="data-upload-berkas f1-file-ref-uji" id="refUjiFile" name="refUjiFile" type="file" accept="application/pdf,image/*" data-target-id="f4-preview-11" data-old-filename="{{$userData->fileref_uji}}">
											<div id="attachment-file"> *{{ trans('translate.maximum_filesize') }} </div>
											<a id="ref-uji-file" class="btn btn-link link-download-file" >{{$userData->fileref_uji}}</a>
										</div> 
									@endunless
									@if ($jns_pengujian == 'ta')
										<div class="dv-srt-sp3">
											<div class="form-group col-xs-12">
												<label>{{ trans('translate.service_upload_sp3') }}<span class="text-danger required">*</span></label>
												<input class="data-upload-berkas f1-file-sp3 @if (!$userData->filesrt_sp3) required @endif" id="sp3File" name="sp3File" type="file" accept="application/pdf,image/*" data-target-id="f4-preview-13" data-old-filename="{{$userData->filesrt_sp3}}">
												<div id="attachment-file"> *{{ trans('translate.maximum_filesize') }} </div>
												<a id="sp3-file" class="btn btn-link link-download-file">{{$userData->filesrt_sp3}}</a>
											</div>
										</div> 
									@endif
									<div class="dv-dll">
										<div class="form-group col-xs-12">
											<label>{{ trans('translate.service_upload_another_file') }}</label><span class="text-danger required">*</span></label>
											<input class="data-upload-berkas f1-file-dll @if (!$userData->filedll) required @endif" id="dllFile" name="dllFile" type="file" accept="application/pdf,image/*" data-target-id="f4-preview-12" data-old-filename="{{$userData->filedll}}">
											<div id="attachment-file">*{{ trans('translate.maximum_filesize') }}</div>
											<a id="dll-file" class="btn btn-link link-download-file">{{$userData->filedll}}</a>
										</div>
									</div>
					            </fieldset>

					            <h2>Third Step</h2>
					            <fieldset>
								<legend></legend>
					            		<input type="hidden" name="hide_cekSNjnsPengujian" id="hide_cekSNjnsPengujian">
										<h4>{{ trans('translate.service_preview') }}</h4>
										<h3>{{ trans('translate.service_application') }}</h3>
										<table id="preview-field" class="table table-striped">
											<caption></caption>
											<thead class="hidden">
												<tr>
													<th scope="col">-</th>
												</tr>
											</thead>
											<tr>
												<td>{{ trans('translate.service_application_name') }}</td>
												<td> : </td>
												<td colspan="6"> <div id="f1-preview-1">{{$userData->namaPemohon}}</div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_address') }}</td>
												<td> : </td>
												<td colspan="6"> <div id="f1-preview-2">{{$userData->alamatPemohon}}</div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_phone') }}</td>
												<td> : </td>
												<td> <div id="f1-preview-3">{{$userData->telpPemohon}}</div></td>
												<td colspan=2></td>
												<td>{{ trans('translate.service_application_fax') }}</td>
												<td> : </td>
												<td> <div id="f1-preview-4">{{$userData->faxPemohon ?? '-'}}</div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_email') }}</td>
												<td> : </td>
												<td colspan="6"> <div id="f1-preview-5">{{$userData->emailPemohon}}</div></td>
											</tr>
										</table>
										<h3 id="company_type"></h3>
										<div id="f2-preview-6"></div>
										<table id="preview-field" class="table table-striped">
											<caption></caption>
											<thead class="hidden">
												<tr>
													<th scope="col">-</th>
												</tr>
											</thead>
											<tr>
												<td>{{ trans('translate.service_company_name') }}</td>
												<td> : </td>
												<td colspan="6"> <div id="f2-preview-1">{{$userData->namaPerusahaan}}</div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_address') }}</td>
												<td> : </td>
												<td colspan="6"> <div id="f2-preview-2">{{$userData->alamatPerusahaan}}</div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_phone') }}</td>
												<td> : </td>
												<td> <div id="f2-preview-3">{{$userData->telpPerusahaan}}</div></td>
												<td colspan=2></td>
												<td>{{ trans('translate.service_company_fax') }}</td>
												<td> : </td>
												<td> <div id="f2-preview-4">{{$userData->faxPerusahaan ?? '-'}}</div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_email') }}</td>
												<td> : </td>
												<td colspan="6"> <div id="f2-preview-5">{{$userData->emailPerusahaan}}</div></td>
											</tr>
										</table>
										<h3 id="f5-jns-pengujian" class="f5-jns-pengujian">{{ trans('translate.service_preview_exam_type') }} : VT</h3>
										<br>
										<h3 class="telkom_test">{{ trans('translate.service_device') }} ({{ trans('translate.service_lab_testing') }})</h3>
										<h3 class="location_test">{{ trans('translate.service_device') }} ({{ trans('translate.service_loc_testing') }})</h3>
										<table id="preview-field" class="table table-striped">
											<caption></caption>
											<thead class="hidden">
												<tr>
													<th scope="col">-</th>
												</tr>
											</thead>
											<tr>
												<td>{{ trans('translate.service_device_equipment') }}</td>
												<td> : </td>
												<td colspan="6"> <div id="f3-preview-1"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_mark') }}</td>
												<td> : </td>
												<td> <div id="f3-preview-2"></div></td>
												<td colspan=2></td>
												<td>{{ trans('translate.service_device_model') }}</td>
												<td> : </td>
												<td> <div id="f3-preview-3"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_capacity') }}</td>
												<td> : </td>
												<td> <div id="f3-preview-4"></div></td>
												<td colspan=2></td>
												<td>{{ trans('translate.service_device_test_reference') }}</td>
												<td> : </td>
												<td> <div id="f3-preview-5"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_serial_number') }}</td>
												<td> : </td>
												<td> <div id="f3-preview-7"></div></td>
												<td colspan=2></td>
												<td>{{ trans('translate.service_device_manufactured_by') }}</td>
												<td> : </td>
												<td> <div id="f3-preview-6"></div></td>
											</tr>
										</table>
										<h3>{{ trans('translate.service_upload') }}</h3>
										<table id="preview-field" class="table table-striped">
											<caption></caption>
											<thead class="hidden">
												<tr>
													<th scope="col">-</th>
												</tr>
											</thead>
											<tr>
												<td>{{ trans('translate.service_upload_reference_test') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-11">{{$userData->fileref_uji}}</div></td>
											</tr> 
											<tr>
												<td>{{ trans('translate.service_upload_another_file') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-12">{{$userData->filedll}}</div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_sp3_file') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-13">{{$userData->filesrt_sp3}}</div></td>
											</tr>
										</table>
					            </fieldset>

					            <h2>Forth Step</h2>
					            <fieldset>
									<legend></legend>
					                <div class="form-group">
										<label>{{ trans('translate.service_upload_now') }}<span class="text-danger">*</span></label>
										<input class="data-upload-detail-pengujian form-control" id="fileInput-detail-pengujian" name="fuploaddetailpengujian_edit" type="file" accept="application/pdf,image/*">
										<input type="hidden" name="hide_attachment_file_edit" id="hide_attachment_file" value="{{ $userData->attachment }}"/>
										<a id="attachments-file" class="btn btn-link">{{ $userData->attachment }}</a>
										<div id="attachment-file"></div>
										<button type="button" class="button button3d btn-green upload-form">{{ trans('translate.service_upload_now') }}</button>
										<div id="attachment-file">
											{{ trans('translate.service_upload_if_form') }}
											<a class="btn btn-link" style="margin-left:-10px; height:37px; font-size: 100%;" href="{{ url('/cetakPermohonan').'/'.$userData->id }}" target="_blank">{{ trans('translate.service_upload_click') }}</a>
										</div>
									</div>
									<div class="f1-buttons">
										<a href="#next" class="button button3d btn-green upload_later">{{ trans('translate.service_upload_later') }}</a>
										<div id="attachment-file">
											{{ trans('translate.service_upload_later_alt') }}
										</div>
									</div>
					            </fieldset>

					            <h2>Fifth Step</h2>
								<fieldset class="lastFieldset">
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
	const jns_pengujian = "{{$jns_pengujian}}"
	const jns_pengujian_number = "{{$examintaionType[$jns_pengujian]['number']}}"
	form.validate({
	    errorPlacement: function errorPlacement(error, element) { element.before(error); },
	    rules: { 
	        required: true,extension: "jpeg|jpg|png|pdf"
	    }
	});

	const checkSNjnsPengujian = () =>{
		return $.ajax({
			type:'POST',
			url : "../../cekPermohonan",
			data: {
				'_token':"{{csrf_token()}}",
				'exam_id' : $('#hide_exam_id').val(),
				'examinationType' : jns_pengujian_number,
				'nama_perangkat' : $('#device_name').val(),
				'model_perangkat' : $('#device_model').val(),
				'merk_perangkat' : $('#device_mark').val(),
				'kapasitas_perangkat' : $('#device_capacity').val()
			},
			beforeSend: () => {
				console.log('loading dulu ya');
				$("body").addClass("loading");
			},
			success:(response) => {
				$("body").removeClass("loading");  
				result = response['status'];
				if(response['code'] == 1){
					formWizard.steps("previous");
					alert("{{trans('translate.service_device_already_exist') }}");
				}else if (response['code'] == 2){
					formWizard.steps("previous");
					alert("{{ trans('translate.service_device_not_6_months_yet') }}");
				}
			},
			error:(response)=>{
				$("body").removeClass("loading"); 
				formWizard.steps("previous");
				alert('Oops! Terjadi kesalahan pada server.');
			}
		});
	}

	const uploadForm = () =>{
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
		return $.ajax({
			type:'POST',
			url : "../../updatePermohonan",
			data: formPermohonan,
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
				formWizard.steps("previous");
				alert('Oops! Terjadi kesalahan pada server.');
			}
		});
	}

	const uploadSignedForm = () => {
		return $.ajax({
			type:'POST',
			url : "../../uploadPermohonanEdit",
			data: new FormData($("#form-permohonan")[0]),
			processData: false,
			contentType: false,		
			beforeSend: function(){
				$("body").addClass("loading");  
			},
			success:function(response){
				$("body").removeClass("loading");  
				console.log('berhasil', {response});
			},
			error:function(response){
				console.log({response});
				$("body").removeClass("loading"); 
				formWizard.steps("previous");
				alert('Oops! Terjadi kesalahan pada server.');
			}
		});
	}
	
	var formWizard = form.children("div").steps({
	    headerTag: "h2",
	    bodyTag: "fieldset",
	    autoFocus: true,
	    transitionEffect: "slideLeft",
	    onStepChanging: function (event, currentIndex, newIndex)
	    {  
	    	if(!form.valid() && (newIndex > currentIndex)){ 
	    		return false;
	    	}

			form.trigger("focus"); 
			form.validate().settings.ignore = ":disabled,:hidden";

			if (currentIndex == 0){	
				if (jns_pengujian != 'qa' && !$('.chosen-choices .search-choice span').length){
					$('.chosen-choices .search-field input').removeAttr('style');
					$('.chosen-choices .search-field input').addClass('error');
					return false;
				}
				checkSNjnsPengujian();
			}

			if(currentIndex ==2 && newIndex== 3){
				uploadForm();
			}

	       	if(newIndex == 2){ 
	       		$('.actions > ul > li:nth-child(2) a').text("Save");
	       	 	$("#f3-preview-1").html($("#device_name").val());
				$("#f3-preview-2").html($("#device_mark").val());
				$("#f3-preview-3").html($("#device_model").val());
				$("#f3-preview-4").html($("#device_capacity").val());
				$("#f3-preview-5").html($(".chosen-select").val().join(","));
				$("#f3-preview-6").html($("#device_made_in").val());
				$("#f3-preview-7").html($("#device_serial_number").val());
				$("#f4-preview-1").html($("#f1-no-siupp").val());
				$("#f4-preview-2").html($('#hide_siupp_file').val());
				$("#f4-preview-3").html($("#f1-tgl-siupp").val()); 
				$("#f4-preview-5").html($("#f1-sertifikat-sistem-mutu").val());
				$("#f4-preview-6").html($("#hide_sertifikat_file").val());
				$("#f4-preview-7").html($("#f1-batas-waktu").val());
				$("#f4-preview-11").html($("#hide_npwp_file").val());
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
				$( '#formBTNnext' ).html("next");
			}else if (currentIndex == 2 ){
				$( '#formBTNprevious' ).show(); $( '#formBTNnext' ).show(); $( '#formBTNfinish' ).hide();
				$( '#formBTNnext' ).html("Save");
			}else if (currentIndex == 3 ){
				$( '#formBTNprevious' ).show(); $( '#formBTNnext' ).hide(); $( '#formBTNfinish' ).hide();
				//$( '#formBTNnext' ).html("next");
			} else if (currentIndex == 4 ){
				$( '#formBTNprevious' ).hide(); $( '#formBTNnext' ).hide(); $( '#formBTNfinish' ).hide();
				// $( '#formBTNnext' ).html("Save");
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

	
	function downloadFile(file){
		var path = "{{ \Storage::disk('minio')->url('examination')}}";
		var id_exam = $('#hide_exam_id').val();
		//Get file name from url.
		var url = path+'/'+id_exam+'/'+file;
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
	
	$('.upload-form').click(function(){
		$.ajax({
			url : "../../uploadPermohonanEdit",
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
	
	var strUser = "{{ $userData->referensi_perangkat }}";
	var stel = strUser.split(',');
	$('.chosen-select').val(stel);
	$(".chosen-select").trigger("chosen:updated");
	$(".chosen-select").chosen({width: "95%"}); 

	//
	$(".upload_later, #next").on("click",function(){
		formWizard.steps("next"); 
	});

	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd', 
	    autoclose: true,
	    numberOfMonths: 2 ,
	    showButtonPanel: true
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
		$('#f1-nama-perangkat').val(deviceName);
	});

	$( document ).ready(function() {
		//if test reference is filled remove error
		$('.chosen-container .search-field').focusout(()=>{
			$('.chosen-choices .search-choice').length && $('.chosen-choices .search-field input').removeClass('error');
			$('.chosen-choices .search-field input').removeAttr('style');
		});
		// Text must be normal not bold
		const textNormal = [
			'jns_perusahaan-6',
			'jns_perusahaan-7',
			'examination_location-15',
			'examination_location-16'
		]
		document.querySelectorAll("label").forEach((x) => {
			textNormal.includes(x.htmlFor) && x.classList.add("text-normal");
		});
		//hiding ui ? (code already exist before)
		$('ul[role="tablist"]').hide();
		//download document when click
		$(document).on("click","a.link-download-file", function () {
			downloadFile($(this).html());
		});
		//file onclick update table
		document.getElementById('refUjiFile').onchange = function () {
			targetId = this.getAttribute("data-target-id");
			fileName = '';
			if (this.value){
				fileName = this.value;
			}else if(this.getAttribute("data-old-filename")){
				fileName = this.getAttribute('data-old-filename');
			}
			$('#'+targetId).html(fileName);
			console.log('target: '+targetId+' filename: '+fileName);
		};
		document.getElementById('sp3File').onchange = function () {
			targetId = this.getAttribute("data-target-id");
			fileName = '';
			if (this.value){
				fileName = this.value;
			}else if(this.getAttribute("data-old-filename")){
				fileName = this.getAttribute('data-old-filename');
			}
			$('#'+targetId).html(fileName);
			console.log('target: '+targetId+' filename: '+fileName);
		};
		document.getElementById('dllFile').onchange = function () {
			targetId = this.getAttribute("data-target-id");
			fileName = '';
			if (this.value){
				fileName = this.value;
			}else if(this.getAttribute("data-old-filename")){
				fileName = this.getAttribute('data-old-filename');
			}
			$('#'+targetId).html(fileName);
			console.log('target: '+targetId+' filename: '+fileName);
		};
		// tulisan telkom test/location test
		$(".telkom_test").show();
    	$(".location_test").hide();
		$('input[type=radio][name=examination_location]').change(function() {
			if (this.value == '1') {
           $(".location_test").show();
           $(".telkom_test").hide();
        }
        else {
			$(".telkom_test").show();
            $(".location_test").hide();
        }
    });
	});
 </script>
 
@endsection