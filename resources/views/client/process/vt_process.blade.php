@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>VT - Telkom Test House</title>
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
  </style>
   <div class="overlay"></div>
<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>Voluntary Test Testing Process</h1>
				
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
							<div class="step">
								<div class="garis"></div>
								<ul class="number">
									<li>
										<button class="step-fill active">1</button>
										<p>Data Registrasi</p>
									</li>
									<li>
										<button class="step-fill">2</button>
										<p>Data Perusahaan</p>
									</li>
									<li>
										<button class="step-fill">3</button>
										<p>Data Perangkat</p>
									</li>
									<li>
										<button class="step-fill">4</button>
										<p>Data Lampiran</p>
									</li>
									<li>
										<button class="step-fill">5</button>
										<p>Preview</p>
									</li>
									<li>
										<button class="step-fill">6</button>
										<p>Unggah Form Testing</p>
									</li>
									<li>
										<button class="step-fill">7</button>
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
					            	<input type="hidden" name="hide_jns_pengujian" id="hide_jns_pengujian" value="3"/>
					            	<input type="hidden" name="hide_id_user" id="hide_id_user" value="{{$userData->user_id}}">
									<input type="hidden" name="hide_company_id" id="hide_company_id" value="{{$userData->company_id}}">
									<input type="hidden" name="hide_npwpPerusahaan" id="hide_npwpPerusahaan" value="{{$userData->npwpPerusahaan}}">
										<div class="form-group">
											<label for="f1-nama-pemohon">{{ trans('translate.service_application_name') }}</label>
											<input type="text" name="f1-nama-pemohon" placeholder="John Doe"   id="f1-nama-pemohon" value="{{$userData->namaPemohon}}" readonly>
										</div>
										<div class="form-group">
											<label for="f1-alamat-pemohon">{{ trans('translate.service_application_address') }}</label>
											<input type="text" name="f1-alamat-pemohon" placeholder="Jln. Bandung" id="f1-alamat-pemohon" readonly value="{{$userData->alamatPemohon}}">
										</div>
										<div class="form-group">
											<label for="f1-telepon-pemohon">{{ trans('translate.service_application_phone') }}</label>
											<input type="text" name="f1-telepon-pemohon" placeholder="0812345678"  id="f1-telepon-pemohon" readonly value="{{$userData->telpPemohon}}">
										</div>
										<div class="form-group">
											<label for="f1-faksimile-pemohon">{{ trans('translate.service_application_fax') }}</label>
											<input type="text" name="f1-faksimile-pemohon" placeholder="022123456"   id="f1-faksimile-pemohon" readonly
											value="{{$userData->faxPemohon}}">
										</div>
										<div class="form-group">
											<label for="f1-email-pemohon">{{ trans('translate.service_application_email') }}</label>
											<input type="text" name="f1-email-pemohon" placeholder="user@mail.com" id="f1-email-pemohon" readonly
											value="{{$userData->emailPemohon}}">
										</div>
										<div class="form-group">
											<label for="f1-email-pemohon-alternatif">{{ trans('translate.profile_email_alternate') }}</label>
											<input type="text" name="f1-email-pemohon1" placeholder="user1@mail.com"   id="f1-email-pemohon1" readonly
											value="{{$userData->emailPemohon2}}">
											<input type="text" name="f1-email-pemohon2" placeholder="user1@mail.com"   id="f1-email-pemohon2" readonly
											value="{{$userData->emailPemohon3}}">
										</div> 
					            </fieldset> 

					            <h2>Second Step</h2>
					            <fieldset>
									<legend></legend>
					                <div class="form-group">
											<label for="f1-jns-perusahaan">{{ trans('translate.service_company_type') }} : </label>
											<input type="radio" name="jns_perusahaan" value="Agen" placeholder="{{ trans('translate.service_company_agent') }}" checked>
											<input type="radio" name="jns_perusahaan" value="Pabrikan" placeholder="{{ trans('translate.service_company_branch') }}">
											<input type="radio" name="jns_perusahaan" value="Perorangan" placeholder="{{ trans('translate.service_company_individual') }}">
										</div>
										<div class="form-group">
											<label for="f1-nama-perusahaan">{{ trans('translate.service_company_name') }}</label>
											<input type="text" name="f1-nama-perusahaan" placeholder="PT. Maju Jaya" id="f1-nama-perusahaan" readonly value="{{$userData->namaPerusahaan}}">
										</div>
										<div class="form-group">
											<label for="f1-alamat-perusahaan">{{ trans('translate.service_company_address') }}</label>
											<input type="text" name="f1-alamat-perusahaan" placeholder="Jln. Bandung" id="f1-alamat-perusahaan" readonly value="{{$userData->alamatPerusahaan}}">
										</div>
										<div class="form-group">
											<label for="f1-telepon-perusahaan">{{ trans('translate.service_company_phone') }}</label>
											 <input type="text" name="f1-telepon-perusahaan" placeholder="022123456"  id="f1-telepon-perusahaan" readonly value="{{$userData->telpPerusahaan}}">
										</div>
										<div class="form-group">
											<label for="f1-faksimile-perusahaan">{{ trans('translate.service_company_fax') }}</label>
											 <input type="text" name="f1-faksimile-perusahaan" placeholder="022123456"  id="f1-faksimile-perusahaan" readonly
											  value="{{$userData->faxPerusahaan}}">
										</div>
										<div class="form-group">
											<label for="f1-email-perusahaan">{{ trans('translate.service_company_email') }}</label>
											 <input type="text" name="f1-email-perusahaan" placeholder="company@mail.com" id="f1-email-perusahaan" readonly
											  value="{{$userData->emailPerusahaan}}">
										</div>
										<div class="form-group" style="margin-top:-30px; margin-bottom:30px; height:25px; font-size: 80%;">
											<p class="text-company-email-caption">{{ trans('translate.service_company_confirm') }}</p>
										</div> 
					            </fieldset>

					            <h2>Third Step</h2>
					            <fieldset>
										<legend></legend>
										<div class="form-group"> 
											<input type="radio" name="lokasi_pengujian" value="0" placeholder="{{ trans('translate.service_lab_testing') }}" checked>
											<input type="radio" name="lokasi_pengujian" value="1" placeholder="{{ trans('translate.service_loc_testing') }}">
										</div>
										<div class="form-group txt-ref-perangkat">
											<label for="f1-referensi-perangkat">{{ trans('translate.service_device_test_reference') }} *</label>
											<select multiple class="chosen-select" id="f1-referensi-perangkat" name="f1-referensi-perangkat[]" placeholder="{{ trans('translate.service_device_test_reference') }}"> 
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
											<label for="f1-nama-perangkat">{{ trans('translate.service_device_equipment') }} *</label>
											<input type="text" name="f1-nama-perangkat" placeholder="{{ trans('translate.example') }}Laptop/Phone, Etc." id="f1-nama-perangkat" class="required">
										</div>
										<div class="form-group">
											<label for="f1-merek-perangkat">{{ trans('translate.service_device_mark') }} *</label>
											<input type="text" name="f1-merek-perangkat" placeholder="{{ trans('translate.example') }}{{ trans('translate.service_device_mark') }}"  id="f1-merek-perangkat" class="required">
										</div>
										<div class="form-group">
											<label for="f1-kapasitas-perangkat">{{ trans('translate.service_device_capacity') }} *</label>
											<input type="text" name="f1-kapasitas-perangkat" placeholder="{{ trans('translate.example') }}10 GHz"   id="f1-kapasitas-perangkat" class="required">
										</div>
										<div class="form-group">
											<label for="f1-pembuat-perangkat">{{ trans('translate.service_device_manufactured_by') }} *</label>
											<input type="text" name="f1-pembuat-perangkat" placeholder="{{ trans('translate.example') }}Indonesia" id="f1-pembuat-perangkat" class="required">
										</div>
										<div class="form-group">
											<label for="f1-serialNumber-perangkat">{{ trans('translate.service_device_serial_number') }} *</label>
											<input type="text" name="f1-serialNumber-perangkat" placeholder="{{ trans('translate.example') }}123456789456"  id="f1-serialNumber-perangkat" class="required">
										</div>
										<div class="form-group">
											<label for="f1-model-perangkat">{{ trans('translate.service_device_model') }} *</label>
											<input type="text" name="f1-model-perangkat" placeholder="{{ trans('translate.example') }}L123456"   id="f1-model-perangkat" class="required">
										</div>
										<input type="hidden"   id="f1-fjns-referensi-perangkat" name="f1-jns-referensi-perangkat" value='0'> 
					            </fieldset>

					            <h2>Forth Step</h2>
					            <fieldset>
									<legend></legend>
					                <div class="form-group">
											<label>{{ trans('translate.service_upload_siup') }}<span class="text-danger">* 
												@if($userData->fileSIUP)
													({{ trans('translate.file_already_exists') }})
												@endif
											</span> 
											</label>
											<input   id="fileInput-SIUP" name="fuploadsiup" type="file" accept="application/pdf,image/*">
											<input type="hidden" name="hide_siup_file" id="hide_siup_file" value="{{$userData->fileSIUP}}"/>
											<a id="siup-file" class="btn btn-link" >{{$userData->fileSIUP}}</a>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div>
										<div class="form-group" style="margin-bottom:0.01%">
											<label>{{ trans('translate.service_upload_siup_no') }}</label>
											<input type="text" name="f1-no-siup" placeholder="{{ trans('translate.service_upload_siup_no') }}"   id="f1-no-siup" value="{{$userData->noSIUP}}">
										</div>
										<div class="form-group">
											<label>{{ trans('translate.service_upload_siup_date') }}</label>
											<input type="text" name="f1-tgl-siup" placeholder="{{ trans('translate.service_upload_siup_date') }}" class="data-upload-berkas datepicker f1-tgl-siup  input-submit" id="f1-tgl-siup" value="{{$userData->tglSIUP}}">

										</div>
										<div class="form-group col-xs-12" style="margin-top:35px">
											<label>{{ trans('translate.service_upload_certificate') }}<span class="text-danger">*</span></label>
											<input type="text" name="f1-sertifikat-sistem-mutu" placeholder="{{ trans('translate.service_upload_certificate') }}" id="f1-sertifikat-sistem-mutu" value="{{$userData->noSertifikat}}">
										</div>
										<div class="form-group col-xs-12" style="margin-bottom:0.01%">
											<label>{{ trans('translate.service_upload_certificate_file') }}<span class="text-danger">* 
												@if($userData->fileSertifikat)
													({{ trans('translate.file_already_exists') }})
												@endif
											</span>
											</label>
											<input   id="fileInput-lampiran" name="fuploadlampiran" type="file" accept="application/pdf,image/*">
											<input type="hidden" name="hide_sertifikat_file" id="hide_sertifikat_file" value="{{$userData->fileSertifikat}}"/>
											<a id="sertifikat-file" class="btn btn-link" >{{$userData->fileSertifikat}}</a>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div>
										<div class="form-group">
											<label>{{ trans('translate.service_upload_certificate_date') }}</label>
											<input type="text" name="f1-batas-waktu" placeholder="{{ trans('translate.service_upload_certificate_date') }}" class="datepicker data-upload-berkas f1-batas-waktu  input-submit" id="f1-batas-waktu" value="{{$userData->tglSertifikat}}">
										</div>
										<div class="form-group col-xs-12" style="margin-top:35px">
											<label>{{ trans('translate.service_upload_npwp') }}<span class="text-danger">* 
												@if($userData->fileNPWP)
													({{ trans('translate.file_already_exists') }})
												@endif 
											</span>
											</label>
											<input class="data-upload-berkas f1-file-NPWP" id="fileInput-NPWP" name="fuploadnpwp" type="file" accept="application/pdf,image/*">
											<input type="hidden" name="hide_npwp_file" id="hide_npwp_file" value="{{$userData->fileNPWP}}"/>
											<a id="npwp-file" class="btn btn-link" >{{$userData->fileNPWP}}</a>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div>
										<div class="form-group col-xs-12">
											<label>{{ trans('translate.service_upload_reference_test') }}<span class="text-danger">*</span></label>
											<input class="data-upload-berkas f1-file-ref-uji required" id="fileInput-ref-uji" name="fuploadrefuji" type="file" accept="application/pdf,image/*">
											<div id="ref-uji-file"></div>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div> 
										<div class="form-group col-xs-12">
											<label>{{ trans('translate.service_upload_another_file') }}</label>
											<input class="data-upload-berkas f1-file-dll" id="fileInput-dll" name="fuploaddll" type="file" accept="application/pdf,image/*" >
											<div id="dll-file"></div>
											<div id="attachment-file">
												*{{ trans('translate.maximum_filesize') }}
											</div>
										</div>
					            </fieldset>

					            <h2>Fifth Step</h2>
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
												<td> <div id="f1-preview-4">{{$userData->faxPemohon}}</div></td>
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
												<td> <div id="f2-preview-4">{{$userData->faxPerusahaan}}</div></td>
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
												<td>{{ trans('translate.service_upload_siup') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-1"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_siup_no') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-2"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_siup_date') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-3"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_certificate') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-5"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_certificate_file') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-6"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_certificate_date') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-7"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_npwp') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-11"></div></td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_upload_reference_test') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-file-ref-uji"></div></td>
											</tr> 
											<tr>
												<td>{{ trans('translate.service_upload_another_file') }}</td>
												<td> : </td>
												<td> <div id="f4-preview-12"></div></td>
											</tr>
										</table>
										 
					            </fieldset>
					             <h2>Sixth Step</h2>
					            <fieldset>
									<legend></legend>
					            	<div class="form-group">
											<label>{{ trans('translate.service_upload_now') }}<span class="text-danger">*</span></label>
											<input class="data-upload-detail-pengujian form-control" id="fileInput-detail-pengujian" name="fuploaddetailpengujian" type="file" accept="application/pdf,image/*">
											<div id="attachment-file"></div>
											<button type="button" class="button button3d btn-green upload-form">{{ trans('translate.service_upload_now') }}</button>
											<div id="attachment-file">
												{{ trans('translate.service_upload_if_form') }}
												<a class="btn btn-link" style="margin-left:-10px; height:37px; font-size: 100%;" href="{{ url('/cetakPermohonan') }}" target="_blank">{{ trans('translate.service_upload_click') }}</a>
											</div>
										</div>
										<div class="f1-buttons">
											<a href="#next" class="button button3d btn-green upload_later">{{ trans('translate.service_upload_later') }}</a>
											<div id="attachment-file">
												{{ trans('translate.service_upload_later_alt') }}
											</div>
										</div>
					            </fieldset>
								<h2>Seventh Step</h2>
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
	    	console.log(newIndex);
	    	if(!form.valid() && (newIndex > currentIndex)){ 
	    		return false;
	    	}

	    	if(newIndex == 0 || newIndex == null){ 
	    		$( '#formBTNprevious' ).hide();
	    	}
	    	if(newIndex >= 1){ 
	    		$( '#formBTNprevious' ).show();
	    	}

	    	form.trigger("focus"); 
	        form.validate().settings.ignore = ":disabled,:hidden"; 
	       	console.log(currentIndex);

	       	if(newIndex == 4){ 
	       		 $('.actions > ul > li:nth-child(2) a').text("Save");
	       	 	$("#f3-preview-1").html($("#f1-nama-perangkat").val());
				$("#f3-preview-2").html($("#f1-merek-perangkat").val());
				$("#f3-preview-3").html($("#f1-model-perangkat").val());
				$("#f3-preview-4").html($("#f1-kapasitas-perangkat").val());
					var stel = $(".chosen-select").val().join(",");
				$("#f3-preview-5").html(stel);
				// $("#f3-preview-5").html($("#f1-referensi-perangkat").val());
				$("#f3-preview-6").html($("#f1-pembuat-perangkat").val());
				$("#f3-preview-7").html($("#f1-serialNumber-perangkat").val());

				$("#f4-preview-1").html($("#f1-no-siup").val());
				$("#f4-preview-2").html($('#hide_siup_file').val());
				$("#f4-preview-3").html($("#f1-tgl-siup").val()); 
				$("#f4-preview-5").html($("#f1-sertifikat-sistem-mutu").val());
				$("#f4-preview-6").html($("#hide_sertifikat_file").val());
				$("#f4-preview-7").html($("#f1-batas-waktu").val());
				$("#f4-preview-11").html($("#hide_npwp_file").val());
				$("#f4-preview-file-ref-uji").html($(".f1-file-ref-uji").val());
				$("#f4-preview-8").html($(".f1-file-prinsipal").val());
				$("#f4-preview-12").html($(".f1-file-dll").val());
	       	}  
	        if(newIndex == 5){
				if($('#hide_cekSNjnsPengujian').val() == 1){
					alert("Perangkat[Nama, Merk, Model, Kapasitas] dan Jenis Pengujian sudah ada!"); 
					return false;
				}else{
					var formData = new FormData($('#form-permohonan')[0]);
					var error = false;
					$( "#formBTNprevious" ).hide();
					$( "#formBTNfinish" ).hide();
					$( "#formBTNnext" ).hide();

					$.ajax({
						beforeSend: function(){ 
							$("body").addClass("loading");	
						},
						type: "POST",
						url : "../submitPermohonan",
						// data: {'_token':"{{ csrf_token() }}", 'nama_pemohon':nama_pemohon, 'nama_pemohons':nama_pemohon},
						// data:new FormData($("#form-permohonan")[0]),
						data:formData,
						// dataType:'json', 
						processData: false,  
						contentType: false,
						success: function(data){
							$("body").removeClass("loading"); 
							window.open("../cetakPermohonan");

							$(".actions").hide(); 
						},
						error:function(){
							$( "#formBTNprevious" ).show();
							$( "#formBTNfinish" ).show();
							$( "#formBTNnext" ).show();
							$("body").removeClass("loading");
							error = true;
							alert("Gagal mengambil data"); 
							formWizard.steps("previous"); 
						}
					}); 
				}
	        }

	        if(newIndex == 3){
	        	$('.actions > ul > li:nth-child(2) a').text("Next");
				
				var e = document.getElementById("f1-referensi-perangkat");
				if(e.value==''){
					alert("Please Choose Test Reference");
					e.focus();
					return false;
				}

	        	var jnsPelanggan = $('#hide_jns_pengujian').val();
				var serialNumber_perangkat = $('#f1-serialNumber-perangkat').val();
				var nama_perangkat = $('#f1-nama-perangkat').val();
				var model_perangkat = $('#f1-model-perangkat').val();
				var merk_perangkat = $('#f1-merek-perangkat').val();
				var kapasitas_perangkat = $('#f1-kapasitas-perangkat').val();
				$.ajax({
					type: "POST",
					url : "../cekPermohonan",
					data: {'_token':"{{ csrf_token() }}", 'jnsPelanggan':jnsPelanggan, 'serialNumber_perangkat':serialNumber_perangkat, 'nama_perangkat':nama_perangkat, 'model_perangkat':model_perangkat, 'merk_perangkat':merk_perangkat, 'kapasitas_perangkat':kapasitas_perangkat},
					// dataType:'json',
					type:'post',
					success: function(data){
						console.log(data);
						$('#hide_cekSNjnsPengujian').val(data); 
					}
				});
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

	$("#siup-file").click(function() {
			var file = $('#hide_siup_file').val();
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
		$('#f1-nama-perangkat').val(deviceName);
	});
 </script>
@endsection