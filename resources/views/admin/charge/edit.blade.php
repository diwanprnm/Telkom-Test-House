@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Tarif</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Tarif</span>
					</li>
					<li class="active">
						<span>Edit</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/charge/'.$data->id, 'method' => 'PUT')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Tarif
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Perangkat *
									</label>
									<input type="text" name="device_name" class="form-control" placeholder="Nama Perangkat" value="{{ $data->device_name }}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Referensi Uji *
									</label>
									<input type="text" name="stel" class="form-control" placeholder="STEL" value="{{ $data->stel }}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kategori *
									</label>
									<select name="category" class="cs-select cs-skin-elastic" required>
										@if($data->category == 'Lab CPE')
											<option value="Lab CPE" selected>Lab CPE</option>
										@else
											<option value="Lab CPE">Lab CPE</option>
										@endif
										
										@if($data->category == 'Lab Device')
											<option value="Lab Device" selected>Lab Device</option>
										@else
											<option value="Lab Device">Lab Device</option>
										@endif

										@if($data->category == 'Lab Energi')
											<option value="Lab Energi" selected>Lab Energi</option>
										@else
											<option value="Lab Energi">Lab Energi</option>
										@endif

										@if($data->category == 'Lab Kabel')
											<option value="Lab Kabel" selected>Lab Kabel</option>
										@else
											<option value="Lab Kabel">Lab Kabel</option>
										@endif

										@if($data->category == 'Lab Transmisi')
											<option value="Lab Transmisi" selected>Lab Transmisi</option>
										@else
											<option value="Lab Transmisi">Lab Transmisi</option>
										@endif

										@if($data->category == 'Lab EMC')
											<option value="Lab EMC" selected>Lab EMC</option>
										@else
											<option value="Lab EMC">Lab EMC</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Durasi (Hari) *
									</label>
									<input type="text" id="txt-duration" name="duration" class="form-control" placeholder="Durasi (Hari)" value="{{ $data->duration }}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya QA (Rp.) *
									</label>
									<input type="text" id="txt-price" name="price" class="form-control" placeholder="Biaya QA (Rp.)" value="{{ $data->price }}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya VT (Rp.) *
									</label>
									<input type="text" id="txt-vt_price" name="vt_price" class="form-control" placeholder="Biaya VT (Rp.)" value="{{ $data->vt_price }}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya TA (Rp.) *
									</label>
									<input type="text" id="txt-ta_price" name="ta_price" class="form-control" placeholder="Biaya TA (Rp.)" value="{{ $data->ta_price }}" required>
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
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left" name="submit" value="submit">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/charge')}}">
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
<script type="text/javascript">
	$('#txt-duration').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
	
	$('#txt-price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
	
	$('#txt-vt_price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
	
	$('#txt-ta_price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
</script>
@endsection