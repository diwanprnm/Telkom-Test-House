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
					<h1 class="mainTitle">Rekap Kuitansi dan Faktur Pajak</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li class="active">
						<span>Rekap Kuitansi dan Faktur Pajak</span>
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
                                            @if($filterType == 'SPB')
                                                <option value="SPB" selected>SPB</option>
											@else
                                                <option value="SPB">SPB</option>
                                            @endif
                                            @if($filterType == 'STEL')
                                                <option value="STEL" selected>STEL</option>
											@else
                                                <option value="STEL">STEL</option>
                                            @endif
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
								
								<!-- sorting  -->
							
								<!-- end sorting  -->

								
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
							<caption>Faktur Pajak Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Nama</th>
                                    <th class="center" scope="col">Perusahaan</th>
                                    <th class="center" scope="col">Deskripsi</th>
									<th class="center" scope="col">Payment Date</th>
									<th class="center" scope="col">Download</th>
								</tr>
							</thead>
							<tbody>
								@php
									$no=1;
								@endphp
								@if(count($data)>0)
									@foreach($data as $item)
										<tr>
											<td class="center">{{ $no+(($data->currentPage()-1)*$data->perPage())}}</td>
											<td class="center">{{ $item->type }}</td>
											<td class="center">{{ $item->user_name }}</td>
											<td class="center">{{ $item->company_name }}</td>
											<td class="center">{{ 
												$item->device_name." ,Tipe ".  
												$item->model." , kapasitas".
												$item->capacity

											}}</td>
											<td class="center">{{ $item->payment_date ? $item->payment_date : '0000-00-00' }}</td>
											<td class="center">
												@if($item->type == 'SPB')	
													@if($item->id_kuitansi)
														<a href="{{URL::to('/admin/examination/media/download/'.$item->_id.'/kuitansi')}}">Kuitansi</a>
													@else
														Kuitansi
													@endif
													 || 
													@if($item->faktur_file)
														<a href="{{URL::to('/admin/examination/media/download/'.$item->_id.'/faktur')}}">Faktur Pajak</a>
													@else
														Faktur Pajak
													@endif
												@elseif($item->type == 'STEL')
													@if($item->id_kuitansi)
														<a href="{{URL::to('/admin/downloadkuitansistel/'.$item->id_kuitansi) }}" target="_blank" rel="noopener">Kuitansi</a>
													@else
														Kuitansi
													@endif
													 || 
													@if($item->faktur_file)
														<a href="{{URL::to('/admin/downloadfakturstel/'.$item->_id) }}" target="_blank" rel="noopener">Faktur Pajak</a>
													@else
														Faktur Pajak
													@endif
												@else
													Kuitansi || Faktur Pajak
												@endif
											</td>
										</tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=8 class="center">{{$noDataFound}}</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
	                    <div class="col-md-12 col-sm-12">
	                        <div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
	                            {{$data->appends(array('search' => $search,'type' => $filterType,'company' => $filterCompany,'sort_by' => $sort_by,'sort_type' => $sort_type))->links()}}
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
					/*type:document.getElementById("type").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
					company:document.getElementById("company").value,
					lab:document.getElementById("lab").value,*/
/*sorting*/
					/*sort_by:document.getElementById("sort_by").value,
					sort_type:document.getElementById("sort_type").value*/
/*end sorting*/
				};
				document.location.href = baseUrl+'/admin/fakturpajak?'+jQuery.param(params);
	        }
	    });
/*
	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
            var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
			var company = document.getElementById("company");
			var companyValue = company.options[company.selectedIndex].value;
			var lab = document.getElementById("lab");
			var labValue = lab.options[lab.selectedIndex].value;*/
/*sorting*/
			/*var sort_by = document.getElementById("sort_by");
			var sort_byValue = sort_by.options[sort_by.selectedIndex].value;
			var sort_type = document.getElementById("sort_type");
			var sort_typeValue = sort_type.options[sort_type.selectedIndex].value;*/
/*end sorting*/

			/*params['search'] = search_value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (labValue != ''){
				params['lab'] = labValue;
			}*/
/*sorting*/			
			/*if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}*/
/*end sorting*/			
/*			document.location.href = baseUrl+'/admin/fakturpajak?'+jQuery.param(params);
	    };*/
	    
	    document.getElementById("excel").style.display = "none";
	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			/*var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
            var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
			var company = document.getElementById("company");
			var companyValue = company.options[company.selectedIndex].value;
			var lab = document.getElementById("lab");
			var labValue = lab.options[lab.selectedIndex].value;*/
			
/*sorting*/
			/*var sort_by = document.getElementById("sort_by");
			var sort_byValue = sort_by.options[sort_by.selectedIndex].value;
			var sort_type = document.getElementById("sort_type");
			var sort_typeValue = sort_type.options[sort_type.selectedIndex].value;*/
/*end sorting*/

			params['search'] = search_value;
			
			/*if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (labValue != ''){
				params['lab'] = labValue;
			}*/
/*sorting*/			
			/*if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}*/
/*end sorting*/			
			document.location.href = baseUrl+'/fakturpajak/excel?'+jQuery.param(params);
	    };
	});
</script>
@endsection