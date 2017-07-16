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
								<th>{{ trans('translate.stel_price_total') }}</th>  
								<th>#</th>
							</tr>
						</thead>
						<tbody>
						@foreach($stels as $keys => $stel)
							<tr>
								<td>{{++$keys}}</td>
								<td>{{$stel->name}}</td>
								<td>{{$stel->code}}</td> 
								<td>{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($stel->price), 0, '.', ','); ?></td>
							 

								>
								<td>{{ trans('translate.stel_rupiah') }}. <?php echo number_format(floatval($stel->price + ($stel->price * (config("cart.tax")/100))), 0, '.', ','); ?></td>
								 
								<?php  
								// if($stel->attachment !="" && $stel->payment_status == 1){
								if($stel->attachment !="" && $stel->payment_status == 1){
								?>
								<td><a target="_blank" href="{!! url("cetakstel?invoice_id={$stel->invoice}&attach={$stel->attachment}&company_name={$stel->company_name}") !!}">Download File</a></td>
								<?php }else{?>
								<td> Dokumen Tidak Tersedia</td>
								<?php }?>

							</tr> 
						@endforeach
						</tbody>
					</table>

				</div>
				<div class="col-md-12">
					<a class="button button-3d btn-sky nomargin" href="{{url('/payment_status')}}">{{ trans('translate.back') }}</a>
				</div>
			</div>



			</div>

		</div>

	</section><!-- #content end -->


@endsection
 
