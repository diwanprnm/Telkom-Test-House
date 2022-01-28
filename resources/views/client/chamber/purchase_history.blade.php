@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.chamber_history') }} - Telkom Test House</title>
@section('content')
 <!-- Page Title
	============================================= -->
	<section id="page-title">

        <div class="container clearfix">
			<div class="container clearfix">
			<h1>{{ trans('translate.chamber_history') }}</h1>
			
			<ol class="breadcrumb">
                <li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
				<li class="active">{{ trans('translate.chamber_history') }}</li>
			</ol>
		    </div>
		</div>

	</section><!-- #page-title end -->

	<!-- Content
	============================================= -->
	<section id="content">
		<div class="content-wrap">
			<div class="container clearfix">
				<div class="container-fluid container-fullw bg-white">
					{{-- <div class="col-md-8 offset-0">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em>
						Filter
					</a> --}}
					{{-- <button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button> --}}
				{{-- </div> --}}
				{{-- <div class="col-md-4 offset-0">
					<span class="input-icon input-icon-right search-table  float-right"> 
						<input id="filter_search_input" name="search" type="text" placeholder="{{ trans('translate.search_chamber_history') }}" id="form-field-17" class="form-control " value="{{ $search }}">
						<i class="fa fa-search" aria-hidden="true"></i>
					</span> 
				</div> --}}
					<div class="col-md-12 panell panell-info">
			    	<div id="collapsse1" class="panel-collapsse collapsse">
			     		<fieldset>							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.chamber_history_date') }}
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="{{ trans('translate.chamber_history_date_filter_start') }}" value="" name="after_date" id="filter_after_date_input" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default form-control">
													<i class="fa fa-calendar" aria-hidden="true"></i>
													{{-- <em class="glyphicon glyphicon-calendar"></em> --}}
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
											<input type="text" placeholder="{{ trans('translate.chamber_history_date_filter_end') }}" value="" name="before_date" id="filter_before_date_input" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default form-control">
													<i class="fa fa-calendar" aria-hidden="true"></i>
													{{-- <em class="glyphicon glyphicon-calendar"></em> --}}
												</button>
											</span>
										</p>
									</div>
		                        </div>
								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn button-3d btn-sky pull-right">
		                                Filter
		                            </button>
									<button id="reset" onclick="resetDate()" class="btn button-3d pull-right" style="margin-right: 1.25rem">Reset</button>
		                        </div>								
							</div>
							
						</fieldset>
			    	</div>
			    </div>
							<ul class="nav nav-tabs clearfix">
								<li class="{{ $tab == 'unpaid' ? 'active' : '' }}" data-tab="unpaid"><a href="#tab-unpaid" data-toggle="tab"><strong>Unpaid</strong></a></li>
								<li class="{{ $tab == 'paid' ? 'active' : '' }}" data-tab="paid"><a href="#tab-paid" data-toggle="tab"><strong>Paid</strong></a></li>
								<li class="{{ $tab == 'delivered' ? 'active' : '' }}" data-tab="delivered"><a href="#tab-delivered" data-toggle="tab"><strong>Delivered</strong></a></li>
							</ul>					
					<div class="tab-content">
						<!-- tab unpaid -->
						<div id="tab-unpaid" class="row clearfix tab-pane fade {{ $tab == 'unpaid' ? 'in active' : '' }}">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="datatable1" class="table table-striped table-bordered" style="width: 100%;">
										<caption></caption>
										<thead>
											<tr>
												<th scope="col">No</th>
												<th scope="col">{{ trans('translate.stel_payment_status_order_date') }}</th>
                                                <th scope="col">{{ trans('translate.rent_chamber_client_label_rent_date') }}</th>
                                                <th scope="col">{{ trans('translate.rent_chamber_client_label_rent_duration') }}</th>
												<th scope="col">Total</th> 
												<th scope="col">PIC</th>   
												<th scope="col" class="center">Status</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $i = 0; @endphp
											@if($data_unpaid)
											@foreach($data_unpaid as $keys => $item)
												<tr>
													<td>{{$no+(($data_unpaid->currentPage()-1)*$data_unpaid->perPage())}}</td>
													<td>{{$item->created_at}}</td>
                                                    <td>
                                                        {{$item->start_date}} 
                                                        @if($item->duration > 1) 
                                                            & {{$item->end_date}} 
                                                        @endif
                                                    </td>
                                                    <td>{{$item->duration}} {{ trans('translate.chamber_days') }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													@if($item->spb_date)
														@if($data_unpaid[$i]->payment_method == 2 && $data_unpaid[$i]->VA_expired < date("Y-m-d H:i:s"))
														<td class="center"><span class="label label-sm label-danger" style="line-height: 2;">Expired</span></td>
														@else
														<td class="center"><span class="label label-sm label-default" style="line-height: 2;">{{ $item->spb_date ? 'Unpaid' : trans('translate.rent_chamber_client_label_waiting_verification') }}</span></td>
														<td>
															<a class="label label-sm label-warning" style="line-height: 2;" href="{{URL::to('chamber_history/'.$item->id.'/pembayaran')}}">{{ trans('translate.examination_pay_now') }}</a>
														</td>
														@endif
													@else
														<td class="center"><span class="label label-sm label-default" style="line-height: 2;">{{ $item->spb_date ? 'Unpaid' : trans('translate.rent_chamber_client_label_waiting_verification') }}</span></td>
													@endif
												</tr> 
											@php $no++;$i++; @endphp
											@endforeach
											@else
												<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-striped">
															<caption></caption>
															<tr class="center">
																<th style="text-align: center;" scope="col">{{ trans('translate.data_not_found') }}</th>
															</tr> 
														</table>
													</div>
												</div>
											@endif
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
										@php echo $data_unpaid->links(); @endphp
									</div>
								</div>
							</div>
						</div>
						<!-- tab paid -->
						<div id="tab-paid" class="row clearfix tab-pane fade {{ $tab == 'paid' ? 'in active' : '' }}">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="datatable1" class="table table-striped table-bordered" style="width: 100%;">
										<caption></caption>
										<thead>
                                            <tr>
                                                <th scope="col">No</th>
												<th scope="col">{{ trans('translate.stel_payment_status_order_date') }}</th>
                                                <th scope="col">{{ trans('translate.rent_chamber_client_label_rent_date') }}</th>
                                                <th scope="col">{{ trans('translate.rent_chamber_client_label_rent_duration') }}</th>
												<th scope="col">Total</th> 
												<th scope="col">PIC</th>   
												<th scope="col" class="center">Status</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $i = 0; @endphp
											@if($data_paid)
											@foreach($data_paid as $keys => $item)
												<tr>
													<td>{{$no+(($data_paid->currentPage()-1)*$data_paid->perPage())}}</td>
													<td>{{$item->created_at}}</td>
                                                    <td>
                                                        {{$item->start_date}} 
                                                        @if($item->duration > 1) 
                                                            & {{$item->end_date}} 
                                                        @endif
                                                    </td>
                                                    <td>{{$item->duration}} {{ trans('translate.chamber_days') }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-success" style="line-height: 2;">Paid</span></td>
													<td>
														<a class="label label-sm label-success" style="line-height: 2;" href="{{URL::to('downloadkuitansichamber/'.$item->id.'/')}}">{{ trans('translate.receipt') }}</a>
														<a class="label label-sm label-success" style="line-height: 2;" href="{{URL::to('downloadfakturchamber/'.$item->id.'/')}}">{{ trans('translate.tax_invoice') }}</a>
														<a class="label label-sm label-info" style="line-height: 2;" href="{{URL::to('cetakTiketChamber/'.$item->id.'/')}}">{{ trans('translate.ticket') }}</a>
													</td>
											@php $no++;$i++; @endphp
											@endforeach
											@else
												<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-striped">
															<caption></caption>
															<tr class="center">
																<th style="text-align: center;" scope="col">{{ trans('translate.data_not_found') }}</th>
															</tr> 
														</table>
													</div>
												</div>
											@endif
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
										@php echo $data_paid->links(); @endphp
									</div>
								</div>
							</div>
						</div>
						<!-- tab delivered -->
						<div id="tab-delivered" class="row clearfix tab-pane fade {{ $tab == 'delivered' ? 'in active' : '' }}">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="datatable1" class="table table-striped table-bordered" style="width: 100%;">
										<caption></caption>
										<thead>
                                            <tr>
                                                <th scope="col">No</th>
												<th scope="col">{{ trans('translate.stel_payment_status_order_date') }}</th>
                                                <th scope="col">{{ trans('translate.rent_chamber_client_label_rent_date') }}</th>
                                                <th scope="col">{{ trans('translate.rent_chamber_client_label_rent_duration') }}</th>
												<th scope="col">Total</th> 
												<th scope="col">PIC</th>   
												<th scope="col" class="center">Status</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $i = 0; @endphp
											@if($data_delivered)
											@foreach($data_delivered as $keys => $item)
												<tr>
													<td>{{$no+(($data_delivered->currentPage()-1)*$data_delivered->perPage())}}</td>
													<td>{{$item->created_at}}</td>
                                                    <td>
                                                        {{$item->start_date}} 
                                                        @if($item->duration > 1) 
                                                            & {{$item->end_date}} 
                                                        @endif
                                                    </td>
                                                    <td>{{$item->duration}} {{ trans('translate.chamber_days') }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-info" style="line-height: 2;">Delivered</span></td>
													<td>
														<a class="label label-sm label-success" style="line-height: 2;" href="{{URL::to('downloadkuitansichamber/'.$item->id.'/')}}">{{ trans('translate.receipt') }}</a>
														<a class="label label-sm label-success" style="line-height: 2;" href="{{URL::to('downloadfakturchamber/'.$item->id.'/')}}">{{ trans('translate.tax_invoice') }}</a>
														<a class="label label-sm label-info" style="line-height: 2;" href="{{URL::to('cetakTiketChamber/'.$item->id.'/')}}">{{ trans('translate.ticket') }}</a>
													</td>
												</tr> 
											@php $no++;$i++; @endphp
											@endforeach
											@else
												<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-striped">
															<caption></caption>
															<tr class="center">
																<th style="text-align: center;" scope="col">{{ trans('translate.data_not_found') }}</th>
															</tr> 
														</table>
													</div>
												</div>
											@endif
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
										@php echo $data_delivered->links(); @endphp
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section><!-- #content end -->


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
	const currentTab = url.searchParams.get("tab") ?? 'tab-unpaid';

	// Set destination when button filter(submit) click
	$('#filter').click(()=>{
		let param = getFilterParam();
		document.location.href = baseUrl+'/chamber_history?'+$.param(param);
	});

	// Set destination when search is press with enter key
	// $('#filter_search_input').keypress(function(e) {
	// 	if(e.which != 13) { return; }
	// 	let param = getFilterParam();
	// 	document.location.href = baseUrl+'/chamber_history?'+$.param(param);
	// });


});
const url = new URL(window.location.href);
currentTab = url.searchParams.get("tab") ?? 'tab-unpaid';

// Get url parameter from filter
const getFilterParam = () =>  {
	return {
		// search: $('#filter_search_input').val(),
		after_date: $('#filter_after_date_input').val(),
		before_date: $('#filter_before_date_input').val(),
		tab: $('.nav-tabs .active').attr('data-tab'),
	}
};

// Set filter from url parameter
const setFilterByParam = () =>  {
	// $('#filter_search_input').val(url.searchParams.get("search"));
	$('#filter_after_date_input').val(url.searchParams.get("after_date"));
	$('#filter_before_date_input').val(url.searchParams.get("before_date"));
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

// Reset Date filter fields
function resetDate(){
	$('#filter_after_date_input, #filter_before_date_input').val("");
}
</script>
@endsection