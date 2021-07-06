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
					<h1 class="mainTitle">SPK (Surat Perintah Kerja)</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>SPK (Surat Perintah Kerja)</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
	        	<div class="col-md-6">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em> Filter</a>
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
											Tanggal SPK Dikeluarkan
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
											Nomor SPK
										</label>
										<select class="form-control" id="spk" name="spk" class="chosen-spk">
												@if ($filterSpk == '')
													<option value="" disabled selected> - Pilih Nomor SPK - </option>
												@endif
												@if ($filterSpk == 'all')
													<option value="all" selected>All</option>
												@else
													<option value="all">All</option>
												@endif
											@foreach($spk as $item)
												@if($item->SPK_NUMBER == $filterSpk)
													<option value="{{ $item->SPK_NUMBER }}" selected>{{ $item->SPK_NUMBER }}</option>
												@else
													<option value="{{ $item->SPK_NUMBER }}">{{ $item->SPK_NUMBER }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group typeHTML">
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
												@if($item->name == $filterType)
													<option value="{{ $item->name }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->name }}">{{ $item->name }}</option>
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
									<div class="form-group labHTML">
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
												@if($item->lab_code == $filterLab)
													<option value="{{ $item->lab_code }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->lab_code }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group sortHTML">
										<label>
											Sort by :
										</label>
										<select id="sort_by" name="sort_by" class="cs-select cs-skin-elastic" required>
											@if($sort_by == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($sort_by == 'spk_date')
                                                <option value="spk_date" selected>Tanggal SPK Dikeluarkan</option>
											@else
                                                <option value="spk_date">Tanggal SPK Dikeluarkan</option>
                                            @endif
											@if($sort_by == 'SPK_NUMBER')
                                                <option value="SPK_NUMBER" selected>Nomor SPK</option>
											@else
                                                <option value="SPK_NUMBER">Nomor SPK</option>
                                            @endif
                                            @if($sort_by == 'TESTING_TYPE')
                                                <option value="TESTING_TYPE" selected>Tipe Pengujian</option>
											@else
                                                <option value="TESTING_TYPE">Tipe Pengujian</option>
                                            @endif
											@if($sort_by == 'COMPANY_NAME')
                                                <option value="COMPANY_NAME" selected>Nama Perusahaan</option>
											@else
                                                <option value="COMPANY_NAME">Nama Perusahaan</option>
                                            @endif
										</select>
										<select id="sort_type" name="sort_type" class="cs-select cs-skin-elastic" required>
											@if($sort_type == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($sort_type == 'asc')
                                                <option value="asc" selected>ASC</option>
											@else
                                                <option value="asc">ASC</option>
                                            @endif
											@if($sort_type == 'desc')
                                                <option value="desc" selected>DESC</option>
											@else
                                                <option value="desc">DESC</option>
                                            @endif
										</select>
									</div>
								</div>
								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right">
		                                Filter
		                            </button>
									<button id="reset-filter" class="btn btn-wide btn-white btn-squared pull-right" style="margin-right: 10px;">
                                        Reset
                                    </button>
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
							<caption>(SPK) Surat Perintah Kerja Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nomor SPK</th>
									<th class="center" scope="col">Laboratorium</th>
									<th class="center" scope="col">Tipe Pengujian</th>
                                    <th class="center" scope="col">Perusahaan</th>
                                    <th class="center" scope="col">Perangkat</th>
                                    <th class="center" scope="col">Status</th>
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
											<td class="center">{{ $no }}</td>
											<td class="center">{{ $item->SPK_NUMBER }}</td>
											<td class="center">{{ $item->lab_name }}</td>
											<td class="center">{{ $item->TESTING_TYPE }} ({{ $item->TESTING_TYPE_DESC }})</td>
											<td class="center">{{ $item->COMPANY_NAME }}</td>
											<td class="center">{{ $item->DEVICE_NAME }}</td>
											<td class="center">
												@if (isset($listFlowStatus[$item->FLOW_STATUS]))
													{{ $listFlowStatus[$item->FLOW_STATUS] }}
												@else
													Unkown Status
												@endif
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/spk/'.$item->ID.'')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
										</tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=8 class="center">
											{{$noDataFound}}
										</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{ $data->appends(array('search' => $search,'before_date' => $before_date,'after_date' => $after_date,'type' => $filterType,'spk' => $filterSpk,'company' => $filterCompany,'lab' => $filterLab,'sort_by' => $sort_by,'sort_type' => $sort_type))->links() }}
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
	$('#spk').chosen();
	$('#spk').trigger("chosen:updated");
	$('#company').chosen();
	$('#company').trigger("chosen:updated");
</script>
<script type="text/javascript">
	var typeHTML = '<select id="type" name="type" class="cs-select cs-skin-elastic" required>'+
												'<option value="" disabled selected>Select...</option>'+
                                               	'<option value="all">All</option>'+
											@foreach($type as $item)
													'<option value="{{ $item->id }}">{{ $item->name }}</option>'+
											@endforeach
										'</select>'
	var labHTML = '<select id="lab" name="lab" class="cs-select cs-skin-elastic" required>'+
					'<option value="" disabled selected>Select...</option>'+
					'<option value="all">All</option>'+
				@foreach($type as $item)
						'<option value="{{ $item->id }}">{{ $item->name }}</option>'+
				@endforeach
			'</select>'
	var sortByHTML = '<select id="sort_by" name="sort_by" class="cs-select cs-skin-elastic" required>'+
												'<option value="" disabled selected>Select...</option>'+
                                                '<option value="spk_date">Tanggal SPK Dikeluarkan</option>'+
                                                '<option value="SPK_NUMBER">Nomor SPK</option>'+
                                               '<option value="TESTING_TYPE">Tipe Pengujian</option>'+
                                                '<option value="COMPANY_NAME">Nama Perusahaan</option>'+'</select>'

	var sortTypeHTML = '<select id="sort_type" name="sort_type" class="cs-select cs-skin-elastic" required>'+
												'<option value="" disabled selected>Select...</option>'+
                                                '<option value="asc">ASC</option>'+
                                               ' <option value="desc">DESC</option>'+
										'</select>'
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
					type:document.getElementById("type").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
					spk:document.getElementById("spk").value,
					company:document.getElementById("company").value,
					sort_by:document.getElementById("sort_by").value,
					sort_type:document.getElementById("sort_type").value
				};
				document.location.href = baseUrl+'/admin/spk?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
			var spk = document.getElementById("spk");
			var spkValue = spk.options[spk.selectedIndex].value;
            var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
			var company = document.getElementById("company");
			var companyValue = company.options[company.selectedIndex].value;
			var lab = document.getElementById("lab");
			var labValue = lab.options[lab.selectedIndex].value;

			var sort_by = document.getElementById("sort_by");
			var sort_byValue = sort_by.options[sort_by.selectedIndex].value;
			var sort_type = document.getElementById("sort_type");
			var sort_typeValue = sort_type.options[sort_type.selectedIndex].value;

			params['search'] = search_value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (spkValue != ''){
				params['spk'] = spkValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (labValue != ''){
				params['lab'] = labValue;
			}
			
			if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}
			document.location.href = baseUrl+'/admin/spk?'+jQuery.param(params);
	    };

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
            var spk = document.getElementById("spk");
			var spkValue = spk.options[spk.selectedIndex].value;
            var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
			var company = document.getElementById("company");
			var companyValue = company.options[company.selectedIndex].value;
			var lab = document.getElementById("lab");
			var labValue = lab.options[lab.selectedIndex].value;
			
			var sort_by = document.getElementById("sort_by");
			var sort_byValue = sort_by.options[sort_by.selectedIndex].value;
			var sort_type = document.getElementById("sort_type");
			var sort_typeValue = sort_type.options[sort_type.selectedIndex].value;

			params['search'] = search_value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (spkValue != ''){
				params['spk'] = spkValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (labValue != ''){
				params['lab'] = labValue;
			}
			
			if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}
			document.location.href = baseUrl+'/spk/excel?'+jQuery.param(params);
	    };

		document.getElementById("reset-filter").onclick = function() {
            $('.cs-select').remove();
            $('.typeHTML').append(typeHTML);
			$('.labHTML').append(labHTML);
			$('.sortHTML').append(sortByHTML).append(sortTypeHTML);
			$('#after_date').val(null);
			$('#before_date').val(null);
			$('#spk').chosen().val('').trigger('chosen:updated');
			$('#company').chosen().val('').trigger('chosen:updated');
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        };
	});
</script>
@endsection