@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit STEL/STD</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>STEL/STD</span>
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
				{!! Form::open(array('url' => 'admin/stel/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit STEL/STD
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tipe Referensi Uji *
									</label>
									<select name="stel_type" class="cs-select cs-skin-elastic" required>
										@if($data->stel_type == '1')
											<option value="1" selected>STEL</option>
										@else
											<option value="1">STEL</option>
										@endif

										@if($data->stel_type == '2')
											<option value="2" selected>S-TSEL</option>
										@else
											<option value="2">S-TSEL</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kode Dokumen *
									</label>
									<input type="text" name="code" class="form-control" placeholder="Kode Dokumen" value="{{$data->code}}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Dokumen *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Dokumen" value="{{$data->name}}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Tipe Dokumen *
									</label>
									<select name="type" class="cs-select cs-skin-elastic" required>
										@if($data->type == 'Lab Kabel')
											<option value="Lab Kabel" selected>Lab Kabel</option>
										@else
											<option value="Lab Kabel">Lab Kabel</option>
										@endif

										@if($data->type == 'Lab Transmisi')
											<option value="Lab Transmisi" selected>Lab Transmisi</option>
										@else
											<option value="Lab Transmisi">Lab Transmisi</option>
										@endif

										@if($data->type == 'Lab Device')
											<option value="Lab Device" selected>Lab Device</option>
										@else
											<option value="Lab Device">Lab Device</option>
										@endif

										@if($data->type == 'Lab Energi')
											<option value="Lab Energi" selected>Lab Energi</option>
										@else
											<option value="Lab Energi">Lab Energi</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Harga *
									</label>
									<input type="text" id="txt-price" name="price" class="form-control" placeholder="Harga" value="{{$data->price}}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Total Dokumen *
									</label>
									<input type="text" id="txt-total" name="total" class="form-control" placeholder="Total Dokumen" value="{{$data->total}}" required>
								</div>
							</div>
	                        <div class="col-md-4">
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
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
											Versi *
									</label>
									<input type="text" name="version" class="form-control" placeholder="Versi" value="{{$data->version}}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Tahun *
									</label>
									<input type="number" name="year" class="form-control" placeholder="Tahun" value="{{$data->year}}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										File *
									</label>
									@if($data->attachment != '')
										<a href="{{ URL::to('/admin/stel/media/'.$data->id) }}" target="_blank">Lihat File</a>
									@endif
									<input type="file" name="attachment" class="form-control"  required>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                            <a style=" color:white !important;" href="{{URL::to('/admin/stel')}}">
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
	$('#txt-price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
	
	$('#txt-total').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
</script>
@endsection