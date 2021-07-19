@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit FAQ</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Role</span>
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
				{!! Form::open(array('url' => 'admin/faq/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit FAQ
						</legend>
						<div class="row">
						 
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Pertanyaan *
									</label>
									<input type="text" name="question" class="form-control" placeholder="Pertanyaan" value="{{$data->question}}" required>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label>
										Jawaban *
									</label>
									<textarea type="text" id="answer" name="answer" class="form-control" placeholder="Jawaban...." required>{{$data->answer}}</textarea>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="status" class="cs-select cs-skin-elastic" required>
										<option value="" disabled selected>Select...</option>
										<option value="1" @if ($data->is_active) selected @endif>Active</option>
										<option value="0" @if (!$data->is_active) selected @endif>Not Active</option>
									</select>
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
<script src={{ asset("assets/js/form-elements.js") }}></script>
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