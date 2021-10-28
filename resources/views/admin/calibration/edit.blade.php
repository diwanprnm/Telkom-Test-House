@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Tarif Kalibrasi</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Tarif Kalibrasi</span>
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
				{!! Form::open(array('url' => 'admin/calibration/'.$data->id, 'method' => 'PUT')) !!}
				{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Tarif Kalibrasi
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Alat Ukur *
									</label>
									<input type="text" name="device_name" class="form-control" placeholder="Nama Alat Ukur" value="{{ $data->device_name }}" required>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Referensi Uji *
									</label>
									<select class="form-control" id="stel" name="stel" required>
										<option value="" disabled>Select...</option>
										@foreach($stel as $item)
											<option value="{{$item->code}}" @if ($item->code == $data->stel) selected @endif>{{$item->code}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Durasi (Hari) *
									</label>
									<input type="text" id="txt-duration" name="duration" class="form-control" value="{{ $data->duration }}" placeholder="Durasi (Hari)" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya (Rp.) *
									</label>
									<input type="text" id="txt-price" name="price" class="form-control" placeholder="Biaya (Rp.)" value="{{ $data->price }}" required>
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
								<a style=" color:white !important;" href="{{URL::to('/admin/calibration')}}">
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
	$('#stel').chosen();
	// $('#stel').val(0);
	$('#stel').trigger("chosen:updated");

	$('#txt-price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
	$('#txt-duration').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
</script>
@endsection