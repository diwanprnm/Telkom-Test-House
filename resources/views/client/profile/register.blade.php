@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.register') }} - Telkom DDS</title>
@section('content')
<!-- Content
============================================= -->
<section id="content">

	<div class="content-wrap"> 
		<div class="container clearfix">  
		@if(session()->has('error'))
						<div class="alert alert-info" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<div style="text-align:center">
							 error
							</div>
						</div>
					@endif
			<div class="tab-content clearfix" id="tab-register">
				<div class="panel panel-default nobottommargin">
					<div class="panel-body" style="padding: 40px;">
						<h3>{{ trans('translate.register_user') }}</h3>

						<form id="form-profile" class="smart-wizard" role="form" method="POST" action="{{ url('/client/register') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="hide_is_company_too" id="hide_is_company_too" value="{{ old('hide_is_company_too') }}"/>
							<div class="col_full">
								<label for="register-form-name">{{ trans('translate.register_name') }} : *</label>
								<input type="text" id="username" class="form-control input-submit" name="username" placeholder="John Doe" value="{{ old('username') }}" required>
							</div>

							<div class="col_full">
								<label for="register-form-email">{{ trans('translate.register_address') }} : *</label>
								<input type="text" id="address" class="form-control input-submit" name="address" placeholder="Jl. Gegerkalong Hilir, Sukarasa, Sukasari, Kota Bandung, Jawa Barat 40152" value="{{ old('address') }}" required>
							</div>

							<div class="col_full">
								<label for="register-form-username">{{ trans('translate.register_email') }}: *</label>
							<input type="email" id="email" class="form-control input-submit" name="email" placeholder="john@mail.com" value="{{ old('email') }}" required>
							</div>
							@if(!empty(Session::get('error_email')) && (Session::get('error_email') == 1))
								<div class="col_full">
									<label for="register-form-username">{{ trans('translate.register_email_required') }}</label>
								</div>
							@endif
							@if(!empty(Session::get('error_email')) && (Session::get('error_email') == 2))
								<div class="col_full">
									<label for="register-form-username">{{ trans('translate.register_email_exists') }}</label>
								</div>
							@endif

							<div class="col_full">
								<label for="register-form-phone">{{ trans('translate.register_email_alternate') }} :</label>
							<input type="email" id="email2" class="form-control input-submit" name="email2" placeholder="john_2@mail.com" value="{{ old('email2') }}">
								<input type="email" id="email3" class="form-control input-submit" name="email3" placeholder="John.doe@mail.com" value="{{ old('email3') }}" style="margin-top:15px">
							</div>

							<div class="col_full">
								<label for="register-form-password">{{ trans('translate.register_phone') }}: *</label>
								<input type="text" id="phone" class="form-control input-submit" name="phone" placeholder="0811234599" value="{{ old('phone') }}" required>
							</div>

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.register_fax') }}:</label>
								<input type="text" id="fax" class="form-control input-submit" name="fax" placeholder="0221234567" value="{{ old('fax') }}">
							</div>

							<h3>{{ trans('translate.register_password') }}</h3>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.register_password') }} : *</label>
								<input type="password" id="newPass" class="form-control input-submit" name="newPass" placeholder="p@ssw0rd" data-toggle="password" required>
							</div> 
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.register_confirm_password') }} : *</label>
								<input type="password" id="confnewPass" class="form-control input-submit" name="confnewPass" placeholder="p@ssw0rd" id="password" data-toggle="password" required>
							</div>
							@if(!empty(Session::get('error_newpass')) && (Session::get('error_newpass') == 2))
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.register_password_not_match') }}</label>
								</div> 
							@endif							
							<h3>{{ trans('translate.register_picture') }}</h3>
							<hr>
							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.register_picture') }} : </label>
								<input class="data-upload-user-picture" id="data-upload-user-picture" name="userPicture" type="file" accept="image/*">
							</div> 
							@if(!empty(Session::get('error_img_type')) && (Session::get('error_img_type') == 1))
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.register_image_type') }}</label>
								</div> 
							@endif

							<div class="col_full">
								<label for="register-form-repassword">{{ trans('translate.register_company') }}:</label>
								<select class="form-control input-submit" id="cmb-perusahaan" name="cmb-perusahaan" required>
										<option value="">{{ trans('translate.register_company_select') }}</option>
									@foreach($data as $item)
										<option value="{{ $item->id }}" @if(old('cmb-perusahaan') == $item->id) {{ 'selected' }} @endif>{{ $item->name }}</option>
									@endforeach
								</select>
								<div class="form-group" style="margin-bottom:-5px; height:25px; font-size: 70%;">
									{{ trans('translate.register_message_company') }}
								</div>
							</div>

							<div class="col_full">
								<a class="button button-3d button-green nomargin pull-right" value="register" id="btn-new-company">{{ trans('translate.company_form_button') }}</a>
							</div>

							<div class="new-company-form" style="display: none;">
								<h3>{{ trans('translate.company') }}</h3>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_name') }} : *</label>
									<input disabled="disabled" type="text" id="comp_name" class="form-control input-submit new-company-field" name="comp_name" placeholder="PT. Maju Mundur" value="{{ old('comp_name') }}" required>
								</div>

								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_address') }} : *</label>
									 <input disabled="disabled" type="text" id="comp_address" class="form-control input-submit new-company-field" name="comp_address" placeholder="Jln. Bandung" value="{{ old('comp_address') }}" required>
								</div>

								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_email') }} : *</label>
									<input disabled="disabled" type="email" id="comp_email" class="form-control input-submit new-company-field" name="comp_email" placeholder="company@mail.com" value="{{ old('comp_email') }}" required>
								</div>

								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_city') }} : *</label>
									<input disabled="disabled" type="text" id="comp_city" class="form-control input-submit new-company-field" name="comp_city" placeholder="Bandung" value="{{ old('comp_city') }}" required>
								</div>

								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_postal_code') }} : </label>
									<input disabled="disabled" type="text" id="comp_postal_code" class="form-control input-submit new-company-field" name="comp_postal_code" placeholder="123456" value="{{ old('comp_postal_code') }}">
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_phone') }} : </label>
									<input disabled="disabled" type="text" id="comp_phone_number" class="form-control input-submit new-company-field" name="comp_phone_number" placeholder="08123456789" value="{{ old('comp_phone_number') }}" required>
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_fax') }} : </label>
									<input disabled="disabled" type="text" id="comp_fax" class="form-control input-submit new-company-field" name="comp_fax" placeholder="02212354678" value="{{ old('comp_fax') }}">
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_no_npwp') }} : *</label>
									<input disabled="disabled" type="text" id="comp_npwp_number" class="form-control input-submit new-company-field" name="comp_npwp_number" placeholder="123456789456" value="{{ old('comp_npwp_number') }}" required>
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_npwp_file') }} : *</label>
									<input disabled="disabled" type="file" id="comp_npwp_file" class="form-control input-submit new-company-field" name="comp_npwp_file" placeholder="{{ trans('translate.company_npwp_file') }}" accept="application/pdf, image/*" required>
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_no_siup') }} : </label>
									<input disabled="disabled" type="text" id="comp_siup_number" class="form-control input-submit new-company-field" name="comp_siup_number" placeholder="123456789465" value="{{ old('comp_siup_number') }}" required>
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_siup_date') }} : *</label>
									<input disabled="disabled" type="text" id="comp_siup_date" class="date form-control input-submit new-company-field" name="comp_siup_date" placeholder="YYYY-MM-DD" value="{{ old('comp_siup_date') }}" readonly required>
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_siup_file') }} : *</label>
									<input disabled="disabled" type="file" id="comp_siup_file" class="form-control input-submit new-company-field" name="comp_siup_file" placeholder="{{ trans('translate.company_siup_file') }}" accept="application/pdf, image/*" required>
								</div>
								<div class="col_full">
									 <label for="register-form-repassword">{{ trans('translate.company_no_certificate') }} : </label>
									<input disabled="disabled" type="text" id="comp_qs_certificate_number" class="form-control input-submit new-company-field" name="comp_qs_certificate_number" placeholder="213456789456" value="{{ old('comp_qs_certificate_number') }}" required>
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_certificate_date') }} : *</label>
									<input disabled="disabled" type="text" id="comp_qs_certificate_date" class="date form-control input-submit new-company-field" name="comp_qs_certificate_date" placeholder="YYYY-MM-DD" value="{{ old('comp_qs_certificate_date') }}" readonly required>
								</div>
								<div class="col_full">
									<label for="register-form-repassword">{{ trans('translate.company_certificate_file') }} : *</label>
									<input disabled="disabled" type="file" id="comp_qs_certificate_file" class="form-control input-submit new-company-field" name="comp_qs_certificate_file" placeholder="{{ trans('translate.company_certificate_file') }}" accept="application/pdf, image/*" required>
								</div> 
							</div>

							<div class="col_full nobottommargin">
								<button class="button button-3d btn-sky nomargin" id="register-form-submit" name="register-form-submit" value="register">{{ trans('translate.register') }}</button>
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
		$('#cmb-perusahaan').chosen();
		// $('#cmb-ref-perangkat').val(0);
		$('#cmb-perusahaan').trigger("chosen:updated");
		$('.date').datepicker({
	    	format: 'yyyy-mm-dd', 
		    autoclose: true,
		});
	</script>
	<script src="{{ asset('assets/js/app/app-register.js') }}"></script>
@endsection
 
