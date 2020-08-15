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
					<li><a href="#">{{ trans('translate.see_product') }}</a></li>
				<li class="active">{{ trans('translate.stel_payment_detail') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content"> 
			<div class="content-wrap"> 
				<div class="container clearfix"> 
					<form id="form-send-feedback" class="nobottommargin" role="form" method="POST" action="{{ url('doCheckout') }}" onsubmit="javascript:document.getElementById('submit-btn').style.display = 'none';document.getElementById('submit-msg').style.display = 'block';">
					<div class="row">    
						<p> No. Invoice	: {{$invoice_number}} </p> 
						<input type="hidden" name="invoice_number" value="{{$invoice_number}}"><br>
						<input type="hidden" name="final_price" value="{{$final_price}}"><br>
						<div class="row"> 
							<table id="datatable1" class="table table-striped table-bordered" style="width: 100%;">
								<caption></caption>
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">{{ trans('translate.stel_name') }}</th>
 										<th scope="col">{{ trans('translate.stel_code') }}</th>
										<th scope="col">{{ trans('translate.stel_price') }}</th> 
										<th scope="col">{{ trans('translate.stel_qty') }}</th>
										<th scope="col">Total</th> 
									</tr>
								</thead>
								<tbody>
									@php $no = 0;@endphp
									  @foreach(Cart::content() as $row)
									  	@php $no++;@endphp
									<tr>
										<td>{{$no}}</td>
										@php 
											$res = explode('myTokenProduct', $row->name);
											$stel_name = $res[0] ? $res[0] : '-';
											$stel_code = $res[1] ? $res[1] : '-';
										@endphp
										<td>{{$stel_name}}</td>
										<td>{{$stel_code}}</td>
										<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. {{number_format($row->price)}}</td> 
										<td class="center">{{$row->qty}}</td> 
										<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. {{number_format($row->price*$row->qty)}}</td> 
									</tr> 
									@endforeach
								</tbody>
								<tfoot>
									<tr class="list-total-harga">
										<td colspan="5" class="text-align-right">{{ trans('translate.stel_unique_code') }}</td> 
										<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. {{number_format($unique_code)}}</td> 
									</tr> 
									<tr class="list-total-harga">
										<td colspan="5" class="text-align-right">Sub Total</td> 
										<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. {{number_format($total_price + $unique_code)}}</td> 
									</tr> 
									<tr class="list-total-harga">
										<td colspan="5" class="text-align-right">{{ trans('translate.tax') }}</td> 
										<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. {{number_format($tax)}}</td> 
									</tr> 
									<tr class="list-total-harga" style="font-weight: bold">
										<td colspan="5" class="text-align-right">Total</td>
										<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. {{number_format($final_price)}}</td> 
									</tr> 
								</tfoot>
							</table> 
						</div> 
						<div class="alert alert-warning" style="font-weight: bold;">
							{{ trans('translate.payment_alert_1') }}<br>{{ trans('translate.payment_alert_2') }}
						</div> 
						{{ trans('translate.stel_payment_method') }}
						<br>
						<div class="check-layout">
							<div class="col-md-4">
								<input type="radio" name="payment_method" value="atm" checked> {{ trans('translate.stel_payment_method_atm') }}
							</div>
						</div>
					</div>	 
					<div class="row metoda">
						<div class="col-md-12 bank-list-header">This is list of bank if you take this method</div>
						<div class="col-md-3" style="font-weight: bold;font-size: 175%;">
							<img src="http://localhost/telkomdds/public/images/bank/mandiri.png" alt="logo bank mandiri">
							BANK MANDIRI
						</div>
						<div class="col-md-9" style="font-weight: bold;font-size: 240%;margin-top: -3%;">
							<br>
							KCP KAMPUS TELKOM BANDUNG
							<br>
							131-0096022712
							<br>
							a/n Divisi RisTI TELKOM
						</div>
					</div> 	
					<div class="row metodb" style="display: none;"> 
						 	{{ csrf_field() }}
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-process"></div> 
							<div class="col-md-12 form-group"> 
								<input type="text" name="name" id="name" required placeholder="{{ trans('translate.stel_placeholder_cc_name') }}  *" class="sm-form-control required" />
							</div>   
							<div class="col-md-2 form-group"> 
								<input type="text" name="exp" id="exp" placeholder="Exp  *" required class="required sm-form-control col-md-2" />  
							</div>
							<div class="col-md-2 form-group"> 
								<input type="text" name="cvv" id="cvv" placeholder="CVV *" required size="4" class="required sm-form-control" /> 
							</div>
							<div class="col-md-2 form-group"> 
								<input type="text" name="cvc" id="cvc" placeholder="CVC *" required size="4" class="required sm-form-control" /> 
							</div> 
							<div class="col-md-2 form-group"> 
								<input type="text" name="type" id="type" placeholder="Type *" required size="4" class="required sm-form-control" /> 
							</div>  
							<div class="col-md-12 form-group"> 
								<input type="text" name="no_card" id="no_card" placeholder="{{ trans('translate.stel_placeholder_cc_card_number') }} *" required class="required sm-form-control" />
							</div>  
							<div class="col-md-12 form-group"> 
								<input type="text" name="address" id="address" placeholder="{{ trans('translate.stel_placeholder_cc_billing_address') }} *" required class="required sm-form-control" />
							</div> 
							<div class="col-md-12 form-group"> 
								<input type="text" name="no_telp" id="no_telp" placeholder="{{ trans('translate.stel_placeholder_cc_phone') }} *" required class="required sm-form-control" />
							</div> 
							<div class="col-md-12 form-group"> 
								<input type="text" name="email" id="email" placeholder="Email *" required class="required sm-form-control" />
							</div> 
							<div class="col-md-6 form-group"> 
								<input type="text" name="country" id="country" placeholder="{{ trans('translate.stel_placeholder_cc_national') }} *" required class="required sm-form-control" />
							</div>
							<div class="col-md-6 form-group"> 
								<input type="text" name="province" id="province" placeholder="{{ trans('translate.stel_placeholder_cc_province') }} *" required class="required sm-form-control" />
							</div>
							<div class="col-md-6 form-group"> 
								<input type="text" name="city" id="city" placeholder="{{ trans('translate.stel_placeholder_cc_city') }} *" required class="required sm-form-control" />
							</div> 
							<div class="col-md-6 form-group"> 
								<input type="text" name="postal_code" id="postal_code" placeholder="{{ trans('translate.stel_placeholder_cc_postal_code') }} *" required class="required sm-form-control" />
							</div> 
							<div class="col-md-12 form-group"> 
								<input type="text" name="birthdate" id="birthdate" placeholder="{{ trans('translate.stel_placeholder_cc_birthdate') }} *" required class="required sm-form-control" />
							</div>   
						
					</div> 		
					<div class="col_full"><button id="submit-btn" class="button full button-3d btn-sky">OK</button> <p hidden id="submit-msg">Please Wait ...</p></div>
					</form>
				</div>  
			</div> 
		</section><!-- #content end -->
@endsection