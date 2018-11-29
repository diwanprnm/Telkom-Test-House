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
					<h1 class="mainTitle">SPB (Surat Pemberitahuan Biaya)</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li class="active">
						<span>Rekap Nomor SPB</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="row">
				<div class="col-md-6">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><i class="ti-filter"></i> Filter</a>
	    			<!--<a class="btn btn-info pull-left" id="excel" href="{{URL::to('examination/excel')}}"> Export to Excel</a> -->
	    			<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <i class="ti-search"></i>
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
											Tanggal SPB Dikeluarkan
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
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
													<i class="glyphicon glyphicon-calendar"></i>
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
											Nomor SPB
										</label>
										<select class="form-control" id="spb" name="spb" class="chosen-spb">
												@if ($filterSpb == '')
													<option value="" disabled selected> - Pilih Nomor SPB - </option>
												@endif
												@if ($filterSpb == 'all')
													<option value="all" selected>All</option>
												@else
													<option value="all">All</option>
												@endif
											@foreach($spb as $item)
												@if($item->spb_number == $filterSpb)
													<option value="{{ $item->spb_number }}" selected>{{ $item->spb_number }}</option>
												@else
													<option value="{{ $item->spb_number }}">{{ $item->spb_number }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
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
											Status Bayar
										</label>
										<select id="payment_status" name="payment_status" class="cs-select cs-skin-elastic" required>
											@if($filterPayment_status == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($filterPayment_status == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
                                            @if($filterPayment_status == '1')
                                                <option value="1" selected>SUDAH</option>
											@else
                                                <option value="1">SUDAH</option>
                                            @endif
                                            @if($filterPayment_status == '0')
                                                <option value="0" selected>BELUM</option>
											@else
                                                <option value="0">BELUM</option>
                                            @endif
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Sort by :
										</label>
										<select id="sort_by" name="sort_by" class="cs-select cs-skin-elastic" required>
											@if($sort_by == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($sort_by == 'spb_date')
                                                <option value="spb_date" selected>Tanggal SPB Dikeluarkan</option>
											@else
                                                <option value="spb_date">Tanggal SPB Dikeluarkan</option>
                                            @endif
											@if($sort_by == 'spb_number')
                                                <option value="spb_number" selected>Nomor SPB</option>
											@else
                                                <option value="spb_number">Nomor SPB</option>
                                            @endif
                                            @if($sort_by == 'examination_type_id')
                                                <option value="examination_type_id" selected>Tipe Pengujian</option>
											@else
                                                <option value="examination_type_id">Tipe Pengujian</option>
                                            @endif
											@if($sort_by == 'company_name')
                                                <option value="company_name" selected>Nama Perusahaan</option>
											@else
                                                <option value="company_name">Nama Perusahaan</option>
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
							<thead>
								<tr>
									<th class="center">No</th>
									<th class="center">Nomor SPB</th>
                                    <th class="center">Perusahaan</th>
                                    <th class="center">Perangkat</th>
									<th class="center">Tipe</th>
									<th class="center">Kapasitas</th>
									<th class="center">Nominal</th>
									<th class="center">Status Bayar</th>
									<th class="center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@if(count($data)>0)
									@foreach($data as $item)
										<tr>
											<td class="center">{{$no}}</td>
											<td class="center">{{ $item->spb_number }}</td>
											<td class="center">{{ $item->company->name }}</td>
											<td class="center">{{ $item->device->name }}</td>
											<td class="center">{{ $item->device->mark }}</td>
											<td class="center">{{ $item->device->capacity }}</td>
											<td class="center">{{ $item->price }}</td>
											<td class="center">
												@if($item->payment_status == '1')
													SUDAH
												@else
													BELUM
												@endif
											</td>
											<td class="center">
												@foreach($item->media as $attach)
													@if($attach->name == 'File Pembayaran' && $attach->attachment != '')
														<a href="{{URL::to('/admin/spb/media/download/'.$item->id.'/File Pembayaran')}}"> Lihat Bukti Bayar</a>
													@endif
												@endforeach
											</td>
										</tr>
									<?php $no++ ?>
									@endforeach
								@else
									<tr>
										<td colspan=9 class="center">
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
								<?php echo $data->appends(array('search' => $search,'before_date' => $before_date,'after_date' => $after_date,'type' => $filterType,'spb' => $filterSpb,'company' => $filterCompany,'payment_status' => $filterPayment_status,'sort_by' => $sort_by,'sort_type' => $sort_type))->links(); ?>
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
	$('#spb').chosen();
	$('#spb').trigger("chosen:updated");
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
					type:document.getElementById("type").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
					spb:document.getElementById("spb").value,
					company:document.getElementById("company").value,
					payment_status:document.getElementById("payment_status").value,
					sort_by:document.getElementById("sort_by").value,
					sort_type:document.getElementById("sort_type").value
				};
				document.location.href = baseUrl+'/admin/spb?'+jQuery.param(params);
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
			var spb = document.getElementById("spb");
			var spbValue = spb.options[spb.selectedIndex].value;
            var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
			var company = document.getElementById("company");
			var companyValue = company.options[company.selectedIndex].value;
			var payment_status = document.getElementById("payment_status");
			var payment_statusValue = payment_status.options[payment_status.selectedIndex].value;

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
			if (spbValue != ''){
				params['spb'] = spbValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (payment_statusValue != ''){
				params['payment_status'] = payment_statusValue;
			}
			
			if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}
			document.location.href = baseUrl+'/admin/spb?'+jQuery.param(params);
	    };

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
            var spb = document.getElementById("spb");
			var spbValue = spb.options[spb.selectedIndex].value;
            var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
			var company = document.getElementById("company");
			var companyValue = company.options[company.selectedIndex].value;
			var payment_status = document.getElementById("payment_status");
			var payment_statusValue = payment_status.options[payment_status.selectedIndex].value;
			
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
			if (spbValue != ''){
				params['spb'] = spbValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (payment_statusValue != ''){
				params['payment_status'] = payment_statusValue;
			}
			
			if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}
			document.location.href = baseUrl+'/spb/excel?'+jQuery.param(params);
	    };
	});
</script>
@endsection