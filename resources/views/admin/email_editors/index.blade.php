@extends('layouts.app')

@section('content')

<style>
.right{
	display: flex;
	text-align: right;
	justify-content: right;
	position: relative;
}
</style>

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Email Editor</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Email Editor</span>
					</li>
				</ol>
			</div>
		</section>


		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			@if (Session::get('error'))
				<div class="alert alert-error alert-danger">
					{{ Session::get('error') }}
				</div>
			@endif
			
			@if (Session::get('message'))
				<div class="alert alert-info">
					{{ Session::get('message') }}
				</div>
			@endif
			<div class="col-md-12 panel panel-info">
				<div class="panel">					
					{!! Form::open(array('url' => 'admin/email_editors/update_logo_signature', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
					<fieldset>
						<legend>
							Update Logo
						</legend>
						<div class="row">
							<div class="col-md-12">
								@if (isset($data[0])) 
								<img src="{{ \Storage::disk('minio')->url('logo/'.$data[0]->logo) }}" width="240" alt="Logo"/>
								@endif
								<input type="file" name="logo" class="form-control" accept="image/jpg, image/jpeg, image/png" required>
							</div>
							<div class="col-md-12">
								<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
									Update
								</button>
							</div>
						</div>
					</fieldset>
					{!! Form::close() !!}
				</div>
			</div>
	        <div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<span class="input-icon input-icon-right search-table">
						<input id="search_value" type="text" name="search" placeholder="Search" id="form-field-17" class="form-control" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	        </div>

	        <div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption> </caption>
							<thead>
								<tr>
									<th class="center" id="no">No</th>
									<th class="center" id="name">Nama</th>
									<th class="center" id="subject">Subjek Email</th>
									<th class="center" id="dir_file">Direktori File</th>
									<th class="center" colspan="2" scope="colgroup">Aksi</th>  
								</tr>
							</thead>
							<tbody>
								@php $no=1; @endphp
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="left">{{ $item->name }}</td>
										<td class="left">{{ $item->subject }}</td>
										<td class="left">{{ $item->dir_name }}</td>
										<td class="center">
											<div>
												<a href="{{URL::to('admin/email_editors/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
											</div>
										</td>
										<td class="center">
											<div>
												{!! Form::open(array('url' => 'admin/email_editors/'.$item->id, 'method' => 'DELETE')) !!}
													{!! csrf_field() !!}
												<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><em class="fa fa-times fa fa-white"></em></button>
												{!! Form::close() !!}
											</div>
										</td>
									</tr>
								@php $no++ @endphp
								@endforeach
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								@php echo $data->appends(array('search' => $search))->links(); @endphp
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
		<div class="col-md-12 panel panel-info">
			<div class="panel">
				{!! Form::open(array('url' => 'admin/email_editors/update_logo_signature', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
				{!! csrf_field() !!}
				<fieldset>
					<legend>
						Update Signature
					</legend>
					<div class="row">
						<div class="col-md-12">
							@if (isset($data[0]))
							<textarea type="text" id="signature" name="signature" class="form-control" placeholder="Signature ...">{!! str_replace('&', '&amp;', $data[0]->signature); !!}</textarea>
							@endif
						</div>
						<div class="col-md-12">
							<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
								Update
							</button>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
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
<script src={{ asset("assets/js/ckeditor.js") }}></script>
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
<script type="text/javascript">
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_dev_autocomplete/'+request.term,
					dataType: "json",
					cache: false,
					success: function (data) {
						console.log(data);
						response($.map(data, function (item) {
							return {
								label:item.autosuggest
							};
						}));
					},
				});
			},

			select: function( event, ui ) {
				$( "#search_value" ).val( ui.item.label );
				return false;
			}
		})

		.autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<div>" + item.label + "</div>" )
			.appendTo( ul );
		};
	});
	
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
				};
				document.location.href = baseUrl+'/admin/email_editors?'+jQuery.param(params);
	        }
	    });
	});
	ClassicEditor
		.create(document.querySelector('#signature'))
		.then(content => {
			console.log("ini isi contentnya");
			console.log(content.getData());
		})
		.catch(err => {
			console.log(err);
		});
</script>>
@endsection