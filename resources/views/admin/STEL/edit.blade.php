@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Referensi Uji</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Referensi Uji</span>
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
							Edit Referensi Uji
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										No. Dokumen *
									</label>
									<input type="text" id="code" name="code" class="form-control" placeholder="No. Dokumen" value="{{$data->code}}" required readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Nama Dokumen *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Dokumen" value="{{$data->name}}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tahun *
									</label>
									<input type="number" id="year" name="year" class="form-control" placeholder="Tahun" value="{{$data->year}}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Versi *
									</label>
									<input type="text" id="version" name="version" class="form-control" placeholder="Versi" value="{{$data->version}}" required>
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
										Diterbitkan pada tanggal *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" placeholder="Publish at .." value="{{ $data->publish_date }}" name="publish_date" id="publish_date" class="form-control"/>
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
										@if($data->is_active)
											<option value="1" selected>Active</option>
											<option value="0">Inactive</option>
										@else
											<option value="1">Active</option>
											<option value="0" selected>Inactive</option>
										@endif
									</select>
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
	jQuery(document).ready(function() {
		FormElements.init();
	});

	val = '{{ $data->stel_type }}';
	init_form(val);

	value="{{ old('code') }}";
	if(value){init_form(val)};

	function init_form(val){
		switch(val) {
			case '1':
				type_name = 'STEL';
				$("#year").prop('required', true);
				$("#version").prop('required', true);
				break;
			case '2':
				type_name = 'S-TSEL';
				$("#year").prop('required', true);
				$("#version").prop('required', true);
				break;
			case '3':
				type_name = 'STD';
				$("#year").prop('required', true);
				$("#version").prop('required', true);
				break;
			case '4':
				type_name = 'TLKM/I/KAL';
				$("#year").prop('required', false);
				$("#version").prop('required', true);
				break;
			case '5':
				type_name = 'Perdirjen';
				$("#year").prop('required', true);
				$("#version").prop('required', false);
				break;
			case '6':
				type_name = 'Permenkominfo';
				$("#year").prop('required', true);
				$("#version").prop('required', false);
				break;
			case '7':
				$("#year").prop('required', false);
				$("#version").prop('required', false);
				break;
				
			default:
				$("#year").prop('required', false);
				$("#version").prop('required', false);
		}
	}

	$("#year").on("keyup change", function(){
		reset_code();
	});

	$("#version").keyup(function(){
		reset_code();
	});

	function reset_code(){
		switch(val) {
			case '1':
			case '2':
			case '3':
				var res = $("#code").val().split("Versi");
				var res_code = res[0].split("-");
				code = res_code[0]+'-'+res_code[1]+'-'+$("#year").val()+' Versi '+$("#version").val();
				$("#code").val(code);
				break;
			case '4':
				var res = $("#code").val().split(" ");
				code = res[0]+' Versi '+$("#version").val();
				$("#code").val(code);
				break;
				
			default:
				
		}
	}

	$('#txt-price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
</script>
@endsection