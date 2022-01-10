
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
							@if(!empty(Session::get('error')))
								<div class="alert alert-error alert-danger">
									 {{ Session::get('error') }}
								</div>
							@endif
							<div class="col-md-12">
								<label><strong>-</strong> Nama Perusahaan : {{ $data->company->name }}</label>
								@if($data->name != NULL)
								<label>, <strong>menjadi</strong> {{ $data->name }}</label>
								<input type="hidden" name="name" value="{{ $data->name }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><strong>-</strong> Alamat Perusahaan : {{ $data->company->address }}</label>
								@if($data->address != NULL)
								<label>, <strong>menjadi</strong> {{ $data->address }}</label>
								<input type="hidden" name="address" value="{{ $data->address }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> PLG_ID : {{ $data->company->plg_id }}</label>
								@if($data->plg_id != NULL)
								<label>, <strong>menjadi</strong> {{ $data->plg_id }}</label>
								<input type="hidden" name="plg_id" value="{{ $data->plg_id }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strongb>-</strong> NIB : {{ $data->company->nib }}</label>
								@if($data->nib != NULL)
								<label>, <strong>menjadi</strong> {{ $data->nib }}</label>
								<input type="hidden" name="nib" value="{{ $data->nib }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><strong>-</strong> Email : {{ $data->company->email }}</label>
								@if($data->email != NULL)
								<label>, <strong>menjadi</strong> {{ $data->email }}</label>
								<input type="hidden" name="email" value="{{ $data->email }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Kota : {{ $data->company->city }}</label>
								@if($data->city != NULL)
								<label>, <strong>menjadi</strong> {{ $data->city }}</label>
								<input type="hidden" name="city" value="{{ $data->city }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Kode Pos : {{ $data->company->postal_code }}</label>
								@if($data->postal_code != NULL)
								<label>, <strong>menjadi</strong> {{ $data->postal_code }}</label>
								<input type="hidden" name="postal_code" value="{{ $data->postal_code }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Nomor Telepon : {{ $data->company->phone_number }}</label>
								@if($data->phone_number != NULL)
								<label>, <strong>menjadi</strong> {{ $data->phone_number }}</label>
								<input type="hidden" name="phone_number" value="{{ $data->phone_number }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Fax : {{ $data->company->fax }}</label>
								@if($data->fax != NULL)
								<label>, <strong>menjadi</strong> {{ $data->fax }}</label>
								<input type="hidden" name="fax" value="{{ $data->fax }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><strong>-</strong> Nomor NPWP : {{ $data->company->npwp_number }}</label>
								@if($data->npwp_number != NULL)
								<label>, <strong>menjadi</strong> {{ $data->npwp_number }}</label>
								<input type="hidden" name="npwp_number" value="{{ $data->npwp_number }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><strong>-</strong> File NPWP : <a href="{{ URL::to('/admin/company/media/'.$data->company->id.'/npwp') }}" style="color:#858585 !important;" target="_blank">{{ $data->company->npwp_file }}</a></label>
								@if($data->npwp_file != NULL)
								<label>, <strong>menjadi</strong> <a href="{{ URL::to('/admin/tempcompany/media/'.$data->id.'/npwp') }}" style="color:#858585 !important;" target="_blank">{{ $data->npwp_file }}</a></label>
								<input type="hidden" name="npwp_file" value="{{ $data->npwp_file }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Nomor SIUP : {{ $data->company->siup_number }}</label>
								@if($data->siup_number != NULL)
								<label>, <strong>menjadi</strong> {{ $data->siup_number }}</label>
								<input type="hidden" name="siup_number" value="{{ $data->siup_number }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Masa berlaku SIUP : {{ $data->company->siup_date }}</label>
								@if($data->siup_date != NULL)
								<label>, <strong>menjadi</strong> {{ $data->siup_date }}</label>
								<input type="hidden" name="siup_date" value="{{ $data->siup_date }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><strong>-</strong> File SIUP : <a href="{{ URL::to('/admin/company/media/'.$data->company->id.'/siup') }}" style="color:#858585 !important;" target="_blank">{{ $data->company->siup_file }}</a></label>
								@if($data->siup_file != NULL)
								<label>, <strong>menjadi</strong> <a href="{{ URL::to('/admin/tempcompany/media/'.$data->id.'/siup') }}" style="color:#858585 !important;" target="_blank">{{ $data->siup_file }}</a></label>
								<input type="hidden" name="siup_file" value="{{ $data->siup_file }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Nomor Sertifikat Manajemen Mutu : {{ $data->company->qs_certificate_number }}</label>
								@if($data->qs_certificate_number != NULL)
								<label>, <strong>menjadi</strong> {{ $data->qs_certificate_number }}</label>
								<input type="hidden" name="qs_certificate_number" value="{{ $data->qs_certificate_number }}">
								@endif
							</div>
							<div class="col-md-6">
								<label><strong>-</strong> Masa berlaku Sertifikat Manajemen Mutu : {{ $data->company->qs_certificate_date }}</label>
								@if($data->qs_certificate_date != NULL)
								<label>, <strong>menjadi</strong> {{ $data->qs_certificate_date }}</label>
								<input type="hidden" name="qs_certificate_date" value="{{ $data->qs_certificate_date }}">
								@endif
							</div>
							<div class="col-md-12">
								<label><strong>-</strong> File Sertifikat Manajemen Mutu : <a href="{{ URL::to('/admin/company/media/'.$data->company->id.'/qs') }}" style="color:#858585 !important;" target="_blank">{{ $data->company->qs_certificate_file }}</a></label>
								@if($data->qs_certificate_file != NULL)
								<label>, <strong>menjadi</strong> <a href="{{ URL::to('/admin/tempcompany/media/'.$data->id.'/qs') }}" style="color:#858585 !important;" target="_blank">{{ $data->qs_certificate_file }}</a></label>
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