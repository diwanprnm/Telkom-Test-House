@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Balas Feedback</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Feedback</span>
					</li>
					<li class="active">
						<span>Balas</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/feedback/reply', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Balas Feedback
						</legend>
						<input type="hidden" name="id" class="form-control" value="{{ $data->id }}">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										From *
									</label>
									<input type="text" name="email" class="form-control" placeholder="Title" value="{{ $data->email }}" disabled>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Subject *
									</label>
									<input type="text" name="subject" class="form-control" placeholder="Title" value="{{ $data->subject }}" disabled>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Pesan *
									</label>
									<textarea 
name="message" class="form-control" height="400px" readonly>{{ $data->message }} </textarea>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Reply *
									</label>
									<textarea id="description" name="description" required></textarea>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left" name="submit" value="submit">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/feedback')}}">
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

<script src={{ asset("vendor/unisharp/laravel-ckeditor/ckeditor.js") }}></script>
<script>
    CKEDITOR.replace( 'description', {
    filebrowserImageBrowseUrl: "{{ URL::to('/laravel-filemanager?type=Images') }}",
    filebrowserImageUploadUrl: "{{ URL::to('/laravel-filemanager/upload?type=Images&_token='.csrf_token()) }}",
    filebrowserBrowseUrl: "{{ URL::to('/laravel-filemanager?type=Files') }}",
    filebrowserUploadUrl: "{{ URL::to('/laravel-filemanager/upload?type=Files&_token='.csrf_token()) }}"
  });
</script>
@endsection
