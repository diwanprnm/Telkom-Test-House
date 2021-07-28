@extends('layouts.app')

@section('content')

<style>

.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.btn:hover {
  background-color: rgb(219, 171, 67);
}

/* Create an active/current tablink class */
.btn.active {
  background-color: rgb(0, 46, 253);
}
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
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
									<input type="text" name="dir_name" class="form-control" placeholder="Direktori File" value="{{ $data->dir_name }}" required disabled>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Konten/Isi *
									</label>
									<ul class="tabs">
										<li class="btn tab-1 tablinks" data-tab="tab-1" id="defaultOpen" onclick="openTab(event, 'Input')">Write</li>
										<li class="btn tab-2 tablinks" data-tab="tab-2" id="previewtab" onclick="openTab(event, 'Preview');updateFields()">Preview</li>
									</ul>

									<div id="Input" class="tabcontent">
										<textarea type="text" id="content-block" name="content" style="display: block" class="form-control" placeholder="Konten Email ..."><?= str_replace('&', '&amp;', $data->content) ?></textarea>
									</div>
									<div id="Preview" class="tabcontent">

									</div>
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
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
	});

	var myContent
	ClassicEditor
		.create(document.querySelector('#content-block'))
		.then(content => {
			myContent = content
		})
		.catch(err => {
			console.log(err);
		});

	
	function updateFields() {	
		var value = myContent.getData();
		document.getElementById('Preview').innerHTML = value;
	}
</script>
<script>
	function openTab(evt, tab) {
	  var i, tabcontent, tablinks;
	  tabcontent = document.getElementsByClassName("tabcontent");
	  for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	  }
	  tablinks = document.getElementsByClassName("tablinks");
	  for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	  }
	  document.getElementById(tab).style.display = "block";
	  evt.currentTarget.className += " active";
	}

	document.getElementById("defaultOpen").click();

</script>
@endsection