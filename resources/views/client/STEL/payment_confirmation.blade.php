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
					<p> No. Invoice	: {{ $data[0]->invoice }} <a href="javascript:void(0)" class="collapsible" style="text-decoration: underline !important;">{{ trans('translate.examination_detail') }}</a></p> 
					<table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%" style="display: none;" aria-describedby="mydesc">
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
							@php $no = 0; $total = 0;@endphp
							  @foreach($data[0]->sales_detail as $row)
							  	@php $no++;@endphp
							<tr>
								<td>{{$no}}</td>
								<td>{{$row->stel->name}}</td>
								<td>{{$row->stel->code}}</td>
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($row->stel->price)}}</td> 
								<td align="center">{{$row->qty}}</td> 
								<td align="right">{{ trans('translate.stel_rupiah') }}. {{number_format($row->stel->price*$row->qty)}}</td> 
							</tr> 
								@php $total +=($row->stel->price * $row->qty); @endphp
							@endforeach
						</tbody>
						<tfoot>
                       		<tr>
                        		<td colspan="5" align="right"> {{ trans('translate.tax') }}</td>
                        		<td align="right">{{ trans('translate.stel_rupiah') }}. @php $tax =  ($total) * (config("cart.tax")/100);
                        			echo number_format($tax, 0, '.', ','); @endphp</td>
                        	</tr>
                        	<tr style="font-weight: bold;">
                        		<td colspan="5" align="right"> Total</td>
                        		<td align="right">{{ trans('translate.stel_rupiah') }}. @php echo number_format($data[0]->total, 0, '.', ','); @endphp</td>
                        	</tr> 
						</tfoot>
					</table> 
				</div>	 
				@if($data[0]->payment_method == 1)
					<div class="row metoda"> 
						<div style="text-align: center">
							<p>{{ trans('translate.stel_total_payment') }} : 
								<span style="font-weight: bold; font-size:250%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($data[0]->total, 0, ",", ".") }},-</span>
								<!-- The button used to copy the text -->
								<!-- <button onclick="myFunction()">Copy text</button> -->
							</p>
						</div>
						<div class="alert alert-warning" style="font-weight: bold;">
							{{ trans('translate.payment_alert_1') }}<br>{{ trans('translate.payment_alert_2') }}
						</div> 
						<div class="col-md-3" style="font-weight: bold;font-size: 175%;">
							<img src="http://localhost/telkomdds/public/images/bank/mandiri.png" alt="mandiri">
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
								<div><p><strong>{{ trans('translate.stel_payment_before') }}</strong></p>
									<p style="font-size:180%;">( {{ $data[0]->VA_expired }} WIB )</p></div>
								<div><p>{{ trans('translate.stel_transfer_to_va') }} :</p>
									<img alt="va-image" src="{{ $data[0]->VA_image_url }}" style="height:5%;">
									@if($data[0]->VA_expired < date("Y-m-d H:i:s"))
									<p class="alert alert-warning"><span style="font-size:250%;">{{ $data[0]->VA_number }}</span><br>
										{{ trans('translate.stel_total_expired') }} <a href="{{url('/resend_va/'.$data[0]->id)}}"> {{ trans('translate.here') }} </a> {{ trans('translate.stel_total_resend') }}. 
									</p>
									@else
									<p><span style="font-size:250%;">{{ $data[0]->VA_number }}</span><br></p>
									@endif
								</div>
								<div><p>{{ trans('translate.stel_total_payment') }} :</p>
									<div>
										<span style="font-size:250%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($data[0]->VA_amount, 0, ",", ".") }},- (*)</span>
										<br>
										<p>(*) {{ trans('translate.stel_payment_included_va') }} {{ trans('translate.stel_rupiah') }}. {{ number_format($data[0]->VA_amount - $data[0]->total, 0, ",", ".") }},-</p>
									</div>
								</div>
							</div>
