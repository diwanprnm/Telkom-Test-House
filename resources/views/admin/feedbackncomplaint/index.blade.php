@extends('layouts.app')

@section('content')
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
					<h1 class="mainTitle">Feedback dan Complaint</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li class="active">
						<span>Rekap Feedback dan Complaint</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
	        	<div class="col-md-6">
	    			<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	            <div class="col-md-12 panel panel-info">
			    	<div id="collapse1" class="panel-collapse collapse">
			     		<fieldset>
							<legend>
								Filter
							</legend>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal Masuk Barang
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
		                        </div>
		                        <div class="col-md-6">
									<div class="form-group">
										<label>
											&nbsp;
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date }}" name="before_date" id="before_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
		                        </div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tipe Pengujian
										</label>
										<select id="type" name="type" class="cs-select cs-skin-elastic" required>
											@if($filterType == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($filterType == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@foreach($type as $item)
												@if($item->id == $filterType)
													<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->id }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Nama Perusahaan
										</label>
										<select class="form-control" id="company" name="company" class="chosen-company">
												@if ($filterCompany == '')
													<option value="" disabled selected> - Pilih Perusahaan - </option>
												@endif
												@if ($filterCompany == 'all')
													<option value="all" selected>All</option>
												@else
													<option value="all">All</option>
												@endif
											@foreach($company as $item)
												@if($item->name == $filterCompany)
													<option value="{{ $item->name }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->name }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Lab
										</label>
										<select id="lab" name="lab" class="cs-select cs-skin-elastic" required>
											@if($filterLab == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($filterLab == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@foreach($lab as $item)
												@if($item->id == $filterLab)
													<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->id }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
		                    </div>
						</fieldset>
			    	</div>
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
							<caption>Feedback and Complaint Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nama</th>
                                    <th class="center" scope="col">Perusahaan</th>
                                    <th class="center" scope="col">Perangkat</th>
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas</th>
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
											<td class="center">{{ $item->user->name }}</td>
											<td class="center">{{ $item->examination->company->name }}</td>
											<td class="center">{{ $item->examination->device->name }}</td>
											<td class="center">{{ $item->examination->device->mark }}</td>
											<td class="center">{{ $item->examination->device->capacity }}</td>
											<td class="center"><a href="{{URL::to('/cetakKepuasanKonsumen/'.$item->examination->id)}}" target="_blank"> Download Feedback</a> || <a href="{{URL::to('/cetakComplaint/'.$item->examination->id)}}" target="_blank"> Download Complaint</a></td>
										</tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=8 class="center">
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
	                             {{$data->appends(array('search' => $search,'before_date' => $before_date,'after_date' => $after_date,'type' => $filterType,'company' => $filterCompany,'lab' => $filterLab,'sort_by' => $sort_by,'sort_type' => $sort_type))->links()}}
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
		FormElements.init();
	});
	$('#company').chosen();
	$('#company').trigger("chosen:updated");
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
				};
				document.location.href = baseUrl+'/admin/feedbackncomplaint?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			params['search'] = search_value;			
			document.location.href = baseUrl+'/feedbackncomplaint/excel?'+jQuery.param(params);
	    };
	});
</script>
@endsection