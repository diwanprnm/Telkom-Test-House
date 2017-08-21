@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.profile') }} - Telkom DDS</title>
@section('content')
<!-- Content
============================================= -->
<section id="content">

	<div class="content-wrap"> 
		<div class="container clearfix">  
			<div class="tabs tabs-bordered clearfix" id="tab-2">

							<ul class="tab-nav clearfix">
								<li><a href="#tabs-profile">{{ trans('translate.profile') }}</a></li>
								<li><a href="#tabs-company">{{ trans('translate.company') }}</a></li>
							</ul>

							<div class="tab-container">

								<div class="tab-content clearfix" id="tabs-profile">
									<form id="form" class="smart-wizard" role="form" method="POST" action="{{ url('/client/profile') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<input type="hidden" name="hide_id_user" id="hide_id_user" value="<?php echo $data['id'] ?>">

										<div class="col_full">
											<label for="register-form-name">{{ trans('translate.profile_name') }} :</label>
											<input type="text" id="username" class="form-control input-submit" name="username" placeholder="John Doe" value="<?php echo $data['name'] ?>">
											<input type="hidden" name="hide_username" id="hide_username" value="<?php echo $data['name'] ?>">
										</div>

											<div class="col_full">
												<label for="register-form-email">{{ trans('translate.profile_address') }} :</label>
												<input type="text" id="address" class="form-control input-submit" name="address" placeholder="Jln. Bandung" value="<?php echo $data['address'] ?>">
											</div>

											<div class="col_full">
												<label for="register-form-username">{{ trans('translate.profile_email') }}:</label>
												<input type="email" id="email" class="form-control input-submit" name="email" placeholder="user@mail.com" value="<?php echo $data['email'] ?>" readonly>
												<input type="hidden" name="hide_email" id="hide_email" value="<?php echo $data['email'] ?>"/>
											</div>

											<div class="col_full">
												<label for="register-form-phone">{{ trans('translate.profile_email_alternate') }} :</label>
												<input type="email" id="email2" class="form-control input-submit" name="email2" placeholder="user1@mail.com" value="<?php echo $data['email2'] ?>">
												<input type="email" id="email3" class="form-control input-submit" name="email3" placeholder="user2@mail.com" value="<?php echo $data['email3'] ?>">
												<input type="hidden" name="hide_email" id="hide_email" value="<?php echo $data['email'] ?>"/>
												<input type="hidden" name="hide_email2" id="hide_email2" value="<?php echo $data['email2'] ?>"/>
											</div>

											<div class="col_full">
												<label for="register-form-password">{{ trans('translate.profile_phone') }}:</label>
												<input type="text" id="phone" class="form-control input-submit" name="phone" placeholder="02221234689" value="<?php echo $data['phone_number'] ?>"> 
																		<input type="hidden" name="hide_phone" id="hide_phone" value="<?php echo $data['phone_number'] ?>
											</div>

											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_fax') }}:</label>
												<input type="text" id="fax" class="form-control input-submit" name="fax" placeholder="022123456" value="<?php echo $data['fax'] ?>">
													<input type="hidden" name="hide_fax" id="hide_fax" value="<?php echo $data['fax'] ?>"/>
											</div>

											<div class="col_full">
												<label for="register-form-repassword">{{ trans('translate.profile_company') }} :</label>
												<select class="form-control" id="cmb-perusahaan" name="cmb-perusahaan" disabled>
													@foreach($data_company as $item)
														<?php if($item->id == $data['company_id']){?>
															<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
														<?php }else{?>
															<option value="{{ $item->id }}">{{ $item->name }}</option>
														<?php }?>
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
												<?php if($data['picture'] == ''){?>
													<img src="{{asset('assets/images/default-profile.png')}}" width="240px">
												<?php }else{?>
													<img src="{{asset('media/user/'.$data['id'].'/'.$data['picture'])}}" width="240px">
												<?php }?>
												<input class="data-upload-user-picture" id="data-upload-user-picture" name="userPicture" type="file" accept="image/*">
												<input type="hidden" name="hide_pic_file" id="hide_pic_file" value="<?php echo $data['picture'] ?>"/>
												<div id="pic-file"><?php echo $data['picture'] ?></div>
											</div> 

											<div class="col_full nobottommargin">
												<button class="button button-3d nomargin btn-sky" id="register-form-submit" name="register-form-submit" value="register">{{ trans('translate.profile_save') }}</button>
											</div>

									</form> 
								</div>
								<div class="tab-content clearfix" id="tabs-company">
									<form id="form" class="smart-wizard" role="form" method="POST" action="{{ url('/client/company') }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<input type="hidden" name="hide_id_company" id="hide_id_company" value="<?php echo $myComp['id'] ?>"/>
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
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_name') }} : </label>
								<input type="text" id="name" class="form-control input-submit" name="name" placeholder="PT. Maju Mundur" value="<?php echo $myComp['name'] ?>">
								<input type="hidden" name="hide_name" id="hide_name" value="<?php echo $myComp['name'] ?>"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_address') }} : </label>
								<input type="text" id="address" class="form-control input-submit" name="address" placeholder="Jln. Bandung" value="<?php echo $myComp['address'] ?>">
								<input type="hidden" name="hide_address" id="hide_address" value="<?php echo $myComp['address'] ?>"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_email') }} : </label>
								<input type="email" id="email" class="form-control input-submit" name="email" placeholder="comany@mail.com" value="<?php echo $myComp['email'] ?>">
								<input type="hidden" name="hide_email" id="hide_email" value="<?php echo $myComp['email'] ?>"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_city') }} : </label>
								<input type="text" id="city" class="form-control input-submit" name="city" placeholder="Bandung" value="<?php echo $myComp['city'] ?>">
								<input type="hidden" name="hide_city" id="hide_city" value="<?php echo $myComp['city'] ?>"/>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_postal_code') }} : </label>
								<input type="text" id="postal_code" class="form-control input-submit" name="postal_code" placeholder="456123" value="<?php echo $myComp['postal_code'] ?>">
								<input type="hidden" name="hide_postal_code" id="hide_postal_code" value="<?php echo $myComp['postal_code'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_phone') }} : </label>
								<input type="text" id="phone" class="form-control input-submit" name="phone" placeholder="022123456" value="<?php echo $myComp['phone_number'] ?>">
								<input type="hidden" name="hide_phone" id="hide_phone" value="<?php echo $myComp['phone_number'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_fax') }} : </label>
								<input type="text" id="fax" class="form-control input-submit" name="fax" placeholder="022123456" value="<?php echo $myComp['fax'] ?>">
								<input type="hidden" name="hide_fax" id="hide_fax" value="<?php echo $myComp['fax'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_no_npwp') }} : </label>
								<input type="text" id="npwp_number" class="form-control input-submit" name="npwp_number" placeholder="1423456789" value="<?php echo $myComp['npwp_number'] ?>">
									<input type="hidden" name="hide_npwp_number" id="hide_npwp_number" value="<?php echo $myComp['npwp_number'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_npwp_file') }} : </label>
								<input type="file" id="npwp_file" class="form-control input-submit" name="npwp_file" placeholder="{{ trans('translate.company_npwp_file') }}" accept="application/pdf, image/*">
								<a id="npwp-file" class="btn btn-link" style="color:black !important;" onclick="downloadFileCompany('{{ $myComp['npwp_file'] }}')"> {{ $myComp['npwp_file'] }} </a>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_no_siup') }} : </label>
								<input type="text" id="siup_number" class="form-control input-submit" name="siup_number" placeholder="123456789" value="<?php echo $myComp['siup_number'] ?>">
								<input type="hidden" name="hide_siup_number" id="hide_siup_number" value="<?php echo $myComp['siup_number'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_siup_date') }} : </label>
								<?php
								if($myComp['siup_date'] == '' or $myComp['siup_date'] == '0000-00-00' or $myComp['siup_date'] == NULL){
									$timestamp = date('d-m-Y');
								}else{
									$timestamp = date('d-m-Y', strtotime($myComp['siup_date']));
								}
								?>
								<input type="text" id="siup_date" class="date form-control input-submit" name="siup_date" placeholder="dd-mm-yyyy" value="<?php echo $timestamp ?>" readonly>
								<input type="hidden" name="hide_siup_date" id="hide_siup_date" value="<?php echo $myComp['siup_date'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_siup_file') }} : </label>
								<input type="file" id="siup_file" class="form-control input-submit" name="siup_file" placeholder="{{ trans('translate.company_siup_file') }}" accept="application/pdf, image/*">
								<a id="siup-file" class="btn btn-link" style="color:black !important;" onclick="downloadFileCompany('{{ $myComp['siup_file'] }}')"> {{ $myComp['siup_file'] }} </a>
								<input type="hidden" name="hide_siup_file" id="hide_siup_file" value="<?php echo $myComp['siup_file'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_no_certificate') }} : </label>
								<input type="text" id="certificate_number" class="form-control input-submit" name="certificate_number" placeholder="123456789" value="<?php echo $myComp['qs_certificate_number'] ?>">
								<input type="hidden" name="hide_certificate_number" id="hide_certificate_number" value="<?php echo $myComp['qs_certificate_number'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_certificate_date') }} : </label>
								<?php
								if($myComp['qs_certificate_date'] == '' or $myComp['qs_certificate_date'] == '0000-00-00' or $myComp['qs_certificate_date'] == NULL){
									$timestamp = date('d-m-Y');
								}else{
									$timestamp = date('d-m-Y', strtotime($myComp['qs_certificate_date']));
								}
								?>
								<input type="text" id="certificate_date" class="date form-control input-submit" name="certificate_date" placeholder="{{ trans('translate.company_certificate_date') }}" value="<?php echo $timestamp ?>" readonly>
								<input type="hidden" name="hide_certificate_date" id="hide_certificate_date" value="<?php echo $myComp['qs_certificate_date'] ?>"/>
							</div>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.company_certificate_file') }} : </label>
								<input type="file" id="certificate_file" class="form-control input-submit" name="certificate_file" placeholder="{{ trans('translate.company_certificate_file') }}" accept="application/pdf, image/*">
								<a id="certificate-file" class="btn btn-link" style="color:black !important;" onclick="downloadFileCompany('{{ $myComp['qs_certificate_file'] }}')"> {{ $myComp['qs_certificate_file'] }} </a>
								<input type="hidden" name="hide_certificate_file" id="hide_certificate_file" value="<?php echo $myComp['qs_certificate_file'] ?>"/>
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
<script>
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
 
