@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.profile') }} - Telkom DDS</title>
@php 
	$ADDRESS_STRING = 'address'; 
	$EMAIL_STRING = 'email'; 
	$PHONE_NUMBER_STRING = 'phone_number'; 
	$PICTURE_STRING = 'picture'; 
	$SIUP_DATE_STRING = 'siup_date';
	$dmY_STRING = 'd-m-Y';
	$qs_certificate_date_STRING = 'qs_certificate_date';
@endphp

@section('content')
<!-- Content
============================================= -->
<section id="content">

	<div class="content-wrap"> 
		<div class="container clearfix">  
			<div class="clearfix" id="tab-2">

							<ul class="nav nav-tabs clearfix">
								<li class="{{ $tabs == 'profile' ? 'active' : '' }}"><a href="#tabs-profile" data-toggle="tab"><strong>{{ trans('translate.profile') }}</strong></a></li>
								<li class="{{ $tabs == 'company' ? 'active' : '' }}"><a href="#tabs-company" data-toggle="tab"><strong>{{ trans('translate.company') }}</strong></a></li>
							</ul>

							<div class="tab-content">

								<div id="tabs-profile" class="clearfix tab-pane fade {{ $tabs == 'profile' ? 'in active' : '' }}">
									<form id="form" class="smart-wizard" role="form" method="POST" action="{{ url('/client/profile') }}" enctype="multipart/form-data" aria-label = "Form Profile">
										{{ csrf_field() }}
										<input type="hidden" name="hide_id_user" id="hide_id_user" value="@php echo $data['id'] @endphp">

										<br>
										<div class="col_full">
											<label for="register-form-name">{{ trans('translate.profile_name') }} :</label>
											<input type="text" id="username" class="form-control input-submit" name="username" placeholder="John Doe" value="@php echo $data['name'] @endphp">
											<input type="hidden" name="hide_username" id="hide_username" value="@php echo $data['name'] @endphp">
										</div>

											<div class="col_full">
												<label for="register-form-email">{{ trans('translate.profile_address') }} :</label>
												<input type="text" id="address" class="form-control input-submit" name="address" placeholder="Jln. Bandung" value="@php echo $data[$ADDRESS_STRING] @endphp">
											</div>

											<div class="col_full">
												<label for="register-form-username">{{ trans('translate.profile_email') }}:</label>
												<input type="email" id="email" class="form-control input-submit" name="email" placeholder="user@mail.com" value="@php echo $data[$EMAIL_STRING] @endphp" readonly>
												<input type="hidden" name="hide_email" id="hide_email" value="@php echo $data[$EMAIL_STRING] @endphp"/>
											</div>

											<div class="col_full">
												<label for="register-form-phone">{{ trans('translate.profile_email_alternate') }} :</label>
												<input type="email" id="email2" class="form-control input-submit" name="email2" placeholder="user1@mail.com" value="@php echo $data['email2'] @endphp">
												<input type="email" id="email3" class="form-control input-submit" name="email3" placeholder="user2@mail.com" value="@php echo $data['email3'] @endphp">
												<input type="hidden" name="hide_email" id="hide_email" value="@php echo $data[$EMAIL_STRING] @endphp"/>
												<input type="hidden" name="hide_email2" id="hide_email2" value="@php echo $data['email2'] @endphp"/>
											</div>

											<div class="col_full">
												<label for="register-form-password">{{ trans('translate.profile_phone') }}:</label>
												<input type="text" id="phone" class="form-control input-submit" name="phone" placeholder="02221234689" value="@php echo $data[$PHONE_NUMBER_STRING] @endphp"> 
																		<input type="hidden" name="hide_phone" id="hide_phone" value="@php echo $data[$PHONE_NUMBER_STRING] @endphp
											</div>

											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_fax') }}:</label>
												<input type="text" id="fax" class="form-control input-submit" name="fax" placeholder="022123456" value="@php echo $data['fax'] @endphp">
													<input type="hidden" name="hide_fax" id="hide_fax" value="@php echo $data['fax'] @endphp"/>
											</div>

											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_company') }} :</label>
												<select class="form-control" id="cmb-perusahaan" name="cmb-perusahaan" disabled>
													@foreach($data_company as $item)
														@php if($item->id == $data['company_id']){@endphp
															<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
														@php }else{@endphp
															<option value="{{ $item->id }}">{{ $item->name }}</option>
														@php }@endphp
													@endforeach
												</select>
											</div> 

											<h3>{{ trans('translate.profile_title_password') }}</h3>
											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_old_password') }} : </label>
												<input type="password" id="currPass" class="form-control input-submit" name="currPass" data-toggle="password" placeholder="p@ssw0rd" required>
											</div> 
											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_new_password') }} : </label>
												<input type="password" id="newPass" class="form-control input-submit" name="newPass" data-toggle="password" placeholder="p@ssw0rd">
											</div>  
											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_confirm_password') }} : </label>
												<input type="password" id="confnewPass" class="form-control input-submit" name="confnewPass" data-toggle="password" placeholder="p@ssw0rd">
											</div>  
											<h3>{{ trans('translate.register_picture') }}</h3>
											<hr>
											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_picture') }} : </label>
												@php if($data[$PICTURE_STRING] == ''){@endphp
													<img src="{{asset('assets/images/default-profile.png')}}" width="240px" alt="gambar default profil picture">
												@php }else{@endphp
													<img src="{{asset('media/user/'.$data['id'].'/'.$data[$PICTURE_STRING])}}" width="240px" alt="gambar profile picture">
												@php }@endphp
												<input class="data-upload-user-picture" id="data-upload-user-picture" name="userPicture" type="file" accept="image/*">
												<input type="hidden" name="hide_pic_file" id="hide_pic_file" value="@php echo $data[$PICTURE_STRING] @endphp"/>
												<div id="pic-file">@php echo $data[$PICTURE_STRING] @endphp</div>
											</div> 

											<div class="col_full nobottommargin">
												<button class="button button-3d nomargin btn-sky" id="register-form-submit" name="register-form-submit" value="register">{{ trans('translate.profile_save') }}</button>
											</div>

									</form> 
								</div>
								<div id="tabs-company" class="clearfix tab-pane fade {{ $tabs == 'company' ? 'in active' : '' }}">
									<form id="form" class="smart-wizard" role="form" method="POST" action="{{ url('/client/company') }}" enctype="multipart/form-data" aria-label="Company Form">
							{{ csrf_field() }}
							<input type="hidden" name="hide_id_company" id="hide_id_company" value="@php echo $myComp['id'] @endphp"/>
							@if (Session::get('error_company'))
								<div class="alert alert-error alert-danger">
									{{ Session::get('error_company') }}
								</div>
							@endif
							
							@if (Session::get('message_company'))
								<div class="alert alert-info">
									{{ Session::get('message_company') }}
								</div>
							@endif
							<br>			
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_name') }} : </label>
								<input type="text" id="name" class="form-control input-submit" name="name" placeholder="PT. Maju Mundur" value="@php echo $myComp['name'] @endphp">
								<input type="hidden" name="hide_name" id="hide_name" value="@php echo $myComp['name'] @endphp"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_address') }} : </label>
								<input type="text" id="address" class="form-control input-submit" name="address" placeholder="Jln. Bandung" value="@php echo $myComp[$ADDRESS_STRING] @endphp">
								<input type="hidden" name="hide_address" id="hide_address" value="@php echo $myComp[$ADDRESS_STRING] @endphp"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_plg_id') }} : </label>
								<input type="text" id="plg_id" class="form-control input-submit" name="plg_id" placeholder="012345678" value="@php echo $myComp['plg_id'] @endphp">
								<input type="hidden" name="hide_plg_id" id="hide_plg_id" value="@php echo $myComp['plg_id'] @endphp"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_nib') }} : </label>
								<input type="text" id="nib" class="form-control input-submit" name="nib" placeholder="012345678" value="@php echo $myComp['nib'] @endphp">
								<input type="hidden" name="hide_nib" id="hide_nib" value="@php echo $myComp['nib'] @endphp"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_email') }} : </label>
								<input type="email" id="email" class="form-control input-submit" name="email" placeholder="comany@mail.com" value="@php echo $myComp[$EMAIL_STRING] @endphp">
								<input type="hidden" name="hide_email" id="hide_email" value="@php echo $myComp[$EMAIL_STRING] @endphp"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_city') }} : </label>
								<input type="text" id="city" class="form-control input-submit" name="city" placeholder="Bandung" value="@php echo $myComp['city'] @endphp">
								<input type="hidden" name="hide_city" id="hide_city" value="@php echo $myComp['city'] @endphp"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_postal_code') }} : </label>
								<input type="text" id="postal_code" class="form-control input-submit" name="postal_code" placeholder="456123" value="@php echo $myComp['postal_code'] @endphp">
								<input type="hidden" name="hide_postal_code" id="hide_postal_code" value="@php echo $myComp['postal_code'] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_phone') }} : </label>
								<input type="text" id="phone" class="form-control input-submit" name="phone" placeholder="022123456" value="@php echo $myComp[$PHONE_NUMBER_STRING] @endphp">
								<input type="hidden" name="hide_phone" id="hide_phone" value="@php echo $myComp[$PHONE_NUMBER_STRING] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_fax') }} : </label>
								<input type="text" id="fax" class="form-control input-submit" name="fax" placeholder="022123456" value="@php echo $myComp['fax'] @endphp">
								<input type="hidden" name="hide_fax" id="hide_fax" value="@php echo $myComp['fax'] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_no_npwp') }} : </label>
								<input type="text" id="npwp_number" class="form-control input-submit" name="npwp_number" placeholder="1423456789" value="@php echo $myComp['npwp_number'] @endphp">
									<input type="hidden" name="hide_npwp_number" id="hide_npwp_number" value="@php echo $myComp['npwp_number'] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_npwp_file') }} : </label>
								<input type="file" id="npwp_file" class="form-control input-submit" name="npwp_file" placeholder="{{ trans('translate.company_npwp_file') }}" accept="application/pdf, image/*">
								<a id="npwp-file" class="btn btn-link" style="color:black !important;" onclick="downloadFileCompany('{{ $myComp['npwp_file'] }}')"> {{ $myComp['npwp_file'] }} </a>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_no_siup') }} : </label>
								<input type="text" id="siup_number" class="form-control input-submit" name="siup_number" placeholder="123456789" value="@php echo $myComp['siup_number'] @endphp">
								<input type="hidden" name="hide_siup_number" id="hide_siup_number" value="@php echo $myComp['siup_number'] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_siup_date') }} : </label>
								@php
								if($myComp[$SIUP_DATE_STRING] == '' || $myComp[$SIUP_DATE_STRING] == '0000-00-00' || $myComp[$SIUP_DATE_STRING] == NULL){
									$timestamp = date($dmY_STRING);
								}else{
									$timestamp = date($dmY_STRING, strtotime($myComp[$SIUP_DATE_STRING]));
								}
								@endphp
								<input type="text" id="siup_date" class="date form-control input-submit" name="siup_date" placeholder="dd-mm-yyyy" value="@php echo $timestamp @endphp" readonly>
								<input type="hidden" name="hide_siup_date" id="hide_siup_date" value="@php echo $myComp[$SIUP_DATE_STRING] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_siup_file') }} : </label>
								<input type="file" id="siup_file" class="form-control input-submit" name="siup_file" placeholder="{{ trans('translate.company_siup_file') }}" accept="application/pdf, image/*">
								<a id="siup-file" class="btn btn-link" style="color:black !important;" onclick="downloadFileCompany('{{ $myComp['siup_file'] }}')"> {{ $myComp['siup_file'] }} </a>
								<input type="hidden" name="hide_siup_file" id="hide_siup_file" value="@php echo $myComp['siup_file'] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_no_certificate') }} : </label>
								<input type="text" id="certificate_number" class="form-control input-submit" name="certificate_number" placeholder="123456789" value="@php echo $myComp['qs_certificate_number'] @endphp">
								<input type="hidden" name="hide_certificate_number" id="hide_certificate_number" value="@php echo $myComp['qs_certificate_number'] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_certificate_date') }} : </label>
								@php
								if($myComp[$qs_certificate_date_STRING] == '' || $myComp[$qs_certificate_date_STRING] == '0000-00-00' || $myComp[$qs_certificate_date_STRING] == NULL){
									$timestamp = date($dmY_STRING);
								}else{
									$timestamp = date($dmY_STRING, strtotime($myComp[$qs_certificate_date_STRING]));
								}
								@endphp
								<input type="text" id="certificate_date" class="date form-control input-submit" name="certificate_date" placeholder="{{ trans('translate.company_certificate_date') }}" value="@php echo $timestamp @endphp" readonly>
								<input type="hidden" name="hide_certificate_date" id="hide_certificate_date" value="@php echo $myComp[$qs_certificate_date_STRING] @endphp"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_certificate_file') }} : </label>
								<input type="file" id="certificate_file" class="form-control input-submit" name="certificate_file" placeholder="{{ trans('translate.company_certificate_file') }}" accept="application/pdf, image/*">
								<a id="certificate-file" class="btn btn-link" style="color:black !important;" onclick="downloadFileCompany('{{ $myComp['qs_certificate_file'] }}')"> {{ $myComp['qs_certificate_file'] }} </a>
								<input type="hidden" name="hide_certificate_file" id="hide_certificate_file" value="@php echo $myComp['qs_certificate_file'] @endphp"/>
							</div> 

							<div class="col_full nobottommargin">
								<button class="button button-3d nomargin btn-sky" id="register-form-submit" name="register-form-submit" value="register">{{ trans('translate.company_save') }}</button>
							</div>

						</form>
								</div>

							</div>

						</div>
			 
		</div> 
	</div>

</section><!-- #content end --> 

@endsection

@section('content_js')
<script type="text/javascript">
 	function downloadFileCompany(file){
		var path = "{{ URL::asset('media/company') }}";
		var company_id = $('#hide_id_company').val();
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
</script>
@endsection
 
