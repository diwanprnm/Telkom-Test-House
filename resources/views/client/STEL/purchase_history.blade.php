@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.payment_status') }} - Telkom DDB</title>
@section('content')
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
												@php if($data_unpaid[$i]->sales_detail){ $data_stel_name = ""; $data_stel_code = ""; $count = 0 @endphp
													@foreach($data_unpaid[$i]->sales_detail as $item_detail)
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
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-default" style="line-height: 2;">Unpaid</span></td>
													<td>
														@if($data_unpaid[$i]->payment_method == 2 && $data_unpaid[$i]->VA_expired < date("Y-m-d H:i:s"))
															<a class="label label-sm label-danger" style="line-height: 2;" href="{{url('/payment_confirmation/'.$item->id)}}">{{ trans('translate.expired') }}</a>
														@else
															<a class="label label-sm label-warning" style="line-height: 2;" href="{{url('/payment_confirmation/'.$item->id)}}">{{ trans('translate.examination_pay_now') }}</a>
														@endif
														||
														<a href="javascript:void(0)" class="collapsible">{{ trans('translate.examination_detail') }}</a>
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
																			<tr>
																				<td>{{++$keys}}</td>
																				<td>{{$item_detail->stel->name}}</td>
																				<td>{{$item_detail->stel->code}}</td>
																				<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item_detail->stel->price), 0, '.', ','); @endphp</td> 
																				<td class="right">{{$item_detail->qty}}</td> 
																				<td class="right">@php echo number_format(floatval($item_detail->stel->price * $item_detail->qty), 0, '.', ','); @endphp</td>
																				<td colspan="6" class="center">{{ trans('translate.document_not_found') }}</td>
																		</tr> 	
																		@php $total +=($item_detail->stel->price * $item_detail->qty);@endphp
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
																	<td class="right">@php echo number_format($data_unpaid[$i]->total, 0, '.', ',');@endphp</td>
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
												@php if($data_paid[$i]->sales_detail){ $data_stel_name = ""; $data_stel_code = ""; $count = 0 @endphp
													@foreach($data_paid[$i]->sales_detail as $item_detail)
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
													<td>{{$no+(($data_paid->currentPage()-1)*$data_paid->perPage())}}</td>
													<td>{{$item->created_at}}</td>
													<td>{{ $out }}</td>
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-success" style="line-height: 2;">Paid</span></td>
													<td><a href="javascript:void(0)" class="collapsible">{{ trans('translate.examination_detail') }}</a></td>
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
																			<tr>
																				<td>{{++$keys}}</td>
																				<td>{{$item_detail->stel->name}}</td>
																				<td>{{$item_detail->stel->code}}</td>
																				<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item_detail->stel->price), 0, '.', ','); @endphp</td> 
																				<td class="right">{{$item_detail->qty}}</td> 
																				<td class="right">@php echo number_format(floatval($item_detail->stel->price * $item_detail->qty), 0, '.', ','); @endphp</td>
																				@if($item_detail->attachment !="")
																					<td colspan="6" class="center"><a target="_blank" href="{{ URL::to('/client/downloadstelwatermark/'.$item_detail->id) }}">{{ trans('translate.download') }} File</a></td>
																				@else
																					<td colspan="6" class="center">{{ trans('translate.document_not_found') }}</td>
																				@endif
																			</tr> 
																		@php $total +=($item_detail->stel->price * $item_detail->qty);@endphp
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
																	<td class="right">@php echo number_format($data_paid[$i]->total, 0, '.', ',');@endphp</td>
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
												@php if($data_delivered[$i]->sales_detail){ $data_stel_name = ""; $data_stel_code = ""; $count = 0 @endphp
													@foreach($data_delivered[$i]->sales_detail as $item_detail)
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
													<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item->total), 0, '.', ','); @endphp</td>
													<td>{{$item->user->name}}</td>  
													<td class="center"><span class="label label-sm label-info" style="line-height: 2;">Delivered</span></td>
													<td><a href="javascript:void(0)" class="collapsible">{{ trans('translate.examination_detail') }}</a><span class="glyphicon glyphicon-search" aria-hidden="true"></span></td>
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
																			<tr>
																				<td>{{++$keys}}</td>
																				<td>{{$item_detail->stel->name}}</td>
																				<td>{{$item_detail->stel->code}}</td>
																				<td>{{ trans('translate.stel_rupiah') }}. @php echo number_format(floatval($item_detail->stel->price), 0, '.', ','); @endphp</td> 
																				<td class="right">{{$item_detail->qty}}</td> 
																				<td class="right">@php echo number_format(floatval($item_detail->stel->price * $item_detail->qty), 0, '.', ','); @endphp</td>
																				@if($item_detail->attachment !="")
																					<td colspan="6"><a target="_blank" href="{{ URL::to('/client/downloadstelwatermark/'.$item_detail->id) }}">{{ trans('translate.download') }} File</a></td>
																				@else
																					<td colspan="6" class="center">{{ trans('translate.document_not_found') }}</td>
																				@endif
																			</tr> 
																		@php $total +=($item_detail->stel->price * $item_detail->qty);@endphp
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
																	<td class="right">@php echo number_format($data_delivered[$i]->total, 0, '.', ',');@endphp</td>
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
</script>

@endsection