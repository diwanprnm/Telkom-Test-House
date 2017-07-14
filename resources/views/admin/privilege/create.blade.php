@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Privilege Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Privilege</span>
					</li>
					<li class="active">
						<span>Tambah</span>
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
		
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/privilege', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Tambah Privilege Baru
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Admin *
									</label>
									<select class="form-control" id="user_id" name="user_id" required>
										<option value="" disabled selected>Select...</option>
										@foreach($user as $item)
											<option value="{{$item->id}}" @if(old('user_id') == $item->id) {{ 'selected' }} @endif>{{$item->name}} || {{$item->email}}</option>
										@endforeach
									</select>
								</div>
							</div>
							
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege1" name="check-privilege[]" value="1">
									<label>
										Registrasi
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege2" name="check-privilege[]" value="2">
									<label>
										Uji Fungsi
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege3" name="check-privilege[]" value="3">
									<label>
										Tinjauan Kontrak
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege4" name="check-privilege[]" value="4">
									<label>
										SPB
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege5" name="check-privilege[]" value="5">
									<label>
										Pembayaran
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege6" name="check-privilege[]" value="6">
									<label>
										Pembuatan SPK
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege7" name="check-privilege[]" value="7">
									<label>
										Pelaksanaan Uji
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege8" name="check-privilege[]" value="8">
									<label>
										Laporan Uji
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege9" name="check-privilege[]" value="9">
									<label>
										Sidang QA
									</label>
								</div>
							</div>
	                        
	                        <div class="col-md-4">
								<div class="form-group">
									<input type="checkbox" class="check-privilege10" name="check-privilege[]" value="10">
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
	$('#user_id').chosen();
	// $('#user_id').val(0);
	$('#user_id').trigger("chosen:updated");
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
@endsection