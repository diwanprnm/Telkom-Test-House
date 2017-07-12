@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Question Privilege</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Question Privilege</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="row">
		        <div class="col-md-6">
		                <a style=" color:white !important;" href="{{URL::to('/admin/questionpriv/create')}}">
		            		<button type="button" class="btn btn-wide btn-green btn-squared" >
		                		Tambah Question Privilege
		            		</button>         
		                </a>
		        </div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <i class="ti-search"></i>
	                </span>
	            </div>
	        </div>
			
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
									<th class="center">Nama</th>
									<th class="center">Email</th>
									<th class="center">Kategori Pertanyaan</th>
									<th class="center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=0; 
								$temp_user_id="unlogicalUUIDBL"; 
								$temp_user_name=""; 
								$temp_user_email=""; 
								$temp_qn=""; 
								?>
								@foreach($data as $item)
									@if($item->user_id != $temp_user_id)
										@if($temp_user_id != "unlogicalUUIDBL")
										<tr>
											<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
											<td class="center">{{ $temp_user_name }}</td>
											<td class="center">{{ $temp_user_email }}</td>
											<td class="center">{{ $temp_qn }}.</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/questionpriv/'.$temp_user_id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
													{!! Form::open(array('url' => 'admin/questionpriv/'.$temp_user_id, 'method' => 'DELETE')) !!}
														{!! csrf_field() !!}
														<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><i class="fa fa-times fa fa-white"></i></button>
													{!! Form::close() !!}
												</div>
											</td>
										</tr>
										@endif
										<?php 
										$temp_user_id=$item->user_id;
										$temp_user_name=$item->user->name;
										$temp_user_email=$item->user->email;
										$temp_qn=$item->question->name; 
										$no++;
										?>
									@else
										<?php $temp_qn=$temp_qn.", ".$item->question->name; ?>
									@endif
								@endforeach
								<tr>
									<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
									<td class="center">{{ $temp_user_name }}</td>
									<td class="center">{{ $temp_user_email }}</td>
									<td class="center">{{ $temp_qn }}.</td>
									<td class="center">
										<div>
											<a href="{{URL::to('admin/questionpriv/'.$temp_user_id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
											{!! Form::open(array('url' => 'admin/questionpriv/'.$temp_user_id, 'method' => 'DELETE')) !!}
												{!! csrf_field() !!}
												<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><i class="fa fa-times fa fa-white"></i></button>
											{!! Form::close() !!}
										</div>
									</td>
								</tr>
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
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value
				};
				document.location.href = baseUrl+'/admin/questionpriv?'+jQuery.param(params);
	        }
	    });
	});
</script>
@endsection