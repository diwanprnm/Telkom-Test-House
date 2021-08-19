@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{url('assets/css/pb.calendar.min.css')}}" type="text/css">
<style type="text/css">
	ul.tabs{
		padding: 0px;
		list-style: none;
		margin-bottom: 17px;
	}
	ul.tabs li{
		background: none;
		color: #222;
		display: inline-block;
		/* padding: 10px 15px; */
		cursor: pointer;
	}
	ul.tabs li.current{
		background: #FF3E41;
		color: #ffffff;
	}

	.row.calendar-view{
		position: absolute;
		margin: -22px -15px;
	}

	.calendar-view-body{
		position: relative;
		margin-top: 10px;
	}

	.pb-calendar{
		position: relative;
		margin: auto;
		width: 40%;
	}

	.pb-calendar .row-day .red{
		background-color: red;
		color: white !important;
	}

	.rounded-corner {
			border-radius: 15px;
			border: 2px solid white; 
		}

</style>
<div class="modal fade" id="myModal_delete_detail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Detail Pembelian STEL Akan Dihapus, Mohon Berikan Keterangan!</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<div class="form-group">
								<label for="keterangan">Keterangan:</label>
								<textarea class="form-control" rows="5" name="keterangan" id="keterangan"></textarea>
							</div>
						</th>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="button" id="btn-modal-delete_detail" class="btn btn-danger" style="width:100%" data-delete-id="" ><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

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

			<div class="row calendar-view">
		        <div class="col-md-6 ">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse-calendar" style="margin-right: 10px;"><em class="ti-calendar"></em>
						Calendar View
					</a>
				</div>
			</div>

			<div class="row calendar-view-body">
				<div class="col-md-12 panel panel-info">
					<div id="collapse-calendar" class="panel-collapse collapse">
						<div id="pb-calendar" class="pb-calendar">
						</div>
					</div>
				</div>
			</div>


			<ul class="tabs">
				<li class="btn tab-unpaid" data-tab="tab-unpaid">Unpaid</li>
				<li class="btn tab-paid" data-tab="tab-paid">Paid</li>
				<li class="btn tab-delivered" data-tab="tab-delivered">Delivered</li>
			</ul>

			<div class="row">
		        <div class="col-md-6">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em>
						Filter
					</a>
					{{-- <button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button> --}}
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="filter_search_input" type="text" placeholder="Search" id="form-field-17" class="form-control " value="">
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
											<input type="text" placeholder="Dari Tanggal" value="" name="after_date" id="filter_after_date_input" class="form-control"/>
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
											<input type="text" placeholder="Sampai Tanggal" value="" name="before_date" id="filter_before_date_input" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
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
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
						<caption>Rent Chamber Table</caption>
						<thead>
							<tr>
								<th class="center" scope="col">No</th> 
								<th class="center" scope="col">Company Name</th> 
								<th class="center" scope="col">Sales Date</th> 
								<th class="center" scope="col">Rent Date</th> 
								<th class="center" scope="col">Invoice</th>  
								<th class="center" scope="col">Total</th>
								<th class="center" scope="col">Status</th>
								<th class="center" scope="col">Payment Method</th>
								<th class="center" colspan="3"  scope="colgroup">Action</th>
								<th class="center" scope="col"></th> 
							</tr>
						</thead>
						@foreach ($statuses as $status)
						<tbody class="tab-{{$status}} tab-content" data-paginate-current-page="">
							@if (count($data->$status))
								@for ($i = 0; $i < count($data->$status); $i++)
								<tr>
									<td class="center">{{$i+1}}</td>
									<td class="center">{{$data->$status[$i]->company_name}}</td>
									<td class="center">{{$data->$status[$i]->start_date}}</td>
									<td class="center">{{$data->$status[$i]->PO_ID ?? '-'}}</td>

									<td class="center">{{$data->$status[$i]->invoice}}</td>
									<td class="center">{{ "Rp " . number_format($data->$status[$i]->total,0,'','.') . ",-" }}</td>
									<td class="center">{{$status}}</td>

									<td class="center">{{$data->$status[$i]->payment_method ?? '-'}}</td>
									<td class="center">
										<div>
											<a href="{{URL::to('admin/chamber/'.$data->$status[$i]->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
										</div>
									</td>
									<td class="center">
										<div>
											<a href="{{URL::to('admin/chamber/'.$data->$status[$i]->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Upload"><em class="fa fa-upload"></em></a>
										</div>
									</td>
									<td class="center">
										<div>
											<a class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Destroy" data-rent-chamber-id="{{$data->$status[$i]->id}}"  data-toggle="modal" data-target="#myModal_delete_detail" onclick="document.getElementById('btn-modal-delete_detail').setAttribute('data-delete-id', '{{ $data->$status[$i]->id }}')" ><em class="fa fa-trash"></em></a>
										</div>
									</td>
									<td class="center">
										<a href="{{URL::to('admin/chamber/'.$data->$status[$i]->id)}}" class="btn btn-wide btn-primary btn-margin" tooltip-placement="top" tooltip="Detail">Detail </a>
									</td>
								</tr>
								@endfor
							@else
							<tr>
								<td colspan=9 class="center">
									Data Tidak Ditemukan
								</td>
							</tr>
							@endif

						</tbody>
						@endforeach
					</table>
				</div>
				@foreach ($statuses as $status)
				<div class="row">
					<div class="col-md-12 col-sm-12 tab-{{$status}} tab-content">
						<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
							{{ $data->$status->links() }}
						</div>
					</div>
				</div>
				@endforeach
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
<script type="text/javascript" src="{{url('assets/js/moment.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/pb.calendar.min.js')}}"></script>
<script type="text/javascript">
	

$(document).ready(() => {
	// Initialization
	const baseUrl = "{{URL::to('/')}}";
	let bookedDates = [];
	FormElements.init();
	setFilterByParam();
	setPaginationUrl(getFilterParam());
	$('.tab-content').hide();
	$(`.tab-content.${currentTab}`).show();

	// Setting view current tab list
	$('ul.tabs li').click((event) => {
		var tab_id = event.currentTarget.getAttribute('data-tab');
		$('ul.tabs li').removeClass('current');
		$('.tab-content').hide();
		event.currentTarget.className += " current";
		$("#"+tab_id).addClass('current');
		$(".tab-content."+tab_id).show();
		setPaginationUrl(getFilterParam());
	})	

	// Set destination when button filter(submit) click
	$('#filter').click(()=>{
		let param = getFilterParam();
		document.location.href = baseUrl+'/admin/chamber?'+$.param(param);
	});

	// Set destination when search is press with enter key
	$('#filter_search_input').keypress(function(e) {
		if(e.which != 13) { return; }
		let param = getFilterParam();
		document.location.href = baseUrl+'/admin/chamber?'+$.param(param);
	});

	$('#myModal_delete_detail').on('shown.bs.modal', function (e) {
		$('#keterangan').focus();
	});

	$('#btn-modal-delete_detail').click(function (e) {
		console.log(e.target.getAttribute('data-delete-id'))
		var baseUrl = "{{URL::to('/')}}";
		var keterangan = document.getElementById('keterangan').value;
		let idTobeDelete = e.target.getAttribute('data-delete-id')
		// var stel_sales_detail_id = document.getElementById('hide_stel_sales_detail_id').value;
		if(keterangan == ''){
			$('#myModal_delete_detail').modal('show');
			return false;
		}else{
			$('#myModal_delete_detail').modal('hide');
			if (confirm('Are you sure want to delete this data?')) {
				document.getElementById("overlay").style.display="inherit";	
				document.location.href = baseUrl+'/admin/chamber/delete/'+idTobeDelete+'/'+encodeURIComponent(encodeURIComponent(keterangan));
			}
		}
	});

	// initialize calendar view
	let pbCalendarOption = {
		'day_selectable' : false,
		'next_month_button' :'<img src="{{ URL::asset('assets/images/arrow-next.png') }}" class="icon">',
		'prev_month_button' :'<img src="{{ URL::asset('assets/images/arrow-prev.png') }}" class="icon">',
		'callback_changed_month': (event) => {
			setDayLabelWithClass(bookedDates,'red');
		}
	}

	let pbCalendar = $("#pb-calendar").pb_calendar(pbCalendarOption);

	const initCalendarByAjax = async () => {
		await getDateRentedChamber( resp => {
			bookedDates = resp.map( dateRecord =>{
				return dateRecord['date'].replace(/-/g, '');
			});
		});
		setDayLabelWithClass(bookedDates,'red');
	}

	initCalendarByAjax();
	
});
const url = new URL(window.location.href);
currentTab = url.searchParams.get("tab") ?? 'tab-unpaid';

// Get url parameter from filter
const getFilterParam = () =>  {
	return {
		search: $('#filter_search_input').val(),
		after_date: $('#filter_after_date_input').val(),
		before_date: $('#filter_before_date_input').val(),
		tab: $('.tabs .current').attr('data-tab'),
	}
};

// Set filter from url parameter
const setFilterByParam = () =>  {
	$('#filter_search_input').val(url.searchParams.get("search"));
	$('#filter_after_date_input').val(url.searchParams.get("after_date"));
	$('#filter_before_date_input').val(url.searchParams.get("before_date"));
	$(`.${currentTab}`).addClass('current');
};

//Set pagination url manual.
const setPaginationUrl = ( param ) => {
	let paginationLink = $('ul.pagination li a');
	paginationLink.each( function (element) {
		param.pageUnpaid = new URL(this.href).searchParams.get("pageUnpaid") ?? new URL(url).searchParams.get("pageUnpaid") ;
		param.pagePaid = new URL(this.href).searchParams.get("pagePaid") ?? new URL(url).searchParams.get("pagePaid");
		param.pageDelivered = new URL(this.href).searchParams.get("pageDelivered") ?? new URL(url).searchParams.get("pageDelivered");
		this.href = this.href.split("?")[0]+'?'+$.param(param);
	});
	
}

// Getting all date that have been rented
const getDateRentedChamber = handleData => {
	return $.ajax({
		url:"{{URL::to('/v1/getDateRentedChamber')}}",  
		success:function(data) {
			handleData(data); 
		}
	});
}

// Setup calender coloring
const setDayLabelWithClass = (list, color) => list.forEach( item => $(`.row-day .col[data-day-yyyymmdd='${item}']`).addClass(`${color} rounded-corner`) );

</script>
@endsection