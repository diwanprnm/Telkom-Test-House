@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Poin Pertanyaan Kuisioner</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Data Master</span>
					</li>
					<li class="active">
						<span>Poin Pertanyaan Kuisioner</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row"> 
				<div class="col-md-12">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <i class="ti-search"></i>
	                </span>
	            </div> 
	        </div>

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

			<div class="row">
				<div class="col-md-6 pull-right" style="margin-bottom:10px">
					<a style=" color:white !important;" href="{{URL::to('/admin/questionerquestion/create')}}">
						<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Tambah Poin Pertanyaan
						</button>         
					</a>
		        </div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center">No</th> 
									<th class="center">Nama</th> 
									<th class="center">Status</th> 
                                    <th class="center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{ $no }}</td>
										<td class="left">{{ $item->question }} 
											@if($item->is_essay)
												(Essay)
											@endif
										</td>
										@if($item->is_active)
	                                    	<td class="center"><span class="label label-sm label-success">Active</span></td>
	                                    @else
	                                    	<td class="center"><span class="label label-sm label-warning">Not Active</span></td>
	                                    @endif
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/questionerquestion/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
												{!! Form::open(array('url' => 'admin/questionerquestion/'.$item->id, 'method' => 'DELETE')) !!}
													{!! csrf_field() !!}
													<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><i class="fa fa-times fa fa-white"></i></button>
												{!! Form::close() !!}
											</div>
										</td>
									</tr>
								<?php $no++ ?>
								@endforeach
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								<?php echo $data->appends(array('search' => $search))->links(); ?>
							</div>
						</div>
					</div>
				</div>
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
<script type="text/javascript">
	// $( function() {
		// $( "#search_value" ).autocomplete({
			// minLength: 3,
			// source: function (request, response) {
				// $.ajax({
					// type: 'GET',
					// url: 'adm_stel_autocomplete/'+request.term,
					// dataType: "json",
					// cache: false,
					// success: function (data) {
						// console.log(data);
						// response($.map(data, function (item) {
							// return {
								// label:item.autosuggest
							// };
						// }));
					// },
				// });
			// }, 
			// select: function( event, ui ) {
				// $( "#search_value" ).val( ui.item.label );
				// return false;
			// }
		// })

		// .autocomplete( "instance" )._renderItem = function( ul, item ) {
			// return $( "<li>" )
			// .append( "<div>" + item.label + "</div>" )
			// .appendTo( ul );
		// };
	// });
	
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value
				};
				document.location.href = baseUrl+'/admin/questionerquestion?'+jQuery.param(params);
	        }
	    });
	});
</script>
@endsection