<!-- 
							<div style="margin:20px;border:2px solid #ff3333">
								<div style="background-color:#ff3333;color:#fff;padding:10px;margin-top:0;text-align:center"><b>Panduan Pembayaran</b></div>
								<div style="padding:10px 20px">
									<h3>ATM MANDIRI</h3>
										<ol>
											<li>Masukkan kartu ATM Mandiri dan PIN Anda,</li>
											<li>Pada ATM Mandiri, pilih menu <b>Bayar/Beli</b>><b>Lainnya</b>><b>Lainnya,</b></li>
											<li>Pilih <b>Multi Payment,</b></li>
											<li>Masukkan <b>kode perusahaan (hanya jika diminta) 89208</b>lalu tekan <b>Benar,</b></li>
											<li>Masukkan 16 digit <b>Nomor Virtual Account,</b></li>
											<li>Pada layar konfirmasi,pastikan tagihan Anda sudah sesuai,</li>
											<li>Jika sudah sesuai,tekan 1,lanjutkan dengan menekan <b>Ya,</b></li>
											<li>Transaksi Selesai</li>
										</ol>
									<h3>INTERNET BANKING</h3>
										<ol>
											<li>Masuk ke situs <u><a href="https://ib.bankmandiri.co.id" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://ib.bankmandiri.co.id&source=gmail&ust=1597396233253000&usg=AFQjCNH-Cd_MN_caaHy1ii723aXbK9Rwww">https: //ib.bankmandiri.co.id</a>,</u></li>
											<li>Lakukan <b>log in</b> dengan akun <b>Mandiri Internet Banking</b> Anda,</li>
											<li>Klik menu <b>Bayar</b> > <b>Multi Payment,</b></li>
											<li>Pada kolom <b>penyedia jasa</b> pilih <b>Espay,</b></li>
											<li>Masukan 16 digit <b>Nomor Virtual Account</b> pada kolom <b>Kode Bayar,</b></li>
											<li>Pada layar konfirmasi, pastikan tagihan Anda sudah sesuai.,</li>
											<li>Jika sudah sesuai, <b>checklist kotak tagihan</b> dan klik <b>Lanjutkan,</b></li>
											<li>Transaksi Selesai.</li>
										</ol>
									<h3>MOBILE BANKING</h3>
										<ol> 
											<li>Buka Aplikasi <b>Mandiri Mobile Banking,</b></li>
											<li>Lakukan <b>log in</b> dengan akun Mandiri Mobile Banking,</li>
											<li>Pilih menu <b>Bayar</b> > <b>Lainnya,</b></li>
											<li>Pilih penyedia layanan <b>Espay,</b></li>
											<li>Masukkan 16 digit <b>Nomor Virtual Account,</b></li>
											<li>Pada layar konfirmasi, pastikan tagihan Anda sudah sesuai,</li>
											<li>Jika sudah sesuai, masukkan <b>OTP</b> dan <b>Pin SMS Banking,</b> lalu klik <b>OK,</b></li>
											<li>Transaksi Selesai.</li>
										</ol>
									<h3>TELLER</h3> 
										<ol> 
											<li>Isi slip bukti setoran/transfer kliring,</li>
											<li>Nasabah menyerahkan uang kepada teller Mandiri,</li>
											<li>Teller Mandiri melakukan pembukuan transaksi Mandiri VA,</li>
											<li>Nasabah akan mendapat slip setoran yang telah terdapat tapak validasi sebagai bukti transaksi</li>
										</ol>
								</div>
							</div> -->
						</div>
					</div> 		
				@endif
				<div class="col_full">
					<a href="{{url('/purchase_history')}}" class="button full button-3d btn-sky">{{ trans('translate.done') }}</a> 
					<a href="{{url('/cancel_va/'.$data[0]->id)}}" class="button full button-3d btn-grey" style="background-color: #dfe6e9; color: #636e72;" onclick="javascript:if (!confirm('Are you sure want to change to another bank?')) {return false;}">{{ trans('translate.choose_another_bank') }}</a>
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
</script>

@endsection