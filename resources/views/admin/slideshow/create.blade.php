@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Slideshow Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Slideshow</span>
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
				{!! Form::open(array('url' => 'admin/slideshow', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Tambah Slideshow Baru
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Judul *
									</label>
									<input type="text" name="title" class="form-control" placeholder="Judul" required>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Headline *
									</label>
									<input type="text" name="headline" class="form-control" placeholder="Headline" required>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Gambar *
									</label>
									<input type="file" name="image" class="form-control" accept="image/*" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Timeout (dalam detik)
									</label>
									<input type="number" name="timeout" min="0" class="form-control" placeholder="... s">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic">
										<option value="" disabled selected>Select...</option>
										<option value="1">Active</option>
										<option value="0">Not Active</option>
										
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                                	<a style=" color:white !important;" href="{{URL::to('/admin/slideshow')}}">
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
<script src={{ asset("assets/js/bootstrap-colorpicker.min.js") }}></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();

		$(function() {
	        $('#cp2').colorpicker();
	    });
	});
</script>
@endsection