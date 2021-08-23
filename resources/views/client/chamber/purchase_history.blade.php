@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.chamber_history') }} - Telkom DDB</title>
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
					<ul class="nav nav-tabs clearfix">
						<li class="{{ $tab == 'unpaid' ? 'active' : '' }}"><a href="#tab-unpaid" data-toggle="tab"><strong>Unpaid</strong></a></li>
						<li class="{{ $tab == 'paid' ? 'active' : '' }}"><a href="#tab-paid" data-toggle="tab"><strong>Paid</strong></a></li>
						<li class="{{ $tab == 'delivered' ? 'active' : '' }}"><a href="#tab-delivered" data-toggle="tab"><strong>Delivered</strong></a></li>
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
                                                        @if($item->end_date) 
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
                                                        @if($item->end_date) 
                                                            & {{$item->end_date}} 
                                                        @endif
                                                    </td>
                                                    <td>{{$item->duration}} {{ trans('translate.chamber_days') }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-success" style="line-height: 2;">Paid</span></td>
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
                                                        @if($item->end_date) 
                                                            & {{$item->end_date}} 
                                                        @endif
                                                    </td>
                                                    <td>{{$item->duration}} {{ trans('translate.chamber_days') }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-info" style="line-height: 2;">Delivered</span></td>
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

<script type="text/javascript">
	
</script>

@endsection