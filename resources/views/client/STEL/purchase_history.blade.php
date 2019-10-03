@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.payment_status') }} - Telkom DDS</title>
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
					<div class="row">   
						<?php $i = 0; if($data){ ?>
							@foreach($data as $item)
							<div class="col-md-12 list-border-progress">
								<div class="table-responsive">
									<table class="table table-striped">
										<tr>
											<td>Invoice</td>
											<td>: {{ $item->invoice }}</td>
										</tr>
										<tr>
											<td>{{ trans('translate.stel_payment_status_order_date') }}</td>
											<td>: {{ $item->created_at }}</td>
										</tr>
										<tr>
											<td>Status</td>
											<td>: <?php
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
										</tr>
										<tr>
											<td>PIC</td>
											<td>: {{ $item->user->name }}</td>
										</tr>
										<tr>
											<td colspan="2"><a href="{!! url("upload_payment/{$item->id}") !!}">{{ trans('translate.examination_upload_payment') }}</a></td> 
										</tr>
									</table>
									<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>No</th>
												<th>{{ trans('translate.stel_name') }}</th>
												<th>{{ trans('translate.stel_code') }}</th>
												<th>{{ trans('translate.stel_price') }}</th>
												<th>{{ trans('translate.stel_qty') }}</th>  
												<th>Total</th>  
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php $total = 0; $payment_status = $item->payment_status; $invoice = $item->invoice; $company_name = $item->user->company->name; 
											if($data[$i]->sales_detail){ ?>
												@foreach($data[$i]->sales_detail as $keys => $item_detail)
													<?php if($item_detail->stel){ ?>
														<tr>
															<td>{{++$keys}}</td>
															<td>{{$item_detail->stel->name}}</td>
															<td>{{$item_detail->stel->code}}</td>
															<td>{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($item_detail->stel->price), 0, '.', ','); ?></td> 
															<td>{{$item_detail->qty}}</td> 
															<td align="right">{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($item_detail->stel->price * $item_detail->qty), 0, '.', ','); ?></td>
															<?php  
																if($item_detail->attachment !="" && $payment_status == 1){
															?>
																	<td colspan="6" align="center"><a target="_blank" href="{{ URL::to('/client/downloadstelwatermark/'.$item_detail->id) }}">{{ trans('translate.download') }} File</a></td>
															<?php }
															else if($item_detail->attachment !="" && $payment_status == 1){
															?>
																<td colspan="6" align="center"><a target="_blank" href="{!! url("cetakstel?invoice_id={$invoice}&attach={$item_detail->stel->attachment}&company_name={$company_name}") !!}">{{ trans('translate.download') }} File</a></td>
															<?php
															}
																else{
															?>	
																	<td colspan="6" align="center">{{ trans('translate.document_not_found') }}</td>
															<?php 
																}
															?>  
														</tr> 
													<?php $total +=($item_detail->stel->price * $item_detail->qty);?>
													<?php }else{?> 
														<tr>
															<td>{{++$keys}}</td>
															<td colspan="6" align="center">{{ trans('translate.document_not_found') }}</td>
														</tr> 
													<?php }?>
												@endforeach

											<?php }else{?> 
												<tr align="center">
													<td colspan="7" style="text-align: center;">{{ trans('translate.data_not_found') }}</td>
												</tr> 
											<?php }?>
										</tbody>
										<tfoot> 
				                        	<tr>
				                        		<td colspan="5" align="right"> </td>
				                        		<td align="right">{{ trans('translate.stel_rupiah') }}. <?php 
				                        			echo 	number_format($total, 0, '.', ',');?></td>
				                        	</tr>
				                       		<tr>
				                        		<td colspan="5" align="right"> {{ trans('translate.tax') }}</td>
				                        		<td align="right">{{ trans('translate.stel_rupiah') }}. <?php $tax =  $total * (config("cart.tax")/100);
				                        			echo	number_format($tax, 0, '.', ',');?></td>
				                        	</tr>
				                        	<tr>
				                        		<td colspan="5" align="right"> Total</td>
				                        		<td align="right">{{ trans('translate.stel_rupiah') }}. <?php echo number_format($total+$tax, 0, '.', ',');?></td>
				                        	</tr> 
										</tfoot>
									</table>
								</div>
							</div>
							<?php $i++; ?>
							@endforeach
						<?php }else{?> 
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-striped">
										<tr align="center">
											<th style="text-align: center;">{{ trans('translate.data_not_found') }}</th>
										</tr> 
									</table>
								</div>
							</div>
						<?php }?>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
									<?php echo $data->links(); ?>
								</div>
							</div>
						</div>
					</div>
				 
						
					 
				</div>
			</div>


		</div>

	</section><!-- #content end -->


@endsection
 
