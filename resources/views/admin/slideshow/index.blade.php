@extends('layouts.app')

@section('content')
<style type="text/css">
	td { cursor: grab; }
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Slideshow</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Slideshow</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
		        <div class="col-md-6">
		                <a style=" color:white !important;" href="{{URL::to('/admin/slideshow/create')}}">
		            		<button type="button" class="btn btn-wide btn-green btn-squared" >
		                		Tambah Slideshow
		            		</button>         
		                </a>
		        </div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <em class="ti-search"></em>
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
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Slideshow List Table</caption>
							<thead>
								<tr>
                                    <th class="center" scope="col">Aksi</th>
									<th class="center" scope="col">Judul</th>
									<th class="center" scope="col">Headline</th>
									<th class="center" scope="col">Gambar</th>
									<th class="center" scope="col">Timeout</th>
                                    <th class="center" scope="col">Status</th>
									<th class="center" scope="col"></th>
								</tr>
							</thead>
							<tbody class="row_position">
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr id="{{ $item->id }}">
										<td class="center">{{ $item->title }}</td>
										<td class="center">{{ $item->headline }}</td>
										<td class="center"><img src="{{asset('media/slideshow/'.$item->image)}}" width="240" alt="telkom-test-house-media-slideshow"/></td>
										<td class="center">{{ $item->timeout }}s</td>
										@if($item->is_active)
	                                    	<td class="center"><span class="label label-sm label-success">Active</span></td>
	                                    @else
	                                    	<td class="center"><span class="label label-sm label-warning">Not Active</span></td>
	                                    @endif
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/slideshow/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												{!! Form::open(array('url' => 'admin/slideshow/'.$item->id, 'method' => 'DELETE')) !!}
													{!! csrf_field() !!}
													<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><em class="fa fa-times fa fa-white"></em></button>
												{!! Form::close() !!}
											</div>
										</td>
										<td class="center"><em class="fa fa-reorder"></em></td>
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
<script type="text/javascript">
	$( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });

    function updateOrder(data){
    	$.ajax({
			type: 'POST',
			url: 'orderSlideshow',
			data: {'_token':"{{ csrf_token() }}", 'position':data},
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success: function (data) {
				console.log(data);
				document.getElementById("overlay").style.display="none";	
			},
		});
    }

	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_slideshow_autocomplete/'+request.term,
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


			// focus: function( event, ui ) {
				// $( "#search_value" ).val( ui.item.label );
				// return false;
			// },

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
					search:document.getElementById("search_value").value			
				};
				document.location.href = baseUrl+'/admin/slideshow?'+jQuery.param(params);
	        }
	    });
	});
</script>>
@endsection