@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Data Penjualan</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Data Penjualan</span>
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
					<!-- <a class="btn btn-info pull-left" href="{{URL::to('sales/excel')}}"> Export to Excel</a> -->
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
											Jenis Pendapatan
										</label>
										<select id="type" name="type" class="cs-select cs-skin-elastic" required>
											@if($type == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($type == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
												@if($type == 1)
													<option value="1" selected>Pengujian Perangkat</option>
												@else
													<option value="1">Pengujian Perangkat</option>
												@endif
												@if($type == 2)
													<option value="2" selected>Pembelian STEL</option>
												@else
													<option value="2">Pembelian STEL</option>
												@endif
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Status
										</label>
										<select id="payment_status" name="payment_status" class="cs-select cs-skin-elastic" required>
											@if($payment_status == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($payment_status == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
												@if($payment_status == 1)
													<option value="1" selected>Pending</option>
												@else
													<option value="1">Pending</option>
												@endif
												@if($payment_status == 2)
													<option value="2" selected>Timeout</option>
												@else
													<option value="2">Timeout</option>
												@endif

												@if($payment_status == 3)
													<option value="2" selected>Complete</option>
												@else
													<option value="2">Complete</option>
												@endif
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Sebelum Tanggal" value="{{ $before_date }}" name="before_date" id="before_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
												</button>
											</span>
										</p>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Sesudah Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
												</button>
											</span>
										</p>
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
									<th class="center">Sales Date</th> 
									<th class="center">Invoice</th>  
									<th class="center">Total</th>
									<th class="center">Status</th>
									<th class="center">Payment Method</th> 
									<th class="center" colspan="2">Action</th>  
								</tr>
							</thead>
							<tbody> 
								@foreach($data as $keys => $item)
									<tr>
										<td class="center">{{++$keys}}</td> 
										<td class="center">{{ $item->created_at }}</td>
										<td class="center">{{ $item->invoice }}</td>
										<td class="center"><?php echo number_format($item->total, 0, '.', ','); ?></td>
										<td class="center">
											<?php
												switch ($item->payment_status) {
													case -1:
														echo "Paid (decline)";
														break; 
													case 0:
														echo "Unpaid";
														break; 
													case 1:
														echo "Paid (success)";
														break; 
													case 2:
														echo "Paid (waiting confirmation)";
														break; 
													default:
														# code...
														break;
												}
												?>

										</td>
										<td class="center">{{ ($item->payment_method == 1)?'ATM':'Kartu Kredit'}}</td> 
										<td class="center">
											<div>
												<a href="{{URL::to('admin/sales/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
											</div>
										</td>
										<td class="center">
											<div>
												<a href="{{URL::to('admin/sales/'.$item->id)}}" class="btn btn-wide btn-primary btn-margin" tooltip-placement="top" tooltip="Detail">Detail </a>
											</div>
										</td>
									</tr> 
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
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value			
				};
				document.location.href = baseUrl+'/admin/sales?'+jQuery.param(params);
	        }
	    });
	});

	 document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {}; 
			var search_value = document.getElementById("search_value").value;
            var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
            var type = document.getElementById("type").value;
            var payment_status = document.getElementById("payment_status").value;
			var beforeValue = before.value;
			var afterValue = after.value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (payment_status != ''){
				params['payment_status'] = payment_status;
			}
			if (type != ''){
				params['type'] = type;
			}
			 
			params['search'] = search_value;
			document.location.href = baseUrl+'/admin/sales?'+jQuery.param(params);
	    };
</script>>
@endsection