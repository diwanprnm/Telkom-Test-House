@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.stel_payment_confirmation') }} - Telkom DDB</title>
@section('content') 
 		<!-- Content
		============================================= -->
		<section id="content"> 
			<div class="container clearfix"> 
				<div class="row">    
					<br>
					<p> {{ trans('translate.examination_number_payment') }}	: {{ $data[0]->spb_number }} <a href="javascript:void(0)" class="collapsible" style="text-decoration: underline !important;">{{ trans('translate.examination_detail') }}</a></p> 
					<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%" style="display: none;" aria-describedby="mydesc">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Item</th>
								<th scope="col" align="right">Total ({{ trans('translate.stel_rupiah') }}.)</th> 
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1. </td>
								<td>{{ trans('translate.rent_chamber_client_label_rent_date') }} : 
									{{$data[0]->start_date}} 
									@if($data[0]->start_date != $data[0]->end_date) 
										{{ trans('translate.rent_chamber_client_label_rent_until') }} {{$data[0]->end_date}} 
									@endif
									{{$data[0]->duration}} {{ trans('translate.chamber_days') }}
								</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($data[0]->price)}}</td> 
							</tr> 
							<tr>
								<td></td>
								<td>PPN 10%</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($data[0]->tax)}}</td> 
							</tr> 
						</tbody>
						<tfoot>
                        	<tr style="font-weight: bold">
								<td> </td>
								<td align="right">{{ trans('translate.examination_payment_total') }}</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($data[0]->total)}}</td> 
							</tr> 
							@if($data[0]->include_pph)
							@php $pph = floor(0.02*$data[0]->price); @endphp
							<tr style="font-weight: bold">
								<td> </td>
								<td align="right">pph</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($pph)}}</td> 
							</tr> 
							<tr style="font-weight: bold">
								<td> </td>
								<td align="right">{{ trans('translate.examination_payment_total') }} {{ trans('translate.examination_payment_cut') }} pph</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($data[0]->total - $pph)}}</td> 
							</tr> 
							@endif
						</tfoot>
					</table> 
				</div>	
				@php $amount = $data[0]->include_pph ? $data[0]->total - $pph : $data[0]->total @endphp
				@if($data[0]->payment_method == 1)
					<div class="row metoda"> 
						<div style="text-align: center">
							<p>{{ trans('translate.stel_total_payment') }} : 
								<span style="font-weight: bold; font-size:250%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($amount, 0, ",", ".") }},-</span>
								<!-- The button used to copy the text -->
								<!-- <button onclick="myFunction()">Copy text</button> -->
							</p>
						</div>
						<div class="alert alert-warning" style="font-weight: bold;">
							{{ trans('translate.payment_alert_1') }}<br>{{ trans('translate.payment_alert_2') }}
						</div> 
						<div class="col-md-3" style="font-weight: bold;font-size: 175%;">
							<img alt="bank-mandiri" src="http://localhost/telkomdds/public/images/bank/mandiri.png">
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
				@else
					<div class="row metoda"> 
						<div>
							<div style="text-align:center;font-weight:bold;font-size: 150%;">Virtual Account </div>
							<div style="text-align:center">
								<div><p><strong>{{ trans('translate.stel_payment_before') }}</strong></p><p style="font-size:180%;">( {{ $data[0]->VA_expired }} WIB )</p></div>
								<div><p>{{ trans('translate.stel_transfer_to_va') }} :</p>
									<img alt="va-image" src="{{ $data[0]->VA_image_url }}" style="height:5%;">
									@if($data[0]->VA_expired < date("Y-m-d H:i:s"))
									<p class="alert alert-warning"><span style="font-size:250%;">{{ $data[0]->VA_number }}</span><br>
										{{ trans('translate.stel_total_expired') }} <a href="{{url('/resend_va_spb/'.$data[0]->id)}}"> {{ trans('translate.here') }} </a> {{ trans('translate.stel_total_resend') }}. 
									</p>
									@else
									<p><span style="font-size:250%;">{{ $data[0]->VA_number }}</span><br></p>
									@endif
								</div>
								<div><p>{{ trans('translate.stel_total_payment') }} :</p>
									<div>
										<span style="font-size:250%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($data[0]->VA_amount, 0, ",", ".") }},- (*)</span>
										<br>
										<p>(*) {{ trans('translate.stel_payment_included_va') }} {{ trans('translate.stel_rupiah') }}. {{ number_format($data[0]->VA_amount - $amount, 0, ",", ".") }},-</p>
									</div>
								</div>
							</div>
						</div>
					</div> 		
				@endif
				<div class="col_full">
					<a href="{{url('/chamber_history')}}" class="button full button-3d btn-sky">{{ trans('translate.done') }}</a> 
					<a href="{{url('/cancel_va_chamber/'.$data[0]->id)}}" class="button full button-3d btn-grey" style="background-color: #dfe6e9; color: #636e72;" onclick="javascript:if (!confirm('Are you sure want to change to another bank?')) {return false;}">{{ trans('translate.choose_another_bank') }}</a>
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
	    var content = $(this).parents().next()[0];
	    if (content.style.display == "") {
	      content.style.display = "none";
	    } else {
	      content.style.display = "";
	    }
	  });
	}

	$('#submit-btn').click(function () {
		if (!confirm('Are you sure with '+$('#hide_va_name').val()+' payment?')) {
		 	return false;
		}
	});
</script>

@endsection