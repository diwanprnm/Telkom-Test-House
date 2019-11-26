@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Lab Pengujian Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Lab Pengujian</span>
					</li>
					<li class="active">
						<span>Tambah</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/labs', 'method' => 'POST')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Tambah Lab Pengujian Baru
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Lab" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Kode *
									</label>
									<input type="text" name="lab_code" class="form-control" placeholder="Kode Lab" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Inisial *
									</label>
									<input type="text" name="lab_init" class="form-control" placeholder="Inisial Lab" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Deskripsi *
									</label>
									<textarea type="text" name="description" class="form-control" placeholder="Deskripsi Lab" required></textarea>
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
										<!-- <option value="0">Not Active</option> -->
										
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/labs')}}">
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
@endsection