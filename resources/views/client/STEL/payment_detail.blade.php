@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.stel_payment_detail') }} - Telkom DDS</title>
@section('content')
 <!-- Page Title
	============================================= -->
	<section id="page-title">

		<div class="container clearfix">
			<h1>{{ trans('translate.stel_payment_detail') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">STEL</a></li>
					<li><a href="{{url('/payment_status')}}">{{ trans('translate.payment_status') }}</a></li>
				<li class="active">{{ trans('translate.stel_payment_detail') }}</li>
				</ol>
		</div>

	</section><!-- #page-title end -->

	<!-- Content
	============================================= -->
	<section id="content">

		<div class="content-wrap">


			<div class="container clearfix">

				<div class="table-responsive">

					<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>{{ trans('translate.stel_name') }}</th>
								<th>{{ trans('translate.stel_code') }}</th>
								<th>{{ trans('translate.stel_price') }}</th>  
								<th>{{ trans('translate.stel_qty') }}</th>  
								<th>Total</th>  
								<th>#</th>  
								
							</tr>
						</thead>
						<tbody>
						<?php $total = 0;?>
						@foreach($stels as $keys => $stel)
							<tr>
								<td>{{++$keys}}</td>
								<td>{{$stel->name}}</td>
								<td>{{$stel->code}}</td> 
								<td>{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($stel->price), 0, '.', ','); ?></td>
							  	 <td>{{$stel->qty}}</td> 
								<td align="right">{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($stel->price * $stel->qty), 0, '.', ','); ?></td>
								<?php  
									if($stel->manual_attachment !="" && $stel->payment_status == 1){
								?>
										<td colspan="6" align="center"><a target="_blank" href="{{ URL::to('/client/downloadstelwatermark/'.$stel->id_attachment_stel) }}">{{ trans('translate.download') }} File</a></td>
								<?php }
								else if($stel->attachment !="" && $stel->payment_status == 1){
								?>
									<td colspan="6" align="center"><a target="_blank" href="{!! url("cetakstel?invoice_id={$stel->invoice}&attach={$stel->attachment}&company_name={$stel->company_name}") !!}">{{ trans('translate.download') }} File</a></td>
								<?php
								}
									else{
								?>	
										<td colspan="6" align="center">{{ trans('translate.document_not_found') }}</td>
								<?php 
									}
								?> 
							</tr> 
							<?php $total +=($stel->price * $stel->qty); ?>
						@endforeach
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
				<div class="row">
					<div class="col-md-6">
						<a class="button button-3d btn-sky nomargin" href="{{url('/payment_status')}}">{{ trans('translate.back') }}</a>
					</div>
						<div class="col-md-6">
				@if($stels[0]->payment_status == 1)
					@if($stels[0]->id_kuitansi != '')
							<a class="button button-3d btn-sky nomargin pull-right invoice-group-button" href="{{ URL::to('/client/downloadkuitansistel/'.$stels[0]->id_kuitansi) }}" target="_blank">
								{{ trans('translate.see_receipt') }}
							</a>
					@endif
					@if($stels[0]->faktur_file != '')
							<a class="button button-3d btn-sky nomargin pull-right invoice-group-button" href="{{ URL::to('/client/downloadfakturstel/'.$stels[0]->manual_id) }}" target="_blank">
								{{ trans('translate.see_invoice') }}
							</a>
					@endif
				@endif
						</div>
				</div>
			</div>



			</div>

		</div>

	</section><!-- #content end -->


@endsection
 
