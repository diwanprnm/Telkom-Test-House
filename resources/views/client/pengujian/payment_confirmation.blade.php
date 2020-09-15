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
					<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%" style="display: none;">
						<thead>
							<tr>
								<th>No</th>
								<th>Item</th>
								<th>Total ({{ trans('translate.stel_rupiah') }}.)</th> 
							</tr>
						</thead>
						<tbody>
							@php $price = ceil(($data[0]->price/1.1)); @endphp
							<tr>
								<td>1. </td>
								<td>{{$data[0]->device->name.', '.$data[0]->device->mark.', '.$data[0]->device->capacity.', '.$data[0]->device->model}}</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($price)}}</td> 
							</tr> 
							@php $tax = floor(0.1*$price); @endphp
							<tr>
								<td></td>
								<td>PPN 10%</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($tax)}}</td> 
							</tr> 
						</tbody>
						<tfoot>
                        	<tr style="font-weight: bold">
								<td> </td>
								<td align="right">{{ trans('translate.examination_payment_total') }}</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($data[0]->price)}}</td> 
							</tr> 
							@if($data[0]->include_pph)
							@php $pph = floor(0.02*$price); @endphp
							<tr style="font-weight: bold">
								<td> </td>
								<td align="right">pph</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($pph)}}</td> 
							</tr> 
							<tr style="font-weight: bold">
								<td> </td>
								<td align="right">{{ trans('translate.examination_payment_total') }} {{ trans('translate.examination_payment_cut') }} pph</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($data[0]->price - $pph)}}</td> 
							</tr> 
							@endif
						</tfoot>
					</table> 
				</div>	
				@php $amount = $data[0]->include_pph ? $data[0]->price - $pph : $data[0]->price @endphp
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
							<img src="http://localhost/telkomdds/public/images/bank/mandiri.png">
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
								<div><p><b>{{ trans('translate.stel_payment_before') }}</b></p><center><p style="font-size:180%;">( {{ $data[0]->VA_expired }} WIB )</p></center></div>
								<div><p>{{ trans('translate.stel_transfer_to_va') }} :</p>
									<img src="{{ $data[0]->VA_image_url }}" style="height:5%;">
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
				<div class="col_full"><a href="{{url('/pengujian')}}" class="button full button-3d btn-sky">{{ trans('translate.done') }}</a> <p hidden id="submit-msg">Please Wait ...</p></div>
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
</script>

@endsection