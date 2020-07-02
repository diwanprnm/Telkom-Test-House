@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Rekap Pembelian STEL</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Rekap Pembelian STEL</span>
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
											Tanggal
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
												@if($payment_status == '-1')
													<option value="-1" selected>Paid (decline)</option>
												@else
													<option value="-1">Paid (decline)</option>
												@endif
												@if($payment_status == '0')
													<option value="0" selected>Unpaid</option>
												@else
													<option value="0">Unpaid</option>
												@endif

												@if($payment_status == '1')
													<option value="1" selected>Paid (success)</option>
												@else
													<option value="1">Paid (success)</option>
												@endif
												
												@if($payment_status == '2')
													<option value="2" selected>Paid (waiting confirmation)</option>
												@else
													<option value="2">Paid (waiting confirmation)</option>
												@endif
												
												@if($payment_status == '3')
													<option value="3" selected>Paid (delivered)</option>
												@else
													<option value="3">Paid (delivered)</option>
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
				<div class="col-md-6 pull-right" style="margin-bottom:10px;margin-top:20px">
					<a style=" color:white !important;" href="{{URL::to('/admin/sales/create')}}">
		            <button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Tambah
		            </button>         
					</a>
		        </div>
		    </div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Sales Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Company Name</th> 
									<th class="center" scope="col">Sales Date</th> 
									<th class="center" scope="col">Invoice</th>  
									<th class="center" scope="col">Total</th>
									<th class="center" scope="col">Status</th>
									<th class="center" scope="col">Payment Method</th> 
									<th class="center" scope="col">Document Code</th> 
									<th class="center" colspan="2"  scope="colgroup">Action</th>  
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data)>0)
									@foreach($data as $keys => $item)
										<tr>
											<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
											<td class="center">{{ $item->company_name }}</td>
											<td class="center">{{ $item->created_at }}</td>
											<td class="center">{{ $item->invoice }}</td>
											<td class="center"><?php echo number_format($item->cust_price_payment, 0, '.', ','); ?></td>
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
														case 3:
															echo "Paid (delivered)";
															break; 
														default:
															# code...
															break;
													}
													?>

											</td>
											<td class="center">{{ ($item->payment_method == 1)?'ATM':'Kartu Kredit'}}</td> 
											<td class="center">{{ $item->stel_code }}</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											<td class="center">
												<div>
													@if($item->payment_status == 1 or $item->payment_status == 3)
														<a href="{{URL::to('admin/sales/'.$item->id.'/upload')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Upload"><em class="fa fa-upload"></em></a>
													@endif
												</div>
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id)}}" class="btn btn-wide btn-primary btn-margin" tooltip-placement="top" tooltip="Detail">Detail </a>
												</div>
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
								<?php echo $data->appends(array('payment_status' => $payment_status,'search' => $search,'before_date' => $before_date,'after_date' => $after_date))->links(); ?>
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
			params['search'] = search_value;
			document.location.href = baseUrl+'/admin/sales?'+jQuery.param(params);
	    };

	document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {}; 
			var search_value = document.getElementById("search_value").value;
            var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
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
			params['search'] = search_value;
			document.location.href = baseUrl+'/sales/excel?'+jQuery.param(params);
	    };
</script>>
@endsection