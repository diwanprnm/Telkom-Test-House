@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.stel_payment_detail') }} - Telkom DDB</title>
@section('content') 
 <!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.stel_payment_detail') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">STEL</a></li>
					<li><a href="#">{{ trans('translate.see_product') }}</a></li>
				<li class="active">{{ trans('translate.stel_payment_detail') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->
		<!-- Content
		============================================= -->
		<section id="content"> 
			<div class="container clearfix"> 
				@if (Session::get('error'))
					<div class="alert alert-error alert-danger" style="text-align: center; font-weight: bold; font-size: 110%;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
						{{ Session::get('error') }}
					</div>
				@endif
				@if (Session::get('message'))
					<div class="alert alert-warning" style="text-align: center; font-weight: bold; font-size: 110%;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
						{{ Session::get('message') }}
					</div>
				@endif
				<form id="form-checkout" class="nobottommargin" role="form" method="POST" action="{{ url('doCancel') }}" onsubmit="javascript:document.getElementById('submit-btn').style.display = 'none';document.getElementById('submit-msg').style.display = 'block';">
				<div class="row">    
					<div class="row"> 
					<p> No. Invoice	: {{$STELSales->invoice}} </p> 
						<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%" aria-describedby="mydesc">
							<thead>
								<tr>
									<th  scope="col">No</th>
									<th scope="col">{{ trans('translate.stel_name') }}</th>
										<th>{{ trans('translate.stel_code') }}</th>
									<th scope="col">{{ trans('translate.stel_price') }}</th> 
									<th  scope="col">{{ trans('translate.stel_qty') }}</th>
									<th  scope="col">Total</th> 
								</tr>
							</thead>
							<tbody>
								@php $no = 0; $sub_total = 0;@endphp
								  @foreach($STELSales->sales_detail as $row)
								  	@php $no++;@endphp
								<tr>
									<td>{{$no}}</td>
									<td>{{$row->stel->name}}</td>
									<td>{{$row->stel->code}}</td>
									<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($row->stel->price)}}</td> 
									<td align="center">{{$row->qty}}</td> 
									<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($row->stel->price*$row->qty)}}</td> 
								</tr> 
									@php $sub_total += $row->stel->price*$row->qty; @endphp
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									@php $tax = 0.1*$sub_total; @endphp
									<td colspan="5" align="right">{{ trans('translate.tax') }}</td> 
									<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($tax)}}</td> 
								</tr> 
								<tr style="font-weight: bold">
									<td colspan="5" align="right">Total</td>
									<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($STELSales->total)}}</td> 
								</tr> 
							</tfoot>
						</table> 
						<div class="form-group"> 
							<div class="row">
								<div class="col-md-3 form-group">
									<label>{{ trans('translate.payment_via_virtual_account') }} : </label>
									@if($payment_method->status)
										<select class="form-control cs-select cs-skin-elastic" name="payment_method" id="payment_method" required="">
											<option value=""><label>{{ trans('translate.choose_bank') }}</option>
											@foreach($payment_method->data->VA as $row)
												<option value="{{ $row->gateway }}||{{ $row->productCode }}||{{ $row->productType }}||{{ $row->productName }}||{{ $row->productImageUrl }}">{{ $row->productName }}</option>
											@endforeach
										</select>
									@else
										NOT FOUND, PLEASE REFRESH THIS PAGE
									@endif
								</div>
							</div>
						</div>
						<input type="hidden" id="hide_va_name">
						<input type="hidden" name="id" value="{{ $STELSales->id }}">
						<button id="submit-btn" class="button full button-3d btn-sky">{{ trans('translate.make_an_order') }}</button> <p hidden id="submit-msg">Please Wait ...</p>
					</div> 
				</div>
				</form>
			</div>  
		</section><!-- #content end -->
@endsection
@section('content_js')
<script type="text/javascript">
	$(document).ready(function() {
		$('#payment_method').on('change', function() {
			var res = this.value.split("||");
			$('#hide_va_name').val(res[3]);
		});
		$('#submit-btn').click(function () {
			if(!$("#form-checkout").valid()){
				return false;
			}
			if (!confirm('Are you sure with '+$('#hide_va_name').val()+' payment')) {
			 	return false;
			}
		});
	});
</script>
@endsection