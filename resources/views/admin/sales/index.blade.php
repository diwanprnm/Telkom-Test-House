@extends('layouts.app')

@section('content')

<style type="text/css">
	ul.tabs{
		margin: 0px;
		padding: 0px;
		list-style: none;
		margin-bottom: 10px;
	}
	ul.tabs li{
		background: none;
		color: #222;
		display: inline-block;
		padding: 10px 15px;
		cursor: pointer;
	}

	ul.tabs li.current{
		background: #FF3E41;
		color: #ffffff;
	}

	.tab-content{
		display: none;
		/*background: #FF3E41;
		padding: 15px;*/
	}

	.tab-content.current{
		display: inherit;
	}
</style>

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
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

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
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
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

			<ul class="tabs">
				<li class="btn tab-unpaid" data-tab="tab-unpaid">Unpaid</li>
				<li class="btn tab-paid" data-tab="tab-paid">Paid</li>
				<li class="btn tab-delivered" data-tab="tab-delivered">Delivered</li>
			</ul>

			<input type="hidden" name="hidden_tab" id="hidden_tab" value="{{ $tab }}">
			
			<div id="tab-unpaid" class="row tab-content">
		        <div class="col-md-6">
		        <a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em> Filter</a>
					<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
		        </div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
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
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control after_date"/>
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
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date }}" name="before_date" id="before_date" class="form-control before_date"/>
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
								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right filter">
		                                Filter
		                            </button>
		                        </div>
							</div>
						</fieldset>
			    	</div>
			    </div>
	        
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
								@if(count($data_unpaid)>0)
									@foreach($data_unpaid as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_unpaid->currentPage()-1)*$data_unpaid->perPage()) }}</td>
											<td class="center">{{ $item->company_name }}</td>
											<td class="center">{{ $item->created_at }}</td>
											<td class="center">{{ $item->invoice }}</td>
											<td class="center">{{ number_format($item->cust_price_payment, 0, '.', ',') }}</td>
											<td class="center"> Unpaid </td>
											<td class="center">{{ ($item->payment_method == 1)?'ATM':$item->VA_name}}</td> 
											<td class="center">{{ $item->stel_code }}</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											<td class="center">
												<div>
													
												</div>
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id)}}" class="btn btn-wide btn-primary btn-margin" tooltip-placement="top" tooltip="Detail">Detail </a>
												</div>
											</td>
										</tr>
									@php
										$no++
									@endphp
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
								{{ $data_unpaid->appends(array('search' => $search,'search2' => $search,'search3' => $search,'before_date' => $before_date,'before_date2' => $before_date,'before_date3' => $before_date,'after_date' => $after_date,'after_date2' => $after_date,'after_date3' => $after_date, 'tab' => 'tab-unpaid'))->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="tab-paid" class="row tab-content">
		        <div class="col-md-6">
		        <a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse2" style="margin-right: 10px;"><em class="ti-filter"></em> Filter</a>
					<button id="excel2" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
		        </div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value2" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	            <div class="col-md-12 panel panel-info">
			    	<div id="collapse2" class="panel-collapse collapse">
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
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date2" id="after_date2" class="form-control after_date"/>
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
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date }}" name="before_date2" id="before_date2" class="form-control before_date"/>
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
								<div class="col-md-12">
		                            <button id="filter2" type="submit" class="btn btn-wide btn-green btn-squared pull-right filter">
		                                Filter
		                            </button>
		                        </div>
							</div>
						</fieldset>
			    	</div>
			    </div>
	        
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
								@if(count($data_paid)>0)
									@foreach($data_paid as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_paid->currentPage()-1)*$data_paid->perPage()) }}</td>
											<td class="center">{{ $item->company_name }}</td>
											<td class="center">{{ $item->created_at }}</td>
											<td class="center">{{ $item->invoice }}</td>
											<td class="center">{{ number_format($item->cust_price_payment, 0, '.', ',') }}</td>
											<td class="center"> Paid </td>
											<td class="center">{{ ($item->payment_method == 1)?'ATM':$item->VA_name}}</td> 
											<td class="center">{{ $item->stel_code }}</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id.'/upload')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Upload"><em class="fa fa-upload"></em></a>
												</div>
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id)}}" class="btn btn-wide btn-primary btn-margin" tooltip-placement="top" tooltip="Detail">Detail </a>
												</div>
											</td>
										</tr>
									@php
										$no++
									@endphp
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
								{{ $data_paid->appends(array('search' => $search,'search2' => $search,'search3' => $search,'before_date' => $before_date,'before_date2' => $before_date,'before_date3' => $before_date,'after_date' => $after_date,'after_date2' => $after_date,'after_date3' => $after_date, 'tab' => 'tab-paid'))->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="tab-delivered" class="row tab-content">
		        <div class="col-md-6">
		        <a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse3" style="margin-right: 10px;"><em class="ti-filter"></em> Filter</a>
					<button id="excel3" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
		        </div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value3" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	            <div class="col-md-12 panel panel-info">
			    	<div id="collapse3" class="panel-collapse collapse">
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
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date3" id="after_date3" class="form-control after_date"/>
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
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date }}" name="before_date3" id="before_date3" class="form-control before_date"/>
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
								<div class="col-md-12">
		                            <button id="filter3" type="submit" class="btn btn-wide btn-green btn-squared pull-right filter">
		                                Filter
		                            </button>
		                        </div>
							</div>
						</fieldset>
			    	</div>
			    </div>
	        
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
								@if(count($data_delivered)>0)
									@foreach($data_delivered as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_delivered->currentPage()-1)*$data_delivered->perPage()) }}</td>
											<td class="center">{{ $item->company_name }}</td>
											<td class="center">{{ $item->created_at }}</td>
											<td class="center">{{ $item->invoice }}</td>
											<td class="center">{{ number_format($item->cust_price_payment, 0, '.', ',') }}</td>
											<td class="center"> Delivered </td>
											<td class="center">{{ ($item->payment_method == 1)?'ATM':$item->VA_name}}</td> 
											<td class="center">{{ $item->stel_code }}</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id.'/upload')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Upload"><em class="fa fa-upload"></em></a>
												</div>
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sales/'.$item->id)}}" class="btn btn-wide btn-primary btn-margin" tooltip-placement="top" tooltip="Detail">Detail </a>
												</div>
											</td>
										</tr>
									@php
										$no++
									@endphp
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
								{{ $data_delivered->appends(array('search' => $search,'search2' => $search,'search3' => $search,'before_date' => $before_date,'before_date2' => $before_date,'before_date3' => $before_date,'after_date' => $after_date,'after_date2' => $after_date,'after_date3' => $after_date, 'tab' => 'tab-paid-deliverd'))->links() }}
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
	if($("#hidden_tab").val()){
		var tab_id = $("#hidden_tab").val();

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$("."+tab_id).addClass('current');
		$("#"+tab_id).addClass('current');
	}else{
		$(".tab-unpaid").addClass('current');
		$("#tab-unpaid").addClass('current');
	}

	jQuery(document).ready(function() {
		FormElements.init();

		$('ul.tabs li').click(function(){
			var tab_id = $(this).attr('data-tab');

			$('ul.tabs li').removeClass('current');
			$('.tab-content').removeClass('current');

			$(this).addClass('current');
			$("#"+tab_id).addClass('current');
		})
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('.search_value').keydown(function(event) {
			if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById(this.id).value,
					search2:document.getElementById(this.id).value,
					search3:document.getElementById(this.id).value,
					before:document.getElementById($(this).parent().parent().next().find(".before_date")[0].id).value,
					before2:document.getElementById($(this).parent().parent().next().find(".before_date")[0].id).value,
					before3:document.getElementById($(this).parent().parent().next().find(".before_date")[0].id).value,
					after:document.getElementById($(this).parent().parent().next().find(".after_date")[0].id).value,
					after2:document.getElementById($(this).parent().parent().next().find(".after_date")[0].id).value,
					after3:document.getElementById($(this).parent().parent().next().find(".after_date")[0].id).value,
					tab:$('.tabs .current').attr('data-tab')
				};
				document.location.href = baseUrl+'/admin/sales?'+jQuery.param(params);
	        }
	    });

		$('.filter').click(function(event) {
			var baseUrl = "{{URL::to('/')}}";
			var params = {
				search:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.search_value')[0].id).value,
				search2:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.search_value')[0].id).value,
				search3:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.search_value')[0].id).value,
				before:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.before_date')[0].id).value,
				before2:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.before_date')[0].id).value,
				before3:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.before_date')[0].id).value,
				after:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.after_date')[0].id).value,
				after2:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.after_date')[0].id).value,
				after3:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.after_date')[0].id).value,
				tab:$('.tabs .current').attr('data-tab'),
			};
			
			document.location.href = baseUrl+'/admin/sales?'+jQuery.param(params);
		});
	});

	document.getElementById("excel").onclick = function() {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_value").value,
			search2:document.getElementById("search_value2").value,
			search3:document.getElementById("search_value3").value,
			before:document.getElementById("before_date").value,
			before2:document.getElementById("before_date2").value,
			before3:document.getElementById("before_date3").value,
			after:document.getElementById("after_date").value,
			after2:document.getElementById("after_date2").value,
			after3:document.getElementById("after_date3").value,
			tab:$('.tabs .current').attr('data-tab'),
			payment_status:0
		};
		document.location.href = baseUrl+'/sales/excel?'+jQuery.param(params);
	};

	document.getElementById("excel2").onclick = function() {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_value").value,
			search2:document.getElementById("search_value2").value,
			search3:document.getElementById("search_value3").value,
			before:document.getElementById("before_date").value,
			before2:document.getElementById("before_date2").value,
			before3:document.getElementById("before_date3").value,
			after:document.getElementById("after_date").value,
			after2:document.getElementById("after_date2").value,
			after3:document.getElementById("after_date3").value,
			tab:$('.tabs .current').attr('data-tab'),
			payment_status:1
		};
		document.location.href = baseUrl+'/sales/excel?'+jQuery.param(params);
	};

	document.getElementById("excel3").onclick = function() {
		var baseUrl = "{{URL::to('/')}}";
		var params = {
			search:document.getElementById("search_value").value,
			search2:document.getElementById("search_value2").value,
			search3:document.getElementById("search_value3").value,
			before:document.getElementById("before_date").value,
			before2:document.getElementById("before_date2").value,
			before3:document.getElementById("before_date3").value,
			after:document.getElementById("after_date").value,
			after2:document.getElementById("after_date2").value,
			after3:document.getElementById("after_date3").value,
			tab:$('.tabs .current').attr('data-tab'),
			payment_status:3
		};
		document.location.href = baseUrl+'/sales/excel?'+jQuery.param(params);
	};
</script>>
@endsection