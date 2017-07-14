
@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Perusahaan</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Perusahaan</span>
					</li>
					<li class="active">
						<span>Edit</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/tempcompany/'.$data->id, 'method' => 'PUT')) !!}
					{!! csrf_field() !!}
					<fieldset>
						<legend>
							Edit Perusahaan
						</legend>
						<div class="row">
							<div class="col-md-12">
								<label><b>-</b> Nama Perusahaan : {{ $data->company->name }}</label>
								@if($data->name != NULL)
								<label>, <b>menjadi</b> {{ $data->name }}</label>
								<input type="hidden" name="name" value="{{ $data->name }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><b>-</b> Alamat Perusahaan : {{ $data->company->address }}</label>
								@if($data->address != NULL)
								<label>, <b>menjadi</b> {{ $data->address }}</label>
								<input type="hidden" name="address" value="{{ $data->address }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><b>-</b> Email : {{ $data->company->email }}</label>
								@if($data->email != NULL)
								<label>, <b>menjadi</b> {{ $data->email }}</label>
								<input type="hidden" name="email" value="{{ $data->email }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Kota : {{ $data->company->city }}</label>
								@if($data->city != NULL)
								<label>, <b>menjadi</b> {{ $data->city }}</label>
								<input type="hidden" name="city" value="{{ $data->city }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Kode Pos : {{ $data->company->postal_code }}</label>
								@if($data->postal_code != NULL)
								<label>, <b>menjadi</b> {{ $data->postal_code }}</label>
								<input type="hidden" name="postal_code" value="{{ $data->postal_code }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Nomor Telepon : {{ $data->company->phone_number }}</label>
								@if($data->phone_number != NULL)
								<label>, <b>menjadi</b> {{ $data->phone_number }}</label>
								<input type="hidden" name="phone_number" value="{{ $data->phone_number }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Fax : {{ $data->company->fax }}</label>
								@if($data->fax != NULL)
								<label>, <b>menjadi</b> {{ $data->fax }}</label>
								<input type="hidden" name="fax" value="{{ $data->fax }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><b>-</b> Nomor NPWP : {{ $data->company->npwp_number }}</label>
								@if($data->npwp_number != NULL)
								<label>, <b>menjadi</b> {{ $data->npwp_number }}</label>
								<input type="hidden" name="npwp_number" value="{{ $data->npwp_number }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><b>-</b> File NPWP : <a href="{{ URL::to('/admin/company/media/'.$data->company->id.'/npwp') }}" style="color:#858585 !important;" target="_blank">{{ $data->company->npwp_file }}</a></label>
								@if($data->npwp_file != NULL)
								<label>, <b>menjadi</b> <a href="{{ URL::to('/admin/tempcompany/media/'.$data->id.'/npwp') }}" style="color:#858585 !important;" target="_blank">{{ $data->npwp_file }}</a></label>
								<input type="hidden" name="npwp_file" value="{{ $data->npwp_file }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Nomor SIUPP : {{ $data->company->siup_number }}</label>
								@if($data->siup_number != NULL)
								<label>, <b>menjadi</b> {{ $data->siup_number }}</label>
								<input type="hidden" name="siup_number" value="{{ $data->siup_number }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Masa berlaku SIUPP : {{ $data->company->siup_date }}</label>
								@if($data->siup_date != NULL)
								<label>, <b>menjadi</b> {{ $data->siup_date }}</label>
								<input type="hidden" name="siup_date" value="{{ $data->siup_date }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><b>-</b> File SIUPP : <a href="{{ URL::to('/admin/company/media/'.$data->company->id.'/siup') }}" style="color:#858585 !important;" target="_blank">{{ $data->company->siup_file }}</a></label>
								@if($data->siup_file != NULL)
								<label>, <b>menjadi</b> <a href="{{ URL::to('/admin/tempcompany/media/'.$data->id.'/siup') }}" style="color:#858585 !important;" target="_blank">{{ $data->siup_file }}</a></label>
								<input type="hidden" name="siup_file" value="{{ $data->siup_file }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Nomor Sertifikat Uji Mutu : {{ $data->company->qs_certificate_number }}</label>
								@if($data->qs_certificate_number != NULL)
								<label>, <b>menjadi</b> {{ $data->qs_certificate_number }}</label>
								<input type="hidden" name="qs_certificate_number" value="{{ $data->qs_certificate_number }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><b>-</b> Masa berlaku Sertifikat Uji Mutu : {{ $data->company->qs_certificate_date }}</label>
								@if($data->qs_certificate_date != NULL)
								<label>, <b>menjadi</b> {{ $data->qs_certificate_date }}</label>
								<input type="hidden" name="qs_certificate_date" value="{{ $data->qs_certificate_date }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><b>-</b> File Sertifikat Uji Mutu : <a href="{{ URL::to('/admin/company/media/'.$data->company->id.'/qs') }}" style="color:#858585 !important;" target="_blank">{{ $data->company->qs_certificate_file }}</a></label>
								@if($data->qs_certificate_file != NULL)
								<label>, <b>menjadi</b> <a href="{{ URL::to('/admin/tempcompany/media/'.$data->id.'/qs') }}" style="color:#858585 !important;" target="_blank">{{ $data->qs_certificate_file }}</a></label>
								<input type="hidden" name="qs_certificate_file" value="{{ $data->qs_certificate_file }}">
								@endif
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_commited" class="cs-select cs-skin-elastic" required>
										@if ($data->is_commited == 1)
											<option value="1" selected>Approve</option>
										@else
											<option value="1">Approve</option>
										@endif
										
										@if ($data->is_commited == -1)
											<option value="-1" selected>Decline</option>
										@else
											<option value="-1">Decline</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
									Submit
								</button>
								<a style=" color:white !important;" href="{{URL::to('/admin/tempcompany')}}">
									<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
									Cancel
									</button>
								</a>
							</div>
						</div>
					</fieldset>
				{!! Form::close() !!}
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
	</div>
</div>
@endsection

@section('content_js')
<script src={{ asset("vendor/maskedinput/jquery.maskedinput.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js") }}></script>
<script src={{ asset("vendor/autosize/autosize.min.js") }}></script>
<script src={{ asset("vendor/selectFx/classie.js") }}></script>
<script src={{ asset("vendor/selectFx/selectFx.js") }}></script>
<script src={{ asset("vendor/select2/select2.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.js") }}></script>
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
@endsection