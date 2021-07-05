@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Dokumen {{ $type }} Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Data Master</span>
					</li>
					<li>
						<span>Referensi Uji</span>
					</li>
					<li>
						<span>Detail</span>
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
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Tambah Dokumen {{ $type }}
						</legend>
						<input type="hidden" id="stel_type" name="stel_type" value="{{ $stelMaster->type }}"/>
						<input type="hidden" id="stels_master_id" name="stels_master_id" value="{{ $stelMaster->id }}"/>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										No. Dokumen *
									</label>
									<input type="text" id="code" name="code" class="form-control" placeholder="No. Dokumen" value="{{ old('code') }}" required readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Nama Dokumen *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Dokumen" value="{{ old('name') }}" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>
										Tahun *
									</label>
									<input type="number" id="year" name="year" class="form-control" placeholder="Tahun" value="{{ old('year') }}" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>
										Versi *
									</label>
									<input type="text" id="version" name="version" class="form-control" placeholder="Versi" value="{{ old('version') }}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Harga *
									</label>
									<input type="text" id="txt-price" name="price" class="form-control" placeholder="Harga" value="{{ old('price') }}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Lab *
									</label>
									<select name="type" class="cs-select cs-skin-elastic" required>
										@foreach ($examLab as $dataLab)
											<option value="" disabled>Select...</option>
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
										Dipublishkan pada tanggal *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" placeholder="Publish at .." value="{{ old('publish_date') }}" name="publish_date" id="publish_date" class="form-control"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
										<option value="" disabled selected>Select...</option>
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
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
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
	reset_code();
	jQuery(document).ready(function() {
		FormElements.init();
	});

	$("#year").on("keyup change", function(){
		reset_code();
	});

	$("#version").keyup(function(){
		reset_code();
	});

	function reset_code(){
		code = '{{ $type }} {{ $stelMaster->code }}-'+$("#year").val()+' Versi '+$("#version").val();
		$("#code").val(code);
	}
	
	$('#txt-price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	});
</script>
@endsection