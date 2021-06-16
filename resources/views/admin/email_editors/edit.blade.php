@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Email</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Tools</span>
					</li>
					<li>
						<span>Email Editor</span>
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
				{!! Form::open(array('url' => 'admin/email_editors/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Email
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama" value="{{ $data->name }}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Subject *
									</label>
									<input type="text" name="subject" class="form-control" placeholder="Subject email" required value="{{ $data->subject }}"">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Direktori File *
									</label>
									<input type="text" name="dir_name" class="form-control" placeholder="Direktori File" value="{{ $data->dir_name }}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Konten/Isi *
									</label>
									<?php 
										$content = str_replace('&', '&amp;', $data->content);
									?>
									<textarea type="text" id="content" name="content" class="form-control" placeholder="Konten Email ..."><?= $content ?></textarea>
								</div>
							</div>
	            			<div class="col-md-12">
								<div class="form-group">
									<label>
										Signature
									</label>
									@if($data->signature != '')
										<a href="{{ URL::to('/admin/email_editors/media/'.$data->id.'/signature') }}" target="_blank">Lihat File Signature</a>
									@endif
									<input type="file" id="signature" name="signature" accept="application/pdf, image/*">
								</div>
							</div>
	                        
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/email_editors')}}">
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
<script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
	});
	ClassicEditor
		.create(document.querySelector('#content'))
		.then(content => {
			console.log("ini isi contentnya");
			console.log(content.getData());
		})
		.catch(err => {
			console.log(err);
		});
</script>
@endsection