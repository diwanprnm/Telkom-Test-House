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
						<br><br>
						<div class="col-md-12">
							<div class="table-responsive">

								<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<caption></caption>
									<thead>
										<tr>
											<th scope="col">No</th>
											<th scope="col">{{ trans('translate.stel_payment_status_order_date') }}</th>
											<th scope="col">Invoice</th>
											<th scope="col">{{ trans('translate.stel_payment_code') }}</th> 
											<th scope="col">Total</th> 
											<th scope="col">Status</th>
											<th scope="col">{{ trans('translate.stel_payment_status_complete_time') }}</th>   
											<th colspan="2" class="center"  scope="col">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php  if(count($stels)>0){ ?>
											@foreach($stels as $keys => $stel)
												<tr>
													<td>{{++$keys}}</td>
													<td>{{$stel->created_at}}</td>
													<td>{{$stel->invoice}}</td>
													<td>{{$stel->payment_code}}</td> 
													<td>{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($stel->total), 0, '.', ','); ?></td>
													
													<td>
														<?php
														switch ($stel->payment_status) {
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
													<td>{{$stel->update_at}}</td>  
													<td><a href="{!! url("upload_payment/{$stel->id}") !!}">{{ trans('translate.examination_upload_payment') }}</a></td> 
													<td><a href="{!! url("payment_detail/{$stel->id}") !!}">{{ trans('translate.examination_detail') }}</a></td> 
												</tr> 
											@endforeach

										<?php }else{?> 
											<tr class="center">
												<td colspan="7" style="text-align: center;">{{ trans('translate.data_not_found') }}</td>
											</tr> 
										<?php }?>
									</tbody>
								</table>

							</div>
						</div>
					</div>
				 
						
					 
				</div>
			</div>


		</div>

	</section><!-- #content end -->


@endsection
 
