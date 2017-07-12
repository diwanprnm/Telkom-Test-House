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
					<li><a href="#">{{ trans('translate.payment_status') }}</a></li>
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
								<th>#</th>
							</tr>
						</thead>
						<tbody>
						@foreach($stels as $keys => $stel)
							<tr>
								<td>{{++$keys}}</td>
								<td>{{$stel->name}}</td>
								<td>{{$stel->code}}</td> 
								<td>{{$stel->price}}</td> 
								<?php  if($stel->attachment){?>
								<td><a href="{!! url("cetakstel?invoice_id={$stel->invoice}&attach={$stel->attachment}") !!}">Download File</a></td>
								<?php }else{?>
								<td> </td>
								<?php }?>

							</tr> 
						@endforeach
						</tbody>
					</table>

				</div>

			</div>



			</div>

		</div>

	</section><!-- #content end -->


@endsection
 
