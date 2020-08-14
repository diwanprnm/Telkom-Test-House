@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Privilege</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Privilege</span>
					</li>
					<li class="active">
						<span>Edit</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		
		@if (Session::get('error'))
			<div class="alert alert-error alert-danger">
				{{ Session::get('error') }}
			</div>
		@endif

		@php $CHECKED_STRING = 'checked'; @endphp
		
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/privilege/'.$data->user_id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Privilege
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama || Email
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama" value="{{ $data->user_name }} || {{ $data->user_email }}" readonly>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege1" name="check-privilege[]" value="1" @php if($data->registration_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Registrasi
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege2" name="check-privilege[]" value="2" @php if($data->function_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Uji Fungsi
									</label>
								</div>
							</div>
	                        
							<div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege3" name="check-privilege[]" value="3" @php if($data->contract_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Tinjauan Kontrak
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege4" name="check-privilege[]" value="4" @php if($data->spb_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										SPB
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege5" name="check-privilege[]" value="5" @php if($data->payment_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Pembayaran
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege6" name="check-privilege[]" value="6" @php if($data->spk_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Pembuatan SPK
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege7" name="check-privilege[]" value="7" @php if($data->examination_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Pelaksanaan Uji
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege8" name="check-privilege[]" value="8" @php if($data->resume_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Laporan Uji
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege9" name="check-privilege[]" value="9" @php if($data->qa_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Sidang QA
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege10" name="check-privilege[]" value="10" @php if($data->certificate_status == 1){echo $CHECKED_STRING;}@endphp>
									<label>
										Penerbitan Sertifikat
									</label>
								</div>
							</div>
							
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                                <a style=" color:white !important;" href="{{URL::to('/admin/privilege')}}">
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
</script>
@endsection