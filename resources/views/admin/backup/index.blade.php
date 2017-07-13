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
				 	<a class="btn btn-wide btn-primary pull-left" href="{{url('do_backup')}}"><i class="fa fa-database"></i> Backup NOW</a>
				 
				</div>
				<div class="col-md-6">
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
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center">No</th> 
									<th class="center">Username</th> 
									<th class="center">File</th>
									<th class="center">Waktu Backup</th>
									<th class="center">Action</th> 
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->name }}</td> 
										<td class="center"><a href="{{storage_path().'/app/public/backup-data/'.$item->file}}">{{ $item->file }}</a></td>
										<td class="center">{{ $item->created_at }}</td> 
										<td class="center">
											<!-- <div> 
												{!! Form::open(array('url' => 'admin/restore/', 'method' => 'POST')) !!}
													{!! csrf_field() !!}
													<input type="hidden" name="id" value="{{$item->id}}">
													<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to Restore ?')"><i class="fa fa-database"></i>Restore</button>
												{!! Form::close() !!}
											</div> -->
											<div> 
											 
													<a class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')" href="{{url('admin/delete/'.$item->id)}}"><i class="fa fa-times fa fa-white"></i></a>
											 
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