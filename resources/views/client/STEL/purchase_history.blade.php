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
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>{{ trans('translate.stel_payment_status_order_date') }}</th>
											<th>{{ trans('translate.stel_code') }}</th> 
											<th>Total</th> 
											<th>PIC</th>   
											<th>Status</th>
											<th colspan="2" class="center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $i = 0; if($data){ ?>
										@foreach($data as $keys => $item)
											<?php if($data[$i]->sales_detail){ $data_stel_name = ""; $data_stel_code = ""; $count = 0 ?>
												@foreach($data[$i]->sales_detail as $item_detail)
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
											<?php } $out = strlen($data_stel_code) > 25 ? substr($data_stel_code,0, 25)."..." : $data_stel_code; ?>
											<tr>
												<td>{{++$keys}}</td>
												<td>{{$item->created_at}}</td>
												<td>{{ $out }}</td>
												<td>{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($item->total), 0, '.', ','); ?></td>
												<td>{{$item->user->name}}</td>  
													<?php
													switch ($item->payment_status) {
														case -1:
															echo '<td class="center"><span class="label label-sm label-danger" style="line-height: 2;">Paid (decline)</span></td>';
															break; 
														case 0:
															echo '<td class="center"><span class="label label-sm label-default" style="line-height: 2;">Unpaid</span></td>';
															break; 
														case 1:
															echo '<td class="center"><span class="label label-sm label-success" style="line-height: 2;">Paid (success)</span></td>';
															break; 
														case 2:
															echo '<td class="center"><span class="label label-sm label-warning" style="line-height: 2;">Paid (waiting confirmation)</span></td>';
															break; 
														default:
															# code...
															break;
													}
													?>
												<td><a href="{!! url("upload_payment/{$item->id}") !!}">{{ trans('translate.examination_upload_payment') }}</a></td> 
												<td><a href="javascript:void(0)" class="collapsible">{{ trans('translate.examination_detail') }}</a></td> 
											</tr> 
											<tr class="content" style="display: none;">
												<td colspan="8">
													<table class="table table-striped table-bordered" cellspacing="0" width="100%">
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
												</td>
											</tr>
											<tr class="content" style="display: none;"><td colspan="8"></td></tr>
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
									</tbody>
								</table>
						
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