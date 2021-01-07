@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Tarif</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Tarif</span>
					</li>
					<li class="active">
						<span>Tambah</span>
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
		@if(!empty(Session::get('error_name')) && (Session::get('error_name') == 1))
			<div class="alert alert-error alert-danger">
				Nama Perangkat sudah ada!
			</div>
		@endif
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/charge', 'method' => 'POST')) !!}
					{{ csrf_field() }}
    				<fieldset>
						<legend>
							Tambah Tarif
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Perangkat *
									</label>
									<input type="text" name="device_name" class="form-control" value="{{ old('device_name') }}" placeholder="Nama Perangkat" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Referensi Uji *
									</label>
									<input type="text" name="stel" class="form-control" value="{{ old('stel') }}" placeholder="Referensi Uji" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kategori *
									</label>
									<select name="category" class="cs-select cs-skin-elastic" required>
									@if (!old('category'))
										<option value="" disabled selected>Select...</option>
									@endif
										@foreach ($labs as $lab)
											<option value="{{$lab->description}}" @if (old('category') == $lab->description) selected @endif >{{$lab->description}}</option>
										@endforeach
									</select>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Durasi (Hari) *
									</label>
									<input type="text" id="txt-duration" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="Durasi (Hari)" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya QA (Rp.) *
									</label>
									<input type="text" id="txt-price" name="price" class="form-control" value="{{ old('price') }}" placeholder="Biaya QA (Rp.)" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya VT (Rp.) *
									</label>
									<input type="text" id="txt-vt_price" name="vt_price" class="form-control" value="{{ old('vt_price') }}" placeholder="Biaya VT (Rp.)" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya TA (Rp.) *
									</label>
									<input type="text" id="txt-ta_price" name="ta_price" class="form-control" value="{{ old('ta_price') }}" placeholder="Biaya TA (Rp.)" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
									@if (!old('is_active'))
										<option value="" disabled selected>Select...</option>
									@endif
										<option value="1" @if(old('is_active') == '1') {{'selected'}} @endif >Active</option>
										<option value="0" @if(old('is_active') == '0') {{'selected'}} @endif >Not Active</option>
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