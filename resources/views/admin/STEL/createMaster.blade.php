@extends('layouts.app')

@section('content')
<div class="main-content">
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Referensi Uji Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Data Master</span>
					</li>
					<li>
						<span>Referensi Uji</span>
					</li>
					<li class="active">
						<span>Tambah Referensi Uji</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		@if (Session::get('error'))
		<div class="alert alert-error alert-danger">
			{{ Session::get('error') }}
		</div>
		@endif
		<div class="container-fluid container-fullw bg-white">
			<div class="row">
				<div class="col-md-12">
					{!! Form::open(array('url' => 'admin/stel/storeMaster', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
					<fieldset>
						<legend>
							Tambah Referensi Uji
						</legend>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>
										Tipe Referensi Uji *
									</label>
									<select id="stel_type" name="stel_type" class="cs-select cs-skin-elastic" required>
										<option value="" disabled>Select...</option>
										@foreach ($type as $item)
										<option value="{{ $item['id'] }}" {{ (old("stel_type") == $item['id'] ? "selected":"") }}>{{ $item['name'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3" id="master-code-form">
								<div class="form-group">
									<label>
										Kode *
									</label>
									<input type="text" id="master_code" name="master_code" class="form-control" placeholder="Kode" value="{{ old('master_code') }}" required>
								</div>
							</div>
							<div class="col-md-3">
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
							<div class="col-md-3">
								<div class="form-group">
									<label>
										Bahasa *
									</label>
									<select name="lang" class="cs-select cs-skin-elastic" required>
										<!-- <option value="" disabled selected>Select...</option> -->
										<option value="IDN" {{ (old("lang") == 'IDN' ? "selected":"") }}>IDN</option>
										<option value="ENG" {{ (old("lang") == 'ENG' ? "selected":"") }}>ENG</option>
									</select>
								</div>
							</div>
						</div>
						<!-- DETAIL -->
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
									<input type="text" id="name" name="name" class="form-control" placeholder="Nama Dokumen" value="{{ old('name') }}" required>
								</div>
							</div>
							<div class="col-md-6" id="year-form">
								<div class="form-group">
									<label>
										Tahun *
									</label>
									<input type="text" id="year" name="year" class="form-control" placeholder="Tahun" value="{{ old('year') }}" required>
								</div>
							</div>
							<div class="col-md-6" id="version-form">
								<div class="form-group">
									<label>
										Versi *
									</label>
									<input type="text" id="version" name="version" class="form-control" placeholder="Versi" value="{{ old('version') }}" required>
								</div>
							</div>
							<div class="col-md-4" id="price-form">
								<div class="form-group ">
									<label>
										Harga *
									</label>
									<input type="text" id="txt-price" name="price" class="form-control" placeholder="Harga" value="{{ old('price') }}" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>
										Diterbitkan pada tanggal *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" placeholder="Publish at .." value="{{ old('publish_date') }}" name="publish_date" id="publish_date" class="form-control" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-4 ">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
										<option value="" disabled>Select...</option>
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group ">
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

	value = "{{ old('stel_type') }}";
	if (value) {
		switch (value) {
			case '1':
				type_name = 'STEL';
				$("#master_code").prop('required', true);
				$("#year").prop('required', true);
				$("#version").prop('required', true);
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '2':
				type_name = 'S-TSEL';
				$("#master_code").prop('required', true);
				$("#year").prop('required', true);
				$("#version").prop('required', true);
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '3':
				type_name = 'STD';
				$("#master_code").prop('required', true);
				$("#year").prop('required', true);
				$("#version").prop('required', true);
				$("#code").prop('readonly', false);
				code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '4':
				type_name = 'TLKM/I/KAL';
				$("#master_code").prop('required', true);
				$("#year").prop('required', false);
				$("#version").prop('required', true);
				$("#code").prop('readonly', true);
				code = type_name + '/' + $("#master_code").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '5':
				type_name = 'Perdirjen';
				$("#master_code").prop('required', true);
				$("#year").prop('required', true);
				$("#version").prop('required', false);
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val() + '/Dirjen/' + $("#year").val();
				$("#code").val(code);
				break;
			case '6':
				type_name = 'Permenkominfo';
				$("#master_code").prop('required', true);
				$("#year").prop('required', true);
				$("#version").prop('required', false);
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val();
				$("#code").val(code);
				break;
			case '7':
				$("#master_code").prop('required', false);
				$("#year").prop('required', false);
				$("#version").prop('required', false);
				$("#code").prop('readonly', false);
				break;

			default:
				$("#master_code").prop('required', false);
				$("#year").prop('required', false);
				$("#version").prop('required', false);
				$("#code").prop('readonly', false);
		}
	};

	SelectFx.prototype._changeOption = function(e) {
		if(this.el.id == "stel_type"){
			val = this.current+1;
			switch (val) {
				case 1:
					type_name = 'STEL';
					// show master-code-form
					$("#master_code").prop('required', true);
					$("#master-code-form").show();
					$("#name").val() == '' ? $("#name").val('Spesifikasi Telekomunikasi') : '';
					// show year-form
					$("#year").prop('required', true);
					$("#year-form").show();
					// show version-form
					$("#version").prop('required', true);
					$("#version-form").show();
					// show price-code-form
					$("#txt-price").prop('required', true);
					$("#price-form").show();
					$("#code").prop('readonly', true);
					code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
					$("#code").val(code);
					break;
				case 2:
					type_name = 'S-TSEL';
					// show master-code-form
					$("#master_code").prop('required', true);
					$("#master-code-form").show();
					$("#name").val() == '' ? $("#name").val('Spesifikasi Telekomunikasi') : '';
					// show year-form
					$("#year").prop('required', true);
					$("#year-form").show();
					// show version-form
					$("#version").prop('required', true);
					$("#version-form").show();
					// show price-code-form
					$("#txt-price").prop('required', true);
					$("#price-form").show();
					$("#code").prop('readonly', true);
					code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
					$("#code").val(code);
					break;
				case 3:
					type_name = 'STD';
					// show master-code-form
					$("#master_code").prop('required', true);
					$("#master-code-form").show();
					$("#name").val('');
					// show year-form
					$("#year").prop('required', true);
					$("#year-form").show();
					// show price-code-form
					$("#txt-price").prop('required', true);
					$("#price-form").show();
					// show version-form
					$("#version").prop('required', true);
					$("#version-form").show();
					$("#code").prop('readonly', false);
					code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
					$("#code").val(code);
					break;
				case 4:
					type_name = 'TLKM/I/KAL';
					// show master-code-formf
					$("#master_code").prop('required', true);
					$("#master-code-form").show();
					$("#name").val('');
					// hide year-code-form
					$("#year").prop('required', false);
					$("#year-form").hide();
					// hide price-code-form
					$("#txt-price").prop('required', false);
					$("#price-form").hide();
					// show version-form
					$("#version").prop('required', true);
					$("#version-form").show();
					$("#code").prop('readonly', true);
					code = type_name + '/' + $("#master_code").val() + ' Versi ' + $("#version").val();
					$("#code").val(code);
					break;

				case 5:
					type_name = 'Perdirjen';
					// show master-code-form
					$("#master_code").prop('required', true);
					$("#master-code-form").show();
					$("#name").val('');
					// show year-code-form
					$("#year").prop('required', true);
					$("#year-form").show();
					// hide price-code-form
					$("#txt-price").prop('required', false);
					$("#price-form").hide();
					// hide version-form
					$("#version").prop('required', false);
					$("#version-form").hide();
					$("#code").prop('readonly', true);
					code = type_name + ' ' + $("#master_code").val() + '/Dirjen/' + $("#year").val();
					$("#code").val(code);
					break;
				case 6:
					type_name = 'Permenkominfo';
					// show master-code-form
					$("#master_code").prop('required', true);
					$("#master-code-form").show();
					$("#name").val('');
					// hide year-form
					$("#year").prop('required', true);
					$("#year-form").show();
					// hide price-code-form
					$("#txt-price").prop('required', false);
					$("#price-form").hide();
					// hide version-form
					$("#version").prop('required', false);
					$("#version-form").hide();
					$("#code").prop('readonly', true);
					code = type_name + ' ' + $("#master_code").val();
					$("#code").val(code);
					break;
				case 7:
					// hide master-code-form
					$("#master_code").prop('required', false);
					$("#master-code-form").hide();
					$("#name").val('');
					// hide year-form
					$("#year").prop('required', false);
					$("#year-form").hide();
					// hide price-code-form
					$("#txt-price").prop('required', false);
					$("#price-form").hide();
					// hide version-form
					$("#version").prop('required', false);
					$("#version-form").hide();
					$("#code").prop('readonly', false);
					break;

				default:
					$("#master_code").prop('required', false);
					$("#name").val('');
					$("#year").prop('required', false);
					$("#version").prop('required', false);
					$("#code").prop('readonly', false);
			}
		}

		// current option
		var opt = this.selOpts[ this.current ];
		// update current selected value
		this.selPlaceholder.textContent = opt.textContent;
		// change native select element´s value
		this.el.value = opt.getAttribute( 'data-value' );
		// callback
		this.options.onChange( this.el.value );
	}

	$("#master_code").on("keyup change", function() {
		reset_code();
	});

	$("#year").on("keyup change", function() {
		reset_code();
	});

	$("#version").keyup(function() {
		reset_code();
	});

	function reset_code() {
		switch ($("#stel_type").val()) {
			case '1':
				type_name = 'STEL';
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '2':
				type_name = 'S-TSEL';
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '3':
				type_name = 'STD';
				$("#code").prop('readonly', false);
				code = type_name + ' ' + $("#master_code").val() + '-' + $("#year").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '4':
				type_name = 'TLKM/I/KAL';
				$("#code").prop('readonly', true);
				code = type_name + '/' + $("#master_code").val() + ' Versi ' + $("#version").val();
				$("#code").val(code);
				break;
			case '5':
				type_name = 'Perdirjen';
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val() + '/Dirjen/' + $("#year").val();
				$("#code").val(code);
				break;
			case '6':
				type_name = 'Permenkominfo';
				$("#code").prop('readonly', true);
				code = type_name + ' ' + $("#master_code").val();
				$("#code").val(code);
				break;
			case '7':
				$("#code").prop('readonly', false);
				break;

			default:
				$("#code").prop('readonly', false);
		}
	}

	$('#txt-price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	});
</script>
@endsection