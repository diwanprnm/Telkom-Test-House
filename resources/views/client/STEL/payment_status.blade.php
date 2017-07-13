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
						<div class="col-md-6 col-xs-12">
							<span class="input-icon input-icon-right search-table"> 
								<input id="search_stel_product" type="text" placeholder="{{ trans('translate.search_STEL') }}" id="form-field-17" class="form-control " value="{{ $search }}">
								<i class="ti-search"></i>
							</span>
						</div>
						<br><br>
						<div class="col-md-12">
							<div class="table-responsive">

								<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>{{ trans('translate.stel_payment_status_order_date') }}</th>
											<th>Invoice</th>
											<th>Payment Code</th> 
											<th>Total</th> 
											<th>Status</th>
											<th>{{ trans('translate.stel_payment_status_complete_time') }}</th>  
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
													
													<td>{{($stel->payment_status==0)?'Unpaid':'Paid'}}</td>
													<td>{{$stel->update_at}}</td> 
													<td><a href="{!! url("payment_detail/{$stel->id}") !!}">Detail</a></td> 
												</tr> 
											@endforeach

										<?php }else{?> 
											<tr align="center">
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

		</div>

	</section><!-- #content end -->


@endsection
 
