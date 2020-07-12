@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Backup & Restore</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Backup & Restore</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
		        <div class="col-md-6"> 
				 	<a class="btn btn-wide btn-primary pull-left" href="{{url('do_backup')}}"><em class="fa fa-database"></em> Backup NOW</a>
				 
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
							<caption>Backup list table</caption> 
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Username</th> 
									<th class="center" scope="col">File</th>
									<th class="center" scope="col">Waktu Backup</th>
									<th class="center" scope="col">Action</th> 
								</tr>
							</thead>
							<tbody>
								
							@if (count($data))
								@php
									$no=1;
								@endphp
								@foreach($data as $item)
								<tr>
									<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
									<td class="center">{{ $item->name }}</td> 
									<td class="center"><a href="{{url('admin/backup/'.$item->id.'/media')}}">{{ $item->file }}</a></td>
									<td class="center">{{ $item->created_at }}</td> 
									<td class="center">
										<div>
											<a class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')" href="{{url('admin/backup/'.$item->id.'/delete')}}">
												<em class="fa fa-times fa fa-white"></em>
											</a>
										</div>
									</td>
										@php
											$no++
										@endphp
								</tr>
								@endforeach
								@else
								<tr>
									<td class="center" colspan="5">{{$message}}</td>
								</tr>
								@endif
								
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{$data->appends(array('search' => $search))->links()}}
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
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value			
				};
				document.location.href = baseUrl+'/admin/backup?'+jQuery.param(params);
	        }
	    });
	});
</script>>
@endsection