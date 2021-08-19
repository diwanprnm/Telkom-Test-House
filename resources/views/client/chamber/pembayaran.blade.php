@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.chamber_history') }} - Telkom DDB</title>
@section('content')
<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.stel_payment_confirmation') }}</h1>
				
				<ol class="breadcrumb">
					<li>{{ trans('translate.menu_testing') }}</li>
					<li><a href="{{ url('/chamber_history') }}"></a>{{ trans('translate.chamber_history') }}</li>
					<li class="active">{{ trans('translate.examination_payment') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->
		<!-- Content
		============================================= -->
		<section id="content">
			<div class="content-wrap"> 
				<div class="container clearfix">
					<div class="container-fluid container-fullw bg-white">
						 <div class="col-md-12">
						<div class="panel panel-white" id="panel1">
							<div class="panel-body">
								<div class="col-md-12">
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
								<!-- start: WIZARD FORM -->
								<form id="form-checkout" class="nobottommargin" role="form" method="POST" action="{{ url('doCheckoutChamber') }}" onsubmit="javascript:document.getElementById('submit-btn').style.display = 'none';document.getElementById('submit-msg').style.display = 'block';">
									<input type="hidden" name="hide_id" id="hide_id" value="{{ $id }}"/>
									{{ csrf_field() }}
									<div id="wizard" class="swMain">
										<div class="form-group">
											<table class="table table-condensed" aria-describedby="mydesc">
												<thead>
													<tr>
														<th colspan="3" scope="col">{{ trans('translate.spb_date') }} : {{ $data->spb_date }}</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>{{ trans('translate.rent_chamber_client_label_rent_date') }} : 
														{{$data->start_date}} 
                                                        @if($data->end_date != '0000-00-00' && $data->start_date != $data->end_date) 
                                                            & {{$data->end_date}} 
                                                        @endif
														{{$data->duration}} {{ trans('translate.chamber_days') }}
														</td>
													</tr>
													<tr>
														<td>{{ trans('translate.stel_total_payment') }} : </td>
													</tr>
													<tr class="with_pph" style="display: none;">
														<td>
															<span style="font-weight: bold; font-size:150%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($data->total, 0, ",", ".") }},-</span>&nbsp;
															<label style="font-size:70%; text-transform: none;">({{ trans('translate.examination_payment_this_nominal') }})</label>
														</td>
													</tr>
													<tr class="is_pph">
														<td>
															<span style="font-size:100%; color: #fa8231; text-decoration: line-through;	">{{ trans('translate.stel_rupiah') }}. {{ number_format($data->total, 0, ",", ".") }},-</span>
														</td>
													</tr>
													<tr class="is_pph">
														<td>
															@php 
																$pph = 0.02*floor($data->price);
																$amount = floor($data->total - $pph);
															@endphp
															<span style="font-weight: bold; font-size:150%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($amount, 0, ",", ".") }},-</span>
															<label style="font-size:70%; text-transform: none;">({{ trans('translate.examination_payment_nominal_without_pph') }})</label>
														</td>
													</tr>
												</tbody>
												<tfoot>
													<tr>
														<td>
															<label style="text-transform: none;"><input type="checkbox" id="is_pph" name="is_pph" checked=""> {{ trans('translate.examination_payment_will_pay') }}</label>
														</td>
													</tr>
												</tfoot>
											</table>
											<div class="form-group"> 
												<div class="row">
													<div class="col-md-4">
														<label>{{ trans('translate.payment_via_virtual_account') }} : </label>
														@if($payment_method->status)
															<select class="form-control cs-select cs-skin-elastic" name="payment_method" id="payment_method" value="{{ old('payment_method') }}" required="">
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
											<button id="submit-btn" class="button full button-3d btn-sky">{{ trans('translate.stel_payment_confirmation') }}</button> <p hidden id="submit-msg">Please Wait ...</p>
										</div>								
									</div>
								</div>
								</form>
								<!-- end: WIZARD FORM -->
								</div>
							</div>
						</div>
					</div>
				</div>


				</div>

			</div>

		</section><!-- #content end -->
		

@endsection
 
@section('content_js')
 		<script type="text/javascript">	
	
	 	$(document).ready(function() {
		    $('#is_pph').change(function() {
		    	if(this.checked) {
		            $(".is_pph").show();
		            $(".with_pph").hide();
		        }else{
		        	$(".with_pph").show();
		            $(".is_pph").hide();
		        }
		    });

		    $('#payment_method').on('change', function() {
				var res = this.value.split("||");
				$('#hide_va_name').val(res[3]);
			});

		    $('#submit-btn').click(function () {
				if(!$("#form-checkout").valid()){
					return false;
				}
				if (!confirm('Are you sure with '+$('#hide_va_name').val()+' payment?')) {
				 	return false;
				}
			});
		});

		/* Dengan Rupiah */
		/*
		var jml_pembayaran = document.getElementById('jml-pembayaran');
		jml_pembayaran.value = formatRupiah(jml_pembayaran.value, 'Rp. ');
		jml_pembayaran.addEventListener('keyup', function(e)
		{
			jml_pembayaran.value = formatRupiah(this.value, 'Rp. ');
		}); 
		*/
		/* Fungsi */
		function formatRupiah(angka, prefix)
		{
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
				split	= number_string.split(','),
				sisa 	= split[0].length % 3,
				rupiah 	= split[0].substr(0, sisa),
				ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);
				
			if (ribuan) {
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
			
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

			$("#file-pembayaran").click(function() {
				var file = $('#hide_file_pembayaran').val();
				downloadFile(file);
			});
			
			function downloadFile(file){
				var path = "{{ URL::asset('media/examination') }}";
				var id_exam = $('#hide_id').val();
				//Get file name from url.
				var url = path+'/'+id_exam+'/'+file;
				var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
				var xhr = new XMLHttpRequest();
				xhr.responseType = 'blob';
				xhr.onload = function() {
					if (this.status === 404) {
					   // not found, add some error handling
					   alert("File Tidak Ada!");
					   return false;
					}
					var a = document.createElement('a');
					a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
					a.download = filename; // Set the file name.
					a.style.display = 'none';
					document.body.appendChild(a);
					a.click();
					delete a;
				};
				xhr.open('GET', url);
				xhr.send();
			}
		</script>
@endsection