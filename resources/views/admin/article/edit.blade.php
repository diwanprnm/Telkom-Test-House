@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Artikel</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Artikel</span>
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
				{!! Form::open(array('url' => 'admin/article/'.$data->id, 'method' => 'PATCH', 'enctype' => 'multipart/form-data')) !!}
				{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Artikel
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Judul *
									</label>
									<input type="text" name="title" class="form-control" placeholder="Judul" value="{{ $data->title }}" disabled>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Type*
									</label>
									<input type="text" name="type" class="form-control" placeholder="Type" value="{{ $data->type }}" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
										@if($data->is_active)
											<option value="1" selected>Active</option>
											<option value="0">Not Active</option>
										@else
											<option value="1">Active</option>
											<option value="0" selected>Not Active</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Deskripsi Bahasa Indonesia *
									</label>
									<textarea id="description" name="description" required>{{ $data->description }}</textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Deskripsi Bahasa Inggris *
									</label>
									<textarea id="description_english" name="description_english" required>{{ $data->description_english }}</textarea>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left" name="submit" value="submit">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/article')}}">
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

    CKEDITOR.replace( 'description_english', {
    filebrowserImageBrowseUrl: "{{ URL::to('/laravel-filemanager?type=Images') }}",
    filebrowserImageUploadUrl: "{{ URL::to('/laravel-filemanager/upload?type=Images&_token='.csrf_token()) }}",
    filebrowserBrowseUrl: "{{ URL::to('/laravel-filemanager?type=Files') }}",
    filebrowserUploadUrl: "{{ URL::to('/laravel-filemanager/upload?type=Files&_token='.csrf_token()) }}"
  });
</script>
@endsection