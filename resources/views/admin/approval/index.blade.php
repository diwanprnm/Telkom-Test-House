@extends('layouts.app')

@section('content')

<input type="hide" id="hide_approval_id" name="hide_approval_id">
<div class="modal fade" id="myModal_assign" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Verifikasi Akun, Mohon Masukkan Password Akun Anda!</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<div class="form-group">
								<label for="password">Password:</label>
								<input id="password" type="password" name="password" class="form-control" placeholder="Password">
							</div>
						</th>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="button" id="btn-modal-assign" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<style type="text/css">
	.chosen-container.chosen-container-single {
		width: 100% !important;
	}   
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Approval</h1>
				</div>
				<ol class="breadcrumb">
					<li class="active">
						<span>Approval</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
	        	<div class="col-md-6 pull-right">
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
							<caption>Approval Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Jenis Dokumen</th>
                                    <th class="center" scope="col">Attachment</th>
                                    <th class="center" scope="col">Dibuat pada</th>
									<th class="center" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@php
									$no=1;
								@endphp
								@if(count($data)>0)
									@foreach($data as $item)
										<tr>
											<td class="center">{{$no}}</td>
											<td class="center">{{ $item->approval->authentikasi->name }}</td>
											<td class="center"><a href="{{ \Storage::disk('minio')->url($item->approval->reference_table.'/'.$item->approval->reference_id.'/'.$item->approval->attachment) }}" target="_blank">{{ $item->approval->attachment }}</a></td>
											<td class="center">{{ $item->created_at }}</td>
											<td class="center">
											@if($item->approval->status)
												<a href="{{URL::to('admin/approval/'.$item->approval->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Detail" target="_blank"><em class="fa fa-eye"></em></a>
											@else
												@if($item->approve_date)
													Approved
												@else
													<a data-toggle="modal" data-target="#myModal_assign" onclick="document.getElementById('hide_approval_id').value = '{{ $item->id }}'" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil-square-o"></em></a>
												@endif
											@endif
											</td>
										</tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=5 class="center">
											Data Not Found
										</td>
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
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
				};
				document.location.href = baseUrl+'/admin/approval?'+jQuery.param(params);
	        }
	    });

		$('#myModal_assign').on('shown.bs.modal', function () {
		    $('#password').focus();
		});

		$('#password').keydown(function(event) {
	        if (event.keyCode == 13) {
	            verifyAccount();
	        }
	    });

		$('#btn-modal-assign').click(function () {
			verifyAccount();
		});
	});

	function verifyAccount(){
		var baseUrl = "{{URL::to('/')}}";
		var password = document.getElementById('password').value;
		var approval_id = document.getElementById('hide_approval_id').value;
		if(password == ''){
			$('#myModal_assign').modal('show');
			return false;
		}else{
			$('#myModal_assign').modal('hide');
			if (confirm('Are you sure want to approve this document?')) {
				document.getElementById("overlay").style.display="inherit";	
				document.location.href = baseUrl+'/admin/approval/assign/'+approval_id+'/'+encodeURIComponent(encodeURIComponent(password));
			}
		}
	}
</script>
@endsection