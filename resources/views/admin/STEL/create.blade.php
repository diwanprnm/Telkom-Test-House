@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Referensi Uji Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Referensi Uji</span>
					</li>
					<li class="active">
						<span>Tambah</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		@if(!empty(Session::get('error_name')) && (Session::get('error_name') == 1))
			<div class="alert alert-error alert-danger">
				Nama Dokumen sudah ada!
			</div>
		@endif
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/stel', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{{ csrf_field() }}
    				<fieldset>
						<legend>
							Tambah Referensi Uji Baru
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tipe Referensi Uji *
									</label>
									<select name="stel_type" class="cs-select cs-skin-elastic" required>
									@if( old('stel_type') == '1' )
										<option value="1" selected>STEL</option>
										<option value="2">S-TSEL</option>
									@elseif( old('stel_type') == '2' )
										<option value="1">STEL</option>
										<option value="2" selected>S-TSEL</option>
									@else
										<option value="" disabled selected>Select...</option>
										<option value="1">STEL</option>
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
									<input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Kode Dokumen" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Dokumen *
									</label>
									<input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama Dokumen" required>
								</div>
							</div>
							
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Tipe Dokumen*
									</label>
									<select name="type" class="cs-select cs-skin-elastic" required>
										@foreach ($examLab as $dataLab)
											<option value="" disabled selected>Select...</option>
                                        	@if (old('type') == $dataLab->id)
												<option value="{{$dataLab->id}}" selected>{{$dataLab->name}}</option>
											@else
												<option value="{{$dataLab->id}}">{{$dataLab->name}}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Harga *
									</label>
									<input type="text" id="txt-price" name="price" class="form-control" value="{{ old('price') }}" placeholder="Harga" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Total Dokumen *
									</label>
									<input type="text" id="txt-total" name="total" class="form-control" value="{{ old('total') }}" placeholder="Total Dokumen" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
										@if( old('is_active') == '1' )
											<option value="1" selected>Active</option>
											<option value="0">Not Active</option>
										@elseif( old('is_active') == '0' )
											<option value="1">Active</option>
											<option value="0" selected>Not Active</option>
										@else
											<option value="" disabled selected>Select...</option>
											<option value="1">Active</option>
											<option value="0">Not Active</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Versi *
									</label>
									<input type="text" name="version" class="form-control" value="{{ old('version') }}" placeholder="Versi" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Tahun *
									</label>
									<input type="number" name="year" class="form-control" value="{{ old('year') }}" placeholder="Tahun" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										File *
									</label>
									<input type="file" name="attachment" class="form-control" accept="application/pdf">
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