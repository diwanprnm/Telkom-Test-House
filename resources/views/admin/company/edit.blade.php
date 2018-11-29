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
				{!! Form::open(array('url' => 'admin/company/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Perusahaan
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Perusahaan *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Perusahaan" value="{{ $data->name }}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Alamat Perusahaan *
									</label>
									<textarea type="text" name="address" class="form-control" placeholder="Alamat Perusahaan" required>{{ $data->address }}</textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										PLG_ID *
									</label>
									<input type="text" name="plg_id" class="form-control" placeholder="PLG_ID" value="{{ $data->plg_id }}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										NIB *
									</label>
									<input type="text" name="nib" class="form-control" placeholder="NIB" value="{{ $data->nib }}">
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Email *
									</label>
									<input type="email" name="email" class="form-control" placeholder="Email" value="{{ $data->email }}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kota *
									</label>
									<input type="text" name="city" class="form-control" placeholder="Kota" value="{{ $data->city }}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kode Pos
									</label>
									<input type="text" name="postal_code" class="form-control" placeholder="Kode Pos" value="{{ $data->postal_code }}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor Telepon *
									</label>
									<input type="text" name="phone_number" class="form-control" placeholder="Nomor Telepon" value="{{ $data->phone_number }}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Fax
									</label>
									<input type="text" name="fax" class="form-control" placeholder="Fax" value="{{ $data->fax }}">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nomor NPWP
									</label>
									<input type="text" name="npwp_number" class="form-control" placeholder="NPWP" value="{{ $data->npwp_number }}">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File NPWP
									</label>
									@if($data->npwp_file != '')
										<a href="{{ URL::to('/admin/company/media/'.$data->id.'/npwp') }}" target="_blank">Lihat File NPWP</a>
									@endif
									<input type="file" name="npwp_file" class="form-control" accept="application/pdf, image/*">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor SIUP
									</label>
									<input type="text" name="siup_number" class="form-control" placeholder="SIUP" value="{{ $data->siup_number }}">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Masa Berlaku SIUP
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="siup_date" class="form-control" value="{{ $data->siup_date }}">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File SIUP
									</label>
									@if($data->siup_file != '')
										<a href="{{ URL::to('/admin/company/media/'.$data->id.'/siup') }}" target="_blank">Lihat File SIUP</a>
									@endif
									<input type="file" name="siup_file" class="form-control" accept="application/pdf, image/*">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor Sertifikat Uji Mutu
									</label>
									<input type="text" name="qs_certificate_number" class="form-control" placeholder="Nomor Sertifikat Uji Mutu" value="{{ $data->qs_certificate_number }}">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Masa Berlaku Sertifikat Uji Mutu
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="qs_certificate_date" class="form-control" value="{{ $data->qs_certificate_date }}">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File Sertifikat Uji Mutu
									</label>
									@if($data->qs_certificate_file != '')
										<a href="{{ URL::to('/admin/company/media/'.$data->id.'/qs') }}" target="_blank">Lihat File Sertifikat Uji Mutu</a>
									@endif
									<input type="file" name="qs_certificate_file" class="form-control" accept="application/pdf, image/*">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Keterangan
									</label>
									<textarea type="text" name="keterangan" class="form-control" placeholder="Keterangan terkait perusahaan ...">{{ $data->keterangan }}</textarea>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
										@if($data->is_active)
											<option value="1" selected>Active</option>
											<option value="0">Not Active</option>
										@else
											<option value="1">Active</option>
											<option value="0" selected>Not Active</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/company')}}">
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