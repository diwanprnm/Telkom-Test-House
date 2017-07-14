@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Revisi Pengujian</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Pengujian</span>
					</li>
					<li>
						<span>Detail</span>
					</li>
					<li class="active">
						<span>Revisi</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">				
			{!! Form::open(array('url' => 'admin/examination/revisi', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
				{!! csrf_field() !!}
				<fieldset>
					<legend>
						Data Pemohon
					</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Nama Pemohon *
								</label>
								<input type="text" name="nama_pemohon" class="form-control" value="{{ $data->user->name }}" placeholder="Nama Pemohon" disabled>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Alamat *
								</label>
								<textarea name="alamat_pemohon" class="form-control" disabled>{{ $data->company->address }}</textarea>
							</div>
						</div>
                        <div class="col-md-6">
							<div class="form-group">
								<label>
									Telepon *
								</label>
								<input type="text" name="telp_pemohon" class="form-control" value="{{ $data->company->phone_number }}" placeholder="Telepon" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Email *
								</label>
								<input type="text" name="email_pemohon" class="form-control" value="{{ $data->user->email }}" placeholder="Email" disabled>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						Data Perusahaan
					</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Nama Perusahaan *
								</label>
								<input type="text" name="nama_perusahaan" class="form-control" value="{{ $data->company->name }}" placeholder="Nama Pemohon" disabled>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Alamat *
								</label>
								<textarea name="alamat_perusahaan" class="form-control" disabled>{{ $data->company->address }}</textarea>
							</div>
						</div>
                        <div class="col-md-6">
							<div class="form-group">
								<label>
									Telepon *
								</label>
								<input type="text" name="telp_perusahaan" class="form-control" value="{{ $data->company->phone_number }}" placeholder="Telepon" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Faksimile *
								</label>
								<input type="text" name="fax_perusahaan" class="form-control" value="{{ $data->company->fax }}" placeholder="Faksimile" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Email *
								</label>
								<input type="text" name="email_perusahaan" class="form-control" value="{{ $data->company->email }}" placeholder="Email" disabled>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						Data Perangkat
					</legend>
					<input type="hidden" name="id_perangkat" class="form-control" value="{{ $data->device->id }}">
					<input type="hidden" name="id_exam" class="form-control" value="{{ $data->id }}">
					<input type="hidden" name="exam_type" class="form-control" value="{{ $data->examinationType->name }}">
					<input type="hidden" name="exam_desc" class="form-control" value="{{ $data->examinationType->description }}">
					<input type="hidden" name="exam_created" class="form-control" value="{{ $data->created_by }}">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Perangkat *
								</label>
								<input type="hidden" name="hidden_nama_perangkat" class="form-control" value="{{ $data->device->name }}" placeholder="Perangkat">
								<input type="text" name="nama_perangkat" class="form-control" value="{{ $data->device->name }}" placeholder="Perangkat">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Merek *
								</label>
								<input type="hidden" name="hidden_merk_perangkat" class="form-control" value="{{ $data->device->mark }}" placeholder="Merek">
								<input type="text" name="merk_perangkat" class="form-control" value="{{ $data->device->mark }}" placeholder="Merek">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Kapasitas *
								</label>
								<input type="hidden" name="hidden_kapasitas_perangkat" class="form-control" value="{{ $data->device->capacity }}" placeholder="Kapasitas">
								<input type="text" name="kapasitas_perangkat" class="form-control" value="{{ $data->device->capacity }}" placeholder="Kapasitas">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Negara Pembuat *
								</label>
								<input type="hidden" name="hidden_pembuat_perangkat" class="form-control" value="{{ $data->device->manufactured_by }}" placeholder="Negara Pembuat">
								<input type="text" name="pembuat_perangkat" class="form-control" value="{{ $data->device->manufactured_by }}" placeholder="Negara Pembuat">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Model / Tipe *
								</label>
								<input type="hidden" name="hidden_model_perangkat" class="form-control" value="{{ $data->device->model }}" placeholder="Model/Tipe">
								<input type="text" name="model_perangkat" class="form-control" value="{{ $data->device->model }}" placeholder="Model/Tipe">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Referensi Uji *
								</label>
								<input type="hidden" name="hidden_ref_perangkat" class="form-control" value="{{ $data->device->test_reference }}" placeholder="Referensi Uji">
								@if($data->examination_type_id == '1')
								<select class="form-control" id="cmb-ref-perangkat" name="cmb-ref-perangkat">
										<option value=""> - Pilih Referensi Uji - </option>
									@foreach($data_stels as $item)
										@if($data->device->test_reference == $item->code)
											<option value="{{ $item->code }}" selected>{{ $item->code }} || {{ $item->name }}</option>
										@else
											<option value="{{ $item->code }}">{{ $item->code }} || {{ $item->name }}</option>
										@endif
									@endforeach
								</select>
								@else
									<input type="text" name="ref_perangkat" class="form-control" value="{{ $data->device->test_reference }}" placeholder="Referensi Uji">
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Serial Number *
								</label>
								<input type="hidden" name="hidden_sn_perangkat" class="form-control" value="{{ $data->device->serial_number }}" placeholder="Serial Number">
								<input type="text" name="sn_perangkat" class="form-control" value="{{ $data->device->serial_number }}" placeholder="Serial Number">
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						Dokumen Pendukung
					</legend>
					<div class="row">
						@if($data->attachment != '')
							<div class="col-md-12">
								<div class="form-group">
									<a href="{{URL::to('/admin/examination/download/'.$data->id)}}">Download Form Uji</a>
								</div>
							</div>
						@endif

						@foreach($data->media as $item)
							@if($item->attachment != '')
								<div class="col-md-12">
									<div class="form-group">
										<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name)}}">Download {{ $item->name }}</a>
									</div>
								</div>
							@endif
						@endforeach

						@if($data->device->certificate != '')
							<div class="col-md-12">
								<div class="form-group">
									<a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}">Download Sertifikat</a>
								</div>
							</div>
						@endif
						<div class="col-md-12">
							<a style=" color:white !important;" href="{{URL::to('admin/examination/'.$data->id)}}">
								<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left" style="margin-right:1%">
								Kembali
								</button>
							</a>
							<button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
								Simpan
							</button>
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
	$('#cmb-ref-perangkat').chosen();
	// $('#cmb-ref-perangkat').val(0);
	$('#cmb-ref-perangkat').trigger("chosen:updated");
</script>
@endsection