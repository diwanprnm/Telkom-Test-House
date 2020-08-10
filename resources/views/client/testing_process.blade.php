@extends('layouts.client')

@section('content')
<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>Testing Process</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">Home</a></li>
					<li><a href="#">Testing</a></li>
					<li class="active">Process</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap"> 

				<div class="container"> 			
					<form role="form" action="" method="post" class="f1" id="form-permohonan" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="token" value="{{ csrf_token() }}">
					<input type="hidden" name="hide_id_user" id="hide_id_user">
					<input type="hidden" name="hide_company_id" id="hide_company_id">
					<div class="row" style="padding-right: 10px;margin-top: 20px;">
					<a data-dismiss="modal" style="cursor:pointer;"><img src="{{asset('template-assets/img/close (2).png')}}" style=" margin-top:-27px; float:right;" width="20" alt="close"></a>
					</div>
					
					<h5 class="status-hide">{{ trans('translate.service_title') }}</h5>
					<div class="f1-steps status-hide">
						<div class="f1-progress">
							<div class="f1-progress-line" data-now-value="52.22" data-number-of-steps="8" style="width: 52.22%;"></div>
						</div>
						<div class="f1-step active"></div>
						<div class="f1-step">
							<div class="f1-step-icon"><em class="fa fa-user"></em></div>
							<p>{{ trans('translate.service_application') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><em class="fa fa-user"></em></div>
							<p>{{ trans('translate.service_company') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><em class="fa fa-user"></em></div>
							<p>{{ trans('translate.service_device') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><em class="fa fa-user"></em></div>
							<p>{{ trans('translate.service_upload') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><em class="fa fa-user"></em></div>
							<p>{{ trans('translate.service_preview') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><em class="fa fa-user"></em></div>
							<p>{{ trans('translate.service_upload_form') }}</p>
						</div>
						  <div class="f1-step">
							<div class="f1-step-icon"><em class="fa fa-user"></em></div>
							<p>{{ trans('translate.service_finished') }}</p>
						</div>
					</div>
					<fieldset>
						<legend></legend>
						<div style="text-align: center">
							<img src="{{asset('assets/images/x-banner-1.png')}}" alt="gambar x-banner 1">
						</div>
						<div class="f1-buttons" style="margin-top:10px">
							<button type="button" class="btn btn-next btn-procedure">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>
					<!-- Data Pemohon-->
					<fieldset>
						<legend></legend>
						<h4>{{ trans('translate.service_application') }}</h4>
						<input type="hidden" name="hide_jns_pengujian" id="hide_jns_pengujian" value=""/>
						<div class="form-group">
							<label for="f1-nama-pemohon">{{ trans('translate.service_application_name') }}</label>
							<input type="text" name="f1-nama-pemohon" placeholder="{{ trans('translate.service_application_name') }}" class="data-pemohon f1-nama-pemohon form-control input-submit" id="f1-nama-pemohon" readonly>
						</div>
						<div class="form-group">
							<label for="f1-alamat-pemohon">{{ trans('translate.service_application_address') }}</label>
							<input type="text" name="f1-alamat-pemohon" placeholder="{{ trans('translate.service_application_address') }}" class="data-pemohon f1-alamat-pemohon form-control input-submit" id="f1-alamat-pemohon" readonly>
						</div>
						<div class="form-group">
							<label for="f1-telepon-pemohon">{{ trans('translate.service_application_phone') }}</label>
							<input type="text" name="f1-telepon-pemohon" placeholder="{{ trans('translate.service_application_phone') }}" class="data-pemohon f1-telepon-pemohon form-control input-submit" id="f1-telepon-pemohon" readonly>
						</div>
						<div class="form-group">
							<label for="f1-faksimile-pemohon">{{ trans('translate.service_application_fax') }}</label>
							<input type="text" name="f1-faksimile-pemohon" placeholder="{{ trans('translate.service_application_fax') }}" class="data-pemohon f1-faksimile-pemohon form-control input-submit" id="f1-faksimile-pemohon" readonly>
						</div>
						<div class="form-group">
							<label for="f1-email-pemohon">{{ trans('translate.service_application_email') }}</label>
							<input type="text" name="f1-email-pemohon" placeholder="{{ trans('translate.service_application_email') }}" class="data-pemohon f1-email-pemohon form-control input-submit" id="f1-email-pemohon" readonly>
						</div>
						<div class="form-group">
							<label for="f1-email-pemohon-alternatif">{{ trans('translate.profile_email_alternate') }}</label>
							<input type="text" name="f1-email-pemohon1" placeholder="{{ trans('translate.profile_email_alternate') }} 1" class="data-pemohon f1-email-pemohon1 form-control input-submit" id="f1-email-pemohon1" readonly>
							<input type="text" name="f1-email-pemohon2" placeholder="{{ trans('translate.profile_email_alternate') }} 2" class="data-pemohon f1-email-pemohon2 form-control input-submit" id="f1-email-pemohon2" readonly>
						</div>
						<div class="f1-buttons">
							<button type="button" class="btn btn-previous btn-procedure2">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>
					<!-- Data Perusahaan-->
					<fieldset>
						<legend></legend>
						<h4>{{ trans('translate.service_company') }}</h4>
						<div class="form-group">
							<label for="f1-jns-perusahaan">{{ trans('translate.service_company_type') }} : </label>
							<input type="radio" name="jns_perusahaan" class="rad-jns_perusahaan" id="rad-jns_perusahaan1" value="Agen" checked>{{ trans('translate.service_company_agent') }}
							<input type="radio" name="jns_perusahaan" class="rad-jns_perusahaan" id="rad-jns_perusahaan2" value="Pabrikan">{{ trans('translate.service_company_branch') }}
							<input type="radio" name="jns_perusahaan" class="rad-jns_perusahaan" id="rad-jns_perusahaan3" value="Perorangan">{{ trans('translate.service_company_individual') }}
						</div>
						<div class="form-group">
							<label for="f1-nama-perusahaan">{{ trans('translate.service_company_name') }}</label>
							<input type="text" name="f1-nama-perusahaan" placeholder="{{ trans('translate.service_company_name') }}" class="data-perusahaan f1-nama-perusahaan form-control input-submit" id="f1-nama-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-alamat-perusahaan">{{ trans('translate.service_company_address') }}</label>
							<input type="text" name="f1-alamat-perusahaan" placeholder="{{ trans('translate.service_company_address') }}" class="data-perusahaan f1-alamat-perusahaan form-control input-submit" id="f1-alamat-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-telepon-perusahaan">{{ trans('translate.service_company_phone') }}</label>
							 <input type="text" name="f1-telepon-perusahaan" placeholder="{{ trans('translate.service_company_phone') }}" class="data-perusahaan f1-telepon-perusahaan form-control input-submit" id="f1-telepon-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-faksimile-perusahaan">{{ trans('translate.service_company_fax') }}</label>
							 <input type="text" name="f1-faksimile-perusahaan" placeholder="{{ trans('translate.service_company_fax') }}" class="data-perusahaan f1-faksimile-perusahaan form-control input-submit" id="f1-faksimile-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-email-perusahaan">{{ trans('translate.service_company_email') }}</label>
							 <input type="text" name="f1-email-perusahaan" placeholder="{{ trans('translate.service_company_email') }}" class="data-perusahaan f1-email-perusahaan form-control input-submit" id="f1-email-perusahaan" readonly>
						</div>
						<div class="form-group" style="margin-top:-30px; margin-bottom:30px; height:25px; font-size: 80%;">
							{{ trans('translate.service_company_confirm') }}
						</div>
						<div class="f1-buttons">
							<button type="button" class="btn btn-previous">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>
					<!-- Data Perangkat-->
					<fieldset>
						<legend></legend>
						<h4>{{ trans('translate.service_device') }}</h4>
						<div class="form-group">
							<label for="f1-nama-perangkat">{{ trans('translate.service_device_equipment') }}</label>
							<input type="text" name="f1-nama-perangkat" placeholder="{{ trans('translate.service_device_equipment') }}" class="data-perangkat f1-nama-perangkat form-control input-submit" id="f1-nama-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-merek-perangkat">{{ trans('translate.service_device_mark') }}</label>
							<input type="text" name="f1-merek-perangkat" placeholder="{{ trans('translate.service_device_mark') }}" class="data-perangkat f1-merek-perangkat form-control input-submit" id="f1-merek-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-kapasitas-perangkat">{{ trans('translate.service_device_capacity') }}</label>
							<input type="text" name="f1-kapasitas-perangkat" placeholder="{{ trans('translate.service_device_capacity') }}" class="data-perangkat f1-kapasitas-perangkat form-control input-submit" id="f1-kapasitas-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-pembuat-perangkat">{{ trans('translate.service_device_manufactured_by') }}</label>
							<input type="text" name="f1-pembuat-perangkat" placeholder="{{ trans('translate.service_device_manufactured_by') }}." class="data-perangkat f1-pembuat-perangkat form-control input-submit" id="f1-pembuat-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-serialNumber-perangkat">{{ trans('translate.service_device_serial_number') }}</label>
							<input type="text" name="f1-serialNumber-perangkat" placeholder="{{ trans('translate.service_device_serial_number') }}" class="data-perangkat f1-serialNumber-perangkat form-control input-submit" id="f1-serialNumber-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-model-perangkat">{{ trans('translate.service_device_model') }}</label>
							<input type="text" name="f1-model-perangkat" placeholder="{{ trans('translate.service_device_model') }}" class="data-perangkat f1-model-perangkat form-control input-submit" id="f1-model-perangkat">
						</div>
						<input type="hidden" class="data-perangkat f1-jns-referensi-perangkat" id="f1-jns-referensi-perangkat" name="f1-jns-referensi-perangkat">
						<div class="form-group cmb-ref-perangkat">
							<label for="f1-referensi-perangkat">{{ trans('translate.service_device_test_reference') }}</label>
							<select class="form-control" id="f1-cmb-ref-perangkat" name="f1-cmb-ref-perangkat">
									<option value="">{{ trans('translate.service_device_test_reference') }}</option>
								@foreach($data_stels as $item)
									<option value="{{ $item->code }}">{{ $item->code }} || {{ $item->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group txt-ref-perangkat">
							<label for="f1-referensi-perangkat">{{ trans('translate.service_device_test_reference') }}</label>
							<input type="text" name="f1-referensi-perangkat" placeholder="{{ trans('translate.service_device_test_reference') }}" class="data-perangkat f1-referensi-perangkat form-control input-submit" id="f1-referensi-perangkat">
						</div>
						<div class="f1-buttons">
							<button type="button" class="btn btn-previous">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next cek-SN-jnsPengujian">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>
					<!-- upload berkas-->
					<fieldset>
						<legend></legend>
						<h4>{{ trans('translate.service_upload') }}</h4>
						<div class="form-group">
							<label>{{ trans('translate.service_upload_siupp') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-siupp" id="fileInput-SIUPP" name="fuploadsiupp" type="file" accept="application/pdf,image/*">
							<input type="hidden" name="hide_siupp_file" id="hide_siupp_file" value=""/>
							<a id="siupp-file" class="btn btn-link" style="color:black !important;" ></a>
						</div>
						<div class="form-group" style="margin-bottom:0.01%">
							<label class="sr-only" for="f1-no-siupp">{{ trans('translate.service_upload_siupp_no') }}</label>
							<input type="text" name="f1-no-siupp" placeholder="{{ trans('translate.service_upload_siupp_no') }}" class="data-upload-berkas f1-no-siupp form-control input-submit" id="f1-no-siupp">
						</div>
						<div class="form-group">
							<label class="sr-only" for="f1-tgl-siupp">{{ trans('translate.service_upload_siupp_date') }}</label>
							<input type="hidden" name="f1-tgl-siupp" placeholder="{{ trans('translate.service_upload_siupp_date') }}" class="data-upload-berkas f1-tgl-siupp form-control input-submit" id="f1-tgl-siupp" readonly>
							<div class="col-xs-1 selectContainer">
								D: <select name="daySIUPP" id="daySIUPP" class="form-control" style="width:auto;" onchange="setDays(monthSIUPP,this,yearSIUPP,1)">
									@for($i = 1;$i <= 31; $i++)
										<?php
											if($i < 10){
												$i = '0'.$i;
											}
										?>
										<option value="{{$i}}">{{$i}}</option>
									@endfor
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								M: <select name="monthSIUPP" id="monthSIUPP" class="form-control" style="width:auto;" onchange="setDays(this,daySIUPP,yearSIUPP,1)">
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								Y: <select name="yearSIUPP" id="yearSIUPP" class="form-control" style="width:auto;" onchange="setDays(monthSIUPP,daySIUPP,this,1)">
									@for($i = date('Y')+100;$i >= 1900; $i--)
										@if($i == date('Y'))
											<option value="{{$i}}" selected>{{$i}}</option>
										@else
											<option value="{{$i}}">{{$i}}</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="form-group col-xs-12" style="margin-top:35px">
							<label>{{ trans('translate.service_upload_certificate') }}<span class="text-danger">*</span></label>
							<input type="text" name="f1-sertifikat-sistem-mutu" placeholder="{{ trans('translate.service_upload_certificate') }}" class="data-upload-berkas f1-sertifikat-sistem-mutu form-control input-submit" id="f1-sertifikat-sistem-mutu">
						</div>
						<div class="form-group col-xs-12" style="margin-bottom:0.01%">
							<label>{{ trans('translate.service_upload_certificate_file') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-lampiran" id="fileInput-lampiran" name="fuploadlampiran" type="file" accept="application/pdf,image/*">
							<input type="hidden" name="hide_sertifikat_file" id="hide_sertifikat_file" value=""/>
							<a id="sertifikat-file" class="btn btn-link" style="color:black !important;" ></a>
						</div>
						<div class="form-group">
							<label class="sr-only" for="f1-batas-waktu">{{ trans('translate.service_upload_certificate_date') }}</label>
							<input type="hidden" name="f1-batas-waktu" placeholder="{{ trans('translate.service_upload_certificate_date') }}" class="data-upload-berkas f1-batas-waktu form-control input-submit" id="f1-batas-waktu" readonly>
							<div class="col-xs-1 selectContainer">
								D: <select name="daySerti" id="daySerti" class="form-control" style="width:auto;" onchange="setDays(monthSerti,this,yearSerti,0)">
									@for($i = 1;$i <= 31; $i++)
										<?php
											if($i < 10){
												$i = '0'.$i;
											}
										?>
										<option value="{{$i}}">{{$i}}</option>
									@endfor
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								M: <select name="monthSerti" id="monthSerti" class="form-control" style="width:auto;" onchange="setDays(this,daySerti,yearSerti,0)">
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								Y: <select name="yearSerti" id="yearSerti" class="form-control" style="width:auto;" onchange="setDays(monthSerti,daySerti,this,0)">
									@for($i = date('Y')+100;$i >= 1900; $i--)
										@if($i == date('Y'))
											<option value="{{$i}}" selected>{{$i}}</option>
										@else
											<option value="{{$i}}">{{$i}}</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="form-group col-xs-12" style="margin-top:35px">
							<label>{{ trans('translate.service_upload_npwp') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-NPWP" id="fileInput-NPWP" name="fuploadnpwp" type="file" accept="application/pdf,image/*">
							<input type="hidden" name="hide_npwp_file" id="hide_npwp_file" value=""/>
							<a id="npwp-file" class="btn btn-link" style="color:black !important;" ></a>
						</div>
						<div class="form-group col-xs-12">
							<label>{{ trans('translate.service_upload_reference_test') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-ref-uji" id="fileInput-ref-uji" name="fuploadrefuji" type="file" accept="application/pdf,image/*">
							<div id="ref-uji-file"></div>
						</div>
						<div class="dv-srt-dukungan-prinsipal">
							<div class="form-group col-xs-12">
								<label>{{ trans('translate.service_upload_support_principals') }}<span class="text-danger">*</span></label>
								<input class="data-upload-berkas f1-file-prinsipal" id="fileInput-prinsipal" name="fuploadprinsipal" type="file" accept="application/pdf,image/*">
								<div id="prinsipal-file"></div>
							</div>
						</div>
						<div class="dv-srt-sp3">
							<div class="form-group col-xs-12">
								<label>{{ trans('translate.service_upload_sp3') }}<span class="text-danger">*</span></label>
								<input class="data-upload-berkas f1-file-sp3" id="fileInput-sp3" name="fuploadsp3" type="file" accept="application/pdf,image/*">
								<div id="sp3-file"></div>
							</div>
						</div>
						<div class="f1-buttons col-xs-12">
							<button type="button" class="btn btn-previous">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>
					<!-- Preview-->
					<fieldset>
						<legend></legend>
						<input type="hidden" name="hide_cekSNjnsPengujian" id="hide_cekSNjnsPengujian">
						<h4>{{ trans('translate.service_preview') }}</h4>
						<h3>{{ trans('translate.service_application') }}</h3>
						<table class="table table-striped">
							<caption></caption>
							<thead class="hidden">
								<tr>
									<th scope="col">-</th>
								</tr>
							</thead>
							<tr>
								<td>{{ trans('translate.service_application_name') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-1"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_application_address') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-2"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_application_phone') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-3"></div></td>
								<td colspan=2></td>
								<td>{{ trans('translate.service_application_fax') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-4"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_application_email') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-5"></div></td>
							</tr>
						</table>
						<h3>{{ trans('translate.service_company') }}</h3>
						<div id="f2-preview-6"></div>
						<table class="table table-striped">
							<caption></caption>
							<thead class="hidden">
								<tr>
									<th scope="col">-</th>
								</tr>
							</thead>
							<tr>
								<td>{{ trans('translate.service_company_name') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-1"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_company_address') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-2"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_company_phone') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-3"></div></td>
								<td colspan=2></td>
								<td>{{ trans('translate.service_company_fax') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-4"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_company_email') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-5"></div></td>
							</tr>
						</table>
						<h3 id="f5-jns-pengujian" class="f5-jns-pengujian">{{ trans('translate.service_preview_exam_type') }} : </h3>
						<br>
						<h3>{{ trans('translate.service_device') }}</h3>
						<table class="table table-striped">
							<caption></caption>
							<thead class="hidden">
								<tr>
									<th scope="col">-</th>
								</tr>
							</thead>
							<tr>
								<td>{{ trans('translate.service_device_equipment') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-1"></div></td>
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
						<table class="table table-striped">
							<caption></caption>
							<thead class="hidden">
								<tr>
									<th scope="col">-</th>
								</tr>
							</thead>
							<tr>
								<td>{{ trans('translate.service_upload_siupp') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-1"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_siupp_no') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-2"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_siupp_date') }}</td>
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
							<tr class="dv-srt-dukungan-prinsipal">
								<td>{{ trans('translate.service_upload_support_principals') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-8"></div></td>
							</tr>
							<tr class="dv-srt-sp3">
								<td>{{ trans('translate.service_upload_sp3') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-file-sp3"></div></td>
							</tr>
						</table>
						<div class="f1-buttons">
							<button type="button" class="btn btn-previous">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next save-permohonan">{{ trans('translate.service_save') }}</button>
						</div>
					</fieldset>
					<!-- upload detail pengujian-->
					<fieldset>
						<legend></legend>
						<h4>{{ trans('translate.service_upload_form') }}</h4>
						<div class="form-group">
							<label>{{ trans('translate.service_upload_now') }}<span class="text-danger">*</span></label>
							<input class="data-upload-detail-pengujian" id="fileInput-detail-pengujian" name="fuploaddetailpengujian" type="file" accept="application/pdf,image/*">
							<div id="attachment-file"></div>
							<button type="button" class="btn btn-next upload-form">{{ trans('translate.service_upload_now') }}</button>
							<div id="attachment-file">
								{{ trans('translate.service_upload_if_form') }}
								<a class="btn btn-link" style="margin-left:-10px; height:37px; color:black !important; font-size: 100%;" href="{{ url('/cetakPermohonan') }}" target="_blank">{{ trans('translate.service_upload_click') }}</a>
							</div>
						</div>
						<div class="f1-buttons">
							<button type="button" class="btn btn-next">{{ trans('translate.service_upload_later') }}</button>
							<div id="attachment-file">
								{{ trans('translate.service_upload_later_alt') }}
							</div>
						</div>
					</fieldset>
					<!-- submit-->
					<fieldset>
						<legend></legend>
						<h4 class="judulselesai">{{ trans('translate.service_thanks') }}</h4>
						<div class="f1-buttons">
							<button type="button" class="btn btn-submit">OK</button>
						</div>
					</fieldset>
				</form>
				</div>



			</div>


		</section><!-- #content end -->


@endsection
@section('content_js')

@endsection
