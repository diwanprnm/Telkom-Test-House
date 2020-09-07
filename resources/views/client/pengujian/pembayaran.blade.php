@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.examination_payment') }} - Telkom DDB</title>
@section('content')
 		 
		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap"> 
				<div class="container clearfix">
					@if (Session::get('message'))
						<div class="done_sent">
	                       <div class="done">
	                           <i class="fa fa-check-circle" aria-hidden="true"></i>
	                       </div>
	                       <div class="content">
	                           <h3 style="margin-bottom: 1%;">{{ trans('translate.payment_attach_sent') }}</h3>
	                           <p>{{ trans('translate.an_attach_has_been_sent') }}</p>
	                       </div>
	                       <div class="footer">
	                           <a href="{{ url('/pengujian') }}" style="color:#299ec0 !important">{{ trans('translate.done') }}</a>
	                       </div>
	                   </div>
	                @else
						<div class="container-fluid container-fullw bg-white">
							 <div class="col-md-12">
							<div class="panel panel-white" id="panel1">
								<div class="panel-body">
									<div class="col-md-12">
									@if (Session::get('error'))
										<div class="alert alert-error alert-danger">
											{{ Session::get('error') }}
										</div>
									@endif
									
									@if (Session::get('message'))
										<div class="alert alert-info">
											{{ Session::get('message') }}
										</div>
									@endif
									<!-- start: WIZARD FORM -->
									<form id="form" class="smart-wizard" role="form" method="POST" action="{{ url('doCheckoutSPB') }}" onsubmit="javascript:document.getElementById('submit-btn').style.display = 'none';document.getElementById('submit-msg').style.display = 'block';">
										<input type="hidden" name="hide_id_user" id="hide_id_user" value="<?php echo $data->created_by ?>"/>
										<input type="hidden" name="hide_id_exam" id="hide_id_exam" value="<?php echo $data->examination_id ?>"/>
										<input type="hidden" name="hide_id_attach" id="hide_id_attach" value="<?php echo $data->id ?>"/>
										{{ csrf_field() }}
										<div id="wizard" class="swMain">
											<div class="form-group">
												<table class="table table-condensed">
													<thead>
														<tr>
															<th colspan="3">{{ trans('translate.stel_payment_confirmation') }}</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>{{ trans('translate.examination_number_payment') }} : {{ $spb_number }}</td>
														</tr>
														<tr>
															<td>{{ trans('translate.service_application_name') }} : {{ $examinationsData[0]->device->name }}, {{ $examinationsData[0]->device->mark }}, {{ $examinationsData[0]->device->model }}, {{ $examinationsData[0]->device->capacity }}</td>
														</tr>
														<tr>
															<td>{{ trans('translate.stel_total_payment') }} : </td>
														</tr>
														<tr class="with_pph">
															<td>
																<span style="font-weight: bold; font-size:150%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($examinationsData[0]->price, 0, ",", ".") }},-</span>&nbsp;
																<label style="font-size:70%; text-transform: none;">({{ trans('translate.examination_payment_this_nominal') }})</label>
															</td>
														</tr>
														<tr class="is_pph" style="display: none;">
															<td>
																<span style="font-size:100%; color: #fa8231; text-decoration: line-through;	">{{ trans('translate.stel_rupiah') }}. {{ number_format($examinationsData[0]->price, 0, ",", ".") }},-</span>
															</td>
														</tr>
														<tr class="is_pph" style="display: none;">
															<td>
																@php 
																	$pph = floor(($examinationsData[0]->price + $examinationsData[0]->unique_code)*0.02); 
																	$amount = $examinationsData[0]->price - $pph;
																@endphp
																<span style="font-weight: bold; font-size:150%; color: #fa8231;">{{ trans('translate.stel_rupiah') }}. {{ number_format($amount, 0, ",", ".") }},-</span>
																<label style="font-size:70%; text-transform: none;">({{ trans('translate.examination_payment_nominal_without_pph') }})</label>
															</td>
														</tr>
												</table>		
												<div class="check-layout">
													<div class="col-md-4">
														<label style="text-transform: none;"><input type="checkbox" id="is_pph" name="is_pph"> {{ trans('translate.examination_payment_will_pay') }}</label>
													</div>
												</div>
												<span style="font-weight: bold; text-decoration-line: underline; text-underline-position: under;">{{ trans('translate.stel_payment_method') }}</span>
												<div class="check-layout">
													<div class="col-md-4">
														<input type="radio" name="payment_method" value="atm"> {{ trans('translate.stel_payment_method_atm') }}
													</div>
													<div class="col-md-4">
														<input type="radio" name="payment_method" value="va" checked> {{ trans('translate.stel_payment_method_va') }}
													</div>
												</div>
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
					@endif
				</div>


				<input type="hidden" name="spb_date" id="spd_date" value="<?php echo $examinationsData[0]->spb_date;?>">
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
		});

		/* Dengan Rupiah */
		var jml_pembayaran = document.getElementById('jml-pembayaran');
		jml_pembayaran.value = formatRupiah(jml_pembayaran.value, 'Rp. ');
		jml_pembayaran.addEventListener('keyup', function(e)
		{
			jml_pembayaran.value = formatRupiah(this.value, 'Rp. ');
		}); 
		
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

		var spb_date = $("#spd_date").val();

		var year = spb_date.split("-")[0];
		var month = spb_date.split("-")[1];
		var day = spb_date.split("-")[2];

		$('.date').datepicker({
      dateFormat: 'yy-mm-dd', 
      autoclose: true,
      numberOfMonths: 2,
      minDate: new Date(year+'/'+month+'/'+day),
      showButtonPanel: true

  });
			$("#file-pembayaran").click(function() {
				var file = $('#hide_file_pembayaran').val();
				downloadFile(file);
			});
			
			function downloadFile(file){
				var path = "{{ URL::asset('media/examination') }}";
				var id_exam = $('#hide_id_exam').val();
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