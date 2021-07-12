@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah FAQ Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>FAQ</span>
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
				Data FAQ sudah ada!
			</div>
		@endif
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/faq', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{{ csrf_field() }}
    				<fieldset>
						<legend>
							Tambah FAQ Baru
						</legend>
						<div class="row"> 
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Kategory
									</label>
									<select id="category" name="category" class="cs-select cs-skin-elastic" required>
										@if (old('category') == '1')
											<option value="1" selected>Registrasi Akun</option>
											<option value="2">STEL dan Pengujian Perangkat</option>
											<option value="3">Uji Fungsi</option>
											<option value="4">Invoice dan Pembayaran</option>
											<option value="5">SPK</option>
											<option value="6">Kapabilitas TTH</option>
											<option value="7">Pengambilan Laporan dan Sertifikat</option>
										@elseif(old('category') == '2')
											<option value="1">Registrasi Akun</option>
											<option value="2" selected>STEL dan Pengujian Perangkat</option>
											<option value="3">Uji Fungsi</option>
											<option value="4">Invoice dan Pembayaran</option>
											<option value="5">SPK</option>
											<option value="6">Kapabilitas TTH</option>
											<option value="7">Pengambilan Laporan dan Sertifikat</option>
										@elseif (old('category') == '3')
											<option value="1">Registrasi Akun</option>
											<option value="2">STEL dan Pengujian Perangkat</option>
											<option value="3"selected>Uji Fungsi</option>
											<option value="4">Invoice dan Pembayaran</option>
											<option value="5">SPK</option>
											<option value="6">Kapabilitas TTH</option>
											<option value="7">Pengambilan Laporan dan Sertifikat</option>
										@elseif (old('category') == '4')
											<option value="1">Registrasi Akun</option>
											<option value="2">STEL dan Pengujian Perangkat</option>
											<option value="3">Uji Fungsi</option>
											<option value="4" selected>Invoice dan Pembayaran</option>
											<option value="5">SPK</option>
											<option value="6">Kapabilitas TTH</option>
											<option value="7">Pengambilan Laporan dan Sertifikat</option>
										@elseif (old('category') == '5')
											<option value="1">Registrasi Akun</option>
											<option value="2">STEL dan Pengujian Perangkat</option>
											<option value="3">Uji Fungsi</option>
											<option value="4">Invoice dan Pembayaran</option>
											<option value="5" selected>SPK</option>
											<option value="6">Kapabilitas TTH</option>
											<option value="7">Pengambilan Laporan dan Sertifikat</option>
										@elseif (old('category') == '6')
											<option value="1">Registrasi Akun</option>
											<option value="2">STEL dan Pengujian Perangkat</option>
											<option value="3">Uji Fungsi</option>
											<option value="4">Invoice dan Pembayaran</option>
											<option value="5">SPK</option>
											<option value="6" selected>Kapabilitas TTH</option>
											<option value="7">Pengambilan Laporan dan Sertifikat</option>
										@elseif (old('category') == '7')
											<option value="1">Registrasi Akun</option>
											<option value="2">STEL dan Pengujian Perangkat</option>
											<option value="3">Uji Fungsi</option>
											<option value="4">Invoice dan Pembayaran</option>
											<option value="5">SPK</option>
											<option value="6">Kapabilitas TTH</option>
											<option value="7" selected>Pengambilan Laporan dan Sertifikat</option>
										@else
											<option value="" disabled selected>Select Category</option>
											<option value="1">Registrasi Akun</option>
											<option value="2">STEL dan Pengujian Perangkat</option>
											<option value="3">Uji Fungsi</option>
											<option value="4">Invoice dan Pembayaran</option>
											<option value="5">SPK</option>
											<option value="6">Kapabilitas TTH</option>
											<option value="7">Pengambilan Laporan dan Sertifikat</option>
										@endif
									</select>
								</div>	

								<div class="form-group">
									<label>
										Pertanyaan *
									</label>
									<input type="text" name="question" class="form-control" value="{{ old('question') }}" placeholder="Pertanyaan" required>
								</div>

								<div class="form-group">
									<label>
										Jawaban *
									</label>
									<textarea type="text" id="answer" name="answer" class="form-control" placeholder="Jawaban...."></textarea>
								</div>
							</div> 
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/faq')}}">
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
<script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
	});
		ClassicEditor
			.create(document.querySelector('#answer'))
			.then(answer => {
				console.log("ini isi contentnya");
				console.log(answer.getData());
			})
			.catch(err => {
				console.log(err);
			});
</script>
@endsection