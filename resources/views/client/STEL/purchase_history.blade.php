@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.payment_status') }} - Telkom Test House</title>
@section('content')
<style>
	.tooltip-text {
		width: 2000%;
		position: absolute;
		display: none;
		/* background: ghostwhite; */
		padding: 10px;
		font-size: 12px;
		border-radius: 3px; 
		transition: opacity 1s ease-out;
		bottom: -50%;
		margin-left: -1950%;
	}

	/* .fa.fa-warning:hover + .tooltip-text { */
	.fa-warning:hover .tooltip-text, .fa-info-circle:hover .tooltip-text {
		display: block;
		text-align: left;
		animation: fadeIn 2s;
	}
</style>
 <!-- Page Title
	============================================= -->
	<section id="page-title">

		<div class="container clearfix">
			<div class="container clearfix">
			<h1>{{ trans('translate.payment_status') }}</h1>
			
			<ol class="breadcrumb">
				<li><a href="#">STEL</a></li>
				<li class="active">{{ trans('translate.payment_status') }}</li>
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
						<div class="row offset-0"> 
							<a class="btn btn-default pull-right" style="margin-right: 1.25rem" href="{{URL::to('purchase_history')}}">Reset <em class="fa fa-refresh"></em></a>
							<div class="col-md-4 pull-right">
								<span class="input-icon input-icon-right search-table  float-right"> 
									<input id="search_stel_product" name="search" type="search" placeholder="{{ trans('translate.search_STEL') }}" id="form-field-17" class="form-control " value="{{ $search }}">
								</span> 
							</div>
						</div>
						<ul class="nav nav-tabs clearfix">
							<li class="{{ $tab == 'unpaid' ? 'active' : '' }}"  data-tab="unpaid"><a href="#tab-unpaid" data-toggle="tab"><strong>Unpaid</strong></a></li>
							<li class="{{ $tab == 'paid' ? 'active' : '' }}"  data-tab="paid"><a href="#tab-paid" data-toggle="tab"><strong>Paid</strong></a></li>
							<li class="{{ $tab == 'delivered' ? 'active' : '' }}" data-tab="delivered"><a href="#tab-delivered" data-toggle="tab"><strong>Delivered</strong></a></li>
							<li class="{{ $tab == 'expired' ? 'active' : '' }}" data-tab="expired"><a href="#tab-expired" data-toggle="tab"><strong>Old Document</strong></a></li>
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
												<th scope="col">{{ trans('translate.stel_code') }}</th> 
												<th scope="col">Total</th> 
												<th scope="col">PIC</th>   
												<th scope="col">Status</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $i = 0; @endphp
											@if($data_unpaid)
											@foreach($data_unpaid as $keys => $item)
												@php $update = 0; $update1 = 0; $update2 = 0; @endphp
												@php if($data_unpaid[$i]->sales_detail){ $data_stel_name = ""; $data_stel_code = ""; $count = 0 @endphp
													@foreach($data_unpaid[$i]->sales_detail as $item_detail)
														@php 
														if($item_detail->temp_alert == 1) {
															$update1 = 1;
														} 
														@endphp
														@php 
														if($item_detail->stel && $count < 2){
															if($data_stel_name == ""){
																$data_stel_name = $item_detail->stel->name;
																$data_stel_code = $item_detail->stel->code;
															}else{
																$data_stel_name = $data_stel_name.", ".$item_detail->stel->name;
																$data_stel_code = $data_stel_code.", ".$item_detail->stel->code;
															}
														}
														$count++;
														@endphp
													@endforeach
												@php } $out = strlen($data_stel_code) > 25 ? substr($data_stel_code,0, 25)."..." : $data_stel_code; @endphp
												<tr>
													<td>{{$no+(($data_unpaid->currentPage()-1)*$data_unpaid->perPage())}}</td>
													<td>{{$item->created_at}}</td>
													<td>{{ $out }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp @if ($item->total == 0) (Free) @endif</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-default" style="line-height: 2;">Unpaid</span></td>
													<td colspan="5">
														@if($data_unpaid[$i]->payment_method == 2 && $data_unpaid[$i]->VA_expired < date("Y-m-d H:i:s"))
															<a class="label label-sm label-danger" style="line-height: 2;" href="{{url('/payment_confirmation/'.$item->id)}}">{{ trans('translate.expired') }}</a>
														@else
															<a class="label label-sm label-warning" style="line-height: 2;" href="{{url('/payment_confirmation/'.$item->id)}}">{{ trans('translate.examination_pay_now') }}</a>
														@endif
														||
														<a href="javascript:void(0)" class="collapsible">{{ trans('translate.examination_detail') }}</a>
														@if($update1)
														<a class="right">
															<em class="fa fa-warning warning">
															<span class="tooltip-text alert alert-warning">
																<em class="fa fa-warning"></em>
																{{ trans('translate.purchase_history_updateSTEL_unpaid') }}
															</span>
															</em>
														</a>
														@endif
													</td>
												</tr> 
												<tr class="content" style="display: none;">
													<td colspan="7" class="center">
														<table class="table table-striped" style="width: 100%;">
															<caption></caption>
															<thead>
																<tr>
																	<th scope="col">No</th>
																	<th scope="col">{{ trans('translate.stel_name') }}</th>
																	<th scope="col">{{ trans('translate.stel_code') }}</th>
																	<th scope="col">{{ trans('translate.stel_price') }}</th>
																	<th scope="col">{{ trans('translate.stel_qty') }}</th>  
																	<th scope="col" class="right">Total ({{ trans('translate.stel_rupiah') }}.)</th>  
																	<th scope="col">Action</th>
																</tr>
															</thead>
															<tbody>
																@php $total = 0; $invoice = $item->invoice; $company_name = $item->user->company->name; 
																if($data_unpaid[$i]->sales_detail){ @endphp
																	@foreach($data_unpaid[$i]->sales_detail as $keys => $item_detail)
																		@php if($item_detail->stel){ @endphp
																			@if($item_detail->stel->is_active == 0)
																			<tr style="font-style: italic;text-decoration: line-through;">
																			@else
																			<tr>
																			@endif
																				<td>{{++$keys}}</td>
																				<td>{{$item_detail->stel->name}}</td>
																				<td>{{$item_detail->stel->code}}</td>
																				@php
																					$price = $data_unpaid[$i]->total ? $item_detail->stel->price : 0;
																				@endphp
																				<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($price), 0, '.', ','); @endphp @if ($price == 0) (Free) @endif</td> 
																				<td class="right">{{$item_detail->qty}}</td> 
																				<td class="right">@php echo number_format(floatval($price * $item_detail->qty), 0, '.', ','); @endphp</td>
																				<td colspan="6" class="center">{{ trans('translate.document_not_found') }}
																					@if($item_detail->stel->is_active == 0)
																					<a>
																					<em class="fa fa-info-circle info">
																					<span class="tooltip-text alert alert-info">
																						<em class="fa fa-info-circle"></em>
																						{{ trans('translate.purchase_history_updateSTEL_available') }}
																					</span>
																					</em>
																					</a>
																					@endif
																				</td>
																		</tr> 	
																		@php $total +=($price * $item_detail->qty);@endphp
																		@php }else{@endphp 
																			<tr>
																				<td>{{++$keys}}</td>
																				<td colspan="6" class="center">{{ trans('translate.document_not_found') }}</td>
																			</tr> 
																		@php }@endphp
																	@endforeach

																@php }else{@endphp 
																	<tr class="center">
																		<td colspan="7" style="text-align: center;">{{ trans('translate.data_not_found') }}</td>
																	</tr> 
																@php }@endphp
															</tbody>
															<tfoot> 
																<tr>
																	<td colspan="5" class="right"> {{ trans('translate.tax') }}</td>
																	<td class="right">@php $tax =  ($total) * (config("cart.tax")/100);
																		echo	number_format($tax, 0, '.', ',');@endphp</td>
																	<td>
																		@if($item->faktur_file != '')
																			<a target="_blank" href="{{ URL::to('/client/downloadfakturstel/'.$item->id) }}">
																				{{ trans('translate.download') }} {{ trans('translate.tax_invoice') }}
																			</a>
																		@endif
																	</td>
																</tr>
																<tr style="font-weight: bold;">
																	<td colspan="5" class="right"> Total</td>
																	<td class="right">@php echo number_format($data_unpaid[$i]->total, 0, '.', ',');@endphp @if ($data_unpaid[$i]->total == 0) (Free) @endif</td>
																	<td>
																		@if($item->id_kuitansi != '')
																			<a target="_blank" href="{{ URL::to('/client/downloadkuitansistel/'.$item->id_kuitansi) }}">
																				{{ trans('translate.download') }} {{ trans('translate.receipt') }}
																			</a>
																		@endif
																	</td>
																</tr> 
															</tfoot>
														</table>
													</td>
												</tr>
												<tr class="content" style="display: none;"><td colspan="7"></td></tr>
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
												<th scope="col">{{ trans('translate.stel_code') }}</th> 
												<th scope="col">Total</th> 
												<th scope="col">PIC</th>   
												<th scope="col">Status</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $i = 0; @endphp
											@if($data_paid)
											@foreach($data_paid as $keys => $item)
												@php $update = 0; $update1 = 0; $update2 = 0; $update3 = 0; @endphp
												@php if($data_paid[$i]->sales_detail){ $data_stel_name = ""; $data_stel_code = ""; $count = 0 @endphp
													@foreach($data_paid[$i]->sales_detail as $item_detail)
														@if($item_detail->stel)
															@php 
															if($item_detail->stel->is_active == 1 && $item_detail->temp_alert == 2) {
																$update2 = 1;
															} 
															if($item_detail->stel->is_active == 0 && $item_detail->temp_alert == 2) {
																$update3 = 1;
															} 
															@endphp
															@php 
															if($count < 2){
																if($data_stel_name == ""){
																	$data_stel_name = $item_detail->stel->name;
																	$data_stel_code = $item_detail->stel->code;
																}else{
																	$data_stel_name = $data_stel_name.", ".$item_detail->stel->name;
																	$data_stel_code = $data_stel_code.", ".$item_detail->stel->code;
																}
															}
															$count++;
															@endphp
														@endif
													@endforeach
												@php } $out = strlen($data_stel_code) > 25 ? substr($data_stel_code,0, 25)."..." : $data_stel_code; @endphp
												<tr>
													<td>{{$no+(($data_paid->currentPage()-1)*$data_paid->perPage())}}</td>
													<td>{{$item->created_at}}</td>
													<td>{{ $out }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp @if ($item->total == 0) (Free) @endif</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-success" style="line-height: 2;">Paid</span></td>
													<td><a href="javascript:void(0)" class="collapsible">{{ trans('translate.examination_detail') }}</a>
														@if($update2)
														<a class="right">
															<em class="fa fa-info-circle info">
															<span class="tooltip-text alert alert-info">
																<em class="fa fa-info-circle"></em>
																{{ trans('translate.purchase_history_updateSTEL_paid') }}
															</span>
															</em>
														</a>
														@endif
														@if($update3)
														<a class="right">
															<em class="fa fa-warning warning">
															<span class="tooltip-text alert alert-warning">
																<em class="fa fa-warning"></em>
																{{ trans('translate.purchase_history_updateSTEL_warning') }}
															</span>
															</em>
														</a>
														@endif
													</td>
												</tr> 
												<tr class="content" style="display: none;">
													<td colspan="7" class="center">
														<table class="table table-striped" style="width: 100%;">
															<caption></caption>
															<thead>
																<tr>
																	<th scope="col">No</th>
																	<th scope="col">{{ trans('translate.stel_name') }}</th>
																	<th scope="col">{{ trans('translate.stel_code') }}</th>
																	<th scope="col">{{ trans('translate.stel_price') }}</th>
																	<th scope="col">{{ trans('translate.stel_qty') }}</th>  
																	<th scope="col" class="right">Total ({{ trans('translate.stel_rupiah') }}.)</th>  
																	<th scope="col">Action</th>
																</tr>
															</thead>
															<tbody>
																@php $total = 0; $invoice = $item->invoice; $company_name = $item->user->company->name; 
																if($data_paid[$i]->sales_detail){ @endphp
																	@foreach($data_paid[$i]->sales_detail as $keys => $item_detail)
																		@php if($item_detail->stel){ @endphp
																			@if($item_detail->stel->is_active == 0)
																			<tr style="font-style: italic;text-decoration: line-through;">
																			@else
																			<tr>
																			@endif
																				<td>{{++$keys}}</td>
																				<td>{{$item_detail->stel->name}}</td>
																				<td>{{$item_detail->stel->code}}</td>
																				@php
																					$price = $data_paid[$i]->total ? $item_detail->stel->price : 0;
																				@endphp
																				<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($price), 0, '.', ','); @endphp @if ($price == 0) (Free) @endif</td> 
																				<td class="right">{{$item_detail->qty}}</td> 
																				<td class="right">@php echo number_format(floatval($price * $item_detail->qty), 0, '.', ','); @endphp</td>
																				@if($item_detail->attachment !="")
																					<td colspan="6"><a target="_blank" href="{{ URL::to('/client/downloadstelwatermark/'.$item_detail->id) }}">{{ trans('translate.download') }} File</a>
																						@if($item_detail->temp_alert)
																						<a>
																						<em class="fa fa-info-circle info">
																						<span class="tooltip-text alert alert-info">
																							<em class="fa fa-info-circle"></em>
																							{{ trans('translate.purchase_history_updateSTEL_available') }}
																						</span>
																						</em>
																						</a>
																						@endif
																					</td>
																				@else
																					<td colspan="6" class="center">{{ trans('translate.document_not_found') }}
																						@if($item_detail->temp_alert)
																						<a>
																						<em class="fa fa-info-circle info">
																						<span class="tooltip-text alert alert-info">
																							<em class="fa fa-info-circle"></em>
																							{{ trans('translate.purchase_history_updateSTEL_available') }}
																						</span>
																						</em>
																						</a>
																						@endif
																					</td>
																				@endif
																			</tr> 
																		@php $total +=($price * $item_detail->qty);@endphp
																		@php }else{@endphp 
																			<tr>
																				<td>{{++$keys}}</td>
																				<td colspan="6" class="center">{{ trans('translate.document_not_found') }}</td>
																			</tr> 
																		@php }@endphp
																	@endforeach

																@php }else{@endphp 
																	<tr class="center">
																		<td colspan="7" style="text-align: center;">{{ trans('translate.data_not_found') }}</td>
																	</tr> 
																@php }@endphp
															</tbody>
															<tfoot> 
																<tr>
																	<td colspan="5" class="right"> {{ trans('translate.tax') }}</td>
																	<td class="right">@php $tax =  ($total) * (config("cart.tax")/100);
																		echo	number_format($tax, 0, '.', ',');@endphp</td>
																	<td>
																		@if($item->faktur_file != '')
																			<a target="_blank" href="{{ URL::to('/client/downloadfakturstel/'.$item->id) }}">
																				{{ trans('translate.download') }} {{ trans('translate.tax_invoice') }}
																			</a>
																		@endif
																	</td>
																</tr>
																<tr style="font-weight: bold;">
																	<td colspan="5" class="right"> Total</td>
																	<td class="right">@php echo number_format($data_paid[$i]->total, 0, '.', ',');@endphp @if ($data_paid[$i]->total == 0) (Free) @endif</td>
																	<td>
																		@if($item->id_kuitansi != '')
																			<a target="_blank" href="{{ URL::to('/client/downloadkuitansistel/'.$item->id_kuitansi) }}">
																				{{ trans('translate.download') }} {{ trans('translate.receipt') }}
																			</a>
																		@endif
																	</td>
																</tr> 
															</tfoot>
														</table>
													</td>
												</tr>
												<tr class="content" style="display: none;"><td colspan="7"></td></tr>
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
												<th scope="col">{{ trans('translate.stel_code') }}</th> 
												<th scope="col">Total</th> 
												<th scope="col">PIC</th>   
												<th scope="col">Status</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $i = 0; @endphp
											@if($data_delivered)
											@foreach($data_delivered as $keys => $item)
												@php $update = 0; $update1 = 0; $update2 = 0; $update3 = 0; @endphp
												@php if($data_delivered[$i]->sales_detail){ $data_stel_name = ""; $data_stel_code = ""; $count = 0 @endphp
													@foreach($data_delivered[$i]->sales_detail as $item_detail)
														@php 
														if($item_detail->temp_alert == 1) {
															$update1 = 1;
														} 
														if($item_detail->temp_alert == 2) {
															$update2 = 1;
														} 
														if($item_detail->temp_alert == 3) {
															$update3 = 1;
														} 
														@endphp
														@php 
														if($item_detail->stel && $count < 2){
															if($data_stel_name == ""){
																$data_stel_name = $item_detail->stel->name;
																$data_stel_code = $item_detail->stel->code;
															}else{
																$data_stel_name = $data_stel_name.", ".$item_detail->stel->name;
																$data_stel_code = $data_stel_code.", ".$item_detail->stel->code;
															}
														}
														$count++;
														@endphp
													@endforeach
												@php } $out = strlen($data_stel_code) > 25 ? substr($data_stel_code,0, 25)."..." : $data_stel_code; @endphp
												<tr>
													<td>{{$no+(($data_delivered->currentPage()-1)*$data_delivered->perPage())}}</td>
													<td>{{$item->created_at}}</td>
													<td>{{ $out }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp @if ($item->total == 0) (Free) @endif</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-info" style="line-height: 2;">Delivered</span></td>
													<td><a href="javascript:void(0)" class="collapsible">{{ trans('translate.examination_detail') }}</a>
														@if($update1)
														<a class="right">
															<em class="fa fa-warning warning">
															<span class="tooltip-text alert alert-warning">
																<em class="fa fa-warning"></em>
																{{ trans('translate.purchase_history_updateSTEL_delivered_warning') }}
															</span>
															</em>
														</a>
														@endif
														@if($update2)
														<a class="right">
															<em class="fa fa-info-circle info">
															<span class="tooltip-text alert alert-info">
																<em class="fa fa-info-circle"></em>
																{{ trans('translate.purchase_history_updateSTEL_delivered_info') }}
															</span>
															</em>
														</a>
														@endif
														@if($update3)
														<a class="right">
															<em class="fa fa-warning warning">
															<span class="tooltip-text alert alert-warning">
																<em class="fa fa-warning"></em>
																{{ trans('translate.purchase_history_updateSTEL_warning') }}
															</span>
															</em>
														</a>
														@endif
													</td>
												</tr> 
												<tr class="content" style="display: none;">
													<td colspan="7" class="center">
														<table class="table table-striped" style="width: 100%;">
															<caption></caption>
															<thead>
																<tr>
																	<th scope="col">No</th>
																	<th scope="col">{{ trans('translate.stel_name') }}</th>
																	<th scope="col">{{ trans('translate.stel_code') }}</th>
																	<th scope="col">{{ trans('translate.stel_price') }}</th>
																	<th scope="col">{{ trans('translate.stel_qty') }}</th>  
																	<th scope="col" class="right">Total ({{ trans('translate.stel_rupiah') }}.)</th>  
																	<th scope="col">Action</th>
																</tr>
															</thead>
															<tbody>
																@php $total = 0; $invoice = $item->invoice; $company_name = $item->user->company->name; 
																if($data_delivered[$i]->sales_detail){ @endphp
																	@foreach($data_delivered[$i]->sales_detail as $keys => $item_detail)
																		@php if($item_detail->stel){ @endphp
																			@if($item_detail->stel->is_active == 0)
																			<tr style="font-style: italic;text-decoration: line-through;">
																			@else
																			<tr>
																			@endif
																				<td>{{++$keys}}</td>
																				<td>{{$item_detail->stel->name}}</td>
																				<td>{{$item_detail->stel->code}}</td>
																				@php
																					$price = $data_delivered[$i]->total ? $item_detail->stel->price : 0;
																				@endphp
																				<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($price), 0, '.', ','); @endphp @if ($price == 0) (Free) @endif</td> 
																				<td class="right">{{$item_detail->qty}}</td> 
																				<td class="right">@php echo number_format(floatval($price * $item_detail->qty), 0, '.', ','); @endphp</td>
																				@if($item_detail->attachment !="")
																					<td colspan="6"><a target="_blank" href="{{ URL::to('/client/downloadstelwatermark/'.$item_detail->id) }}">{{ trans('translate.download') }} File</a>
																						@if($item_detail->temp_alert == 1 OR $item_detail->temp_alert == 2)
																						<a>
																						<em class="fa fa-info-circle info">
																						<span class="tooltip-text alert alert-info">
																							<em class="fa fa-info-circle"></em>
																							{{ trans('translate.purchase_history_updateSTEL_available') }}
																						</span>
																						</em>
																						</a>
																						@endif
																					</td>
																				@else
																					<td colspan="6" class="center">{{ trans('translate.document_not_found') }}
																						@if($item_detail->temp_alert == 1 OR $item_detail->temp_alert == 2)
																						<a>
																						<em class="fa fa-info-circle info">
																						<span class="tooltip-text alert alert-info">
																							<em class="fa fa-info-circle"></em>
																							{{ trans('translate.purchase_history_updateSTEL_available') }}
																						</span>
																						</em>
																						</a>
																						@endif
																					</td>
																				@endif
																			</tr> 
																		@php $total +=($price * $item_detail->qty);@endphp
																		@php }else{@endphp 
																			<tr>
																				<td>{{++$keys}}</td>
																				<td colspan="6" class="center">{{ trans('translate.document_not_found') }}</td>
																			</tr> 
																		@php }@endphp
																	@endforeach

																@php }else{@endphp 
																	<tr class="center">
																		<td colspan="7" style="text-align: center;">{{ trans('translate.data_not_found') }}</td>
																	</tr> 
																@php }@endphp
															</tbody>
															<tfoot> 
																<tr>
																	<td colspan="5" class="right"> {{ trans('translate.tax') }}</td>
																	<td class="right">@php $tax =  ($total) * (config("cart.tax")/100);
																		echo	number_format($tax, 0, '.', ',');@endphp</td>
																	<td>
																		@if($item->faktur_file != '')
																			<a target="_blank" href="{{ URL::to('/client/downloadfakturstel/'.$item->id) }}">
																				{{ trans('translate.download') }} {{ trans('translate.tax_invoice') }}
																			</a>
																		@endif
																	</td>
																</tr>
																<tr style="font-weight: bold;">
																	<td colspan="5" class="right"> Total</td>
																	<td class="right">@php echo number_format($data_delivered[$i]->total, 0, '.', ',');@endphp @if ($data_delivered[$i]->total == 0) (Free) @endif</td>
																	<td>
																		@if($item->id_kuitansi != '')
																			<a target="_blank" href="{{ URL::to('/client/downloadkuitansistel/'.$item->id_kuitansi) }}">
																				{{ trans('translate.download') }} {{ trans('translate.receipt') }}
																			</a>
																		@endif
																	</td>
																</tr> 
															</tfoot>
														</table>
													</td>
												</tr>
												<tr class="content" style="display: none;"><td colspan="7"></td></tr>
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
						<!-- tab expired -->
						<div id="tab-expired" class="row clearfix tab-pane fade {{ $tab == 'expired' ? 'in active' : '' }}">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="datatable1" class="table table-striped table-bordered" style="width: 100%;">
										<caption></caption>
										<thead>
											<tr>
												<th scope="col">No</th>
												<th scope="col">{{ trans('translate.stel_name') }}</th>
												<th scope="col">{{ trans('translate.stel_code') }}</th>
											</tr>
										</thead>
										<tbody>
											@if($data_expired)
											@foreach($data_expired as $keys => $item)
												<tr>
													<td>{{++$keys}}</td>
													<td>{{$item->name}}</td>
													<td>{{$item->code}}</td>
												</tr> 
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
										@php echo $data_expired->links(); @endphp
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
	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
	  coll[i].addEventListener("click", function() {
	    this.classList.toggle("active");
	    var content = $(this).parents().parents().next()[0];
	    var content2 = $(this).parents().parents().next().next()[0];
	    if (content.style.display == "") {
	      content.style.display = "none";
	      content2.style.display = "none";
	    } else {
	      content.style.display = "";
	      content2.style.display = "";
	    }
	  });
	}

	jQuery(document).ready(function() {
		const url = new URL(window.location.href);
		const currentTab = url.searchParams.get("tab") ?? 'tab-unpaid';

		// Search STEL products by name or code
		$('#search_stel_product').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = { 
					search: document.getElementById("search_stel_product").value,
					tab: $('.nav-tabs .active').attr('data-tab')
					};	
				document.location.href = baseUrl+'/purchase_history?'+jQuery.param(params);
	        }
	    });
	});
</script>

{{-- Memunculkan tombol X untuk clear form search --}}
<style>
	input[type=search]::-webkit-search-cancel-button {
    -webkit-appearance: searchfield-cancel-button;
}
</style>

@endsection