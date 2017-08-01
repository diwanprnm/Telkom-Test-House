@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.examination_payment') }} - Telkom DDS</title>
@section('content')
 		 
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
								<form id="form" class="smart-wizard" role="form" method="POST" action="{{ url('/pengujian/pembayaran') }}" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<input type="hidden" name="hide_id_user" id="hide_id_user" value="<?php echo $data->created_by ?>"/>
									<input type="hidden" name="hide_id_exam" id="hide_id_exam" value="<?php echo $data->examination_id ?>"/>
									<input type="hidden" name="hide_id_attach" id="hide_id_attach" value="<?php echo $data->id ?>"/>
									{{ csrf_field() }}
									<div id="wizard" class="swMain">
										<div class="form-group">
											<table class="table table-condensed">
												<thead>
													<tr>
														<th colspan="3">{{ trans('translate.examination_upload_payment') }}</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th colspan="3"></th>
													</tr>
													<tr>
														<th colspan="3">{{ trans('translate.examination_file_payment') }}</th>
													</tr>
													<tr>
														<td>
															<input class="data-upload-pembayaran" id="data-upload-pembayaran" name="filePembayaran" type="file" accept="application/pdf,image/*" required>
															<input type="hidden" name="hide_file_pembayaran" id="hide_file_pembayaran" value="<?php echo $data->attachment ?>"/>
															<div id="file-pembayaran"><?php echo $data->attachment ?></div>
														</td>
													</tr>
													<tr>
														<td>{{ trans('translate.examination_number_payment') }} : <input type="text" id="no-pembayaran" class="no-pembayaran" name="no-pembayaran" placeholder="<?php echo $spb_number ?>" value="<?php echo $spb_number ?>" readonly></td>
													</tr>
													<tr>
														<?php 
															if($data->tgl == '' or $data->tgl == '0000-00-00' or $data->tgl == NULL){
																$timestamp = date('d-m-Y');
															}else{
																$timestamp = date('d-m-Y', strtotime($data->tgl));
															}
														?>
														<td>{{ trans('translate.examination_date_payment') }} : <input type="text" id="tgl-pembayaran" class="date tgl-pembayaran" name="tgl-pembayaran" placeholder="Tanggal ..." value="<?php echo $timestamp; ?>" readonly required></td>
													</tr>
													<tr>
														<td>{{ trans('translate.examination_price_payment') }} : <input type="number" id="jml-pembayaran" class="jml-pembayaran" name="jml-pembayaran" placeholder="<?php echo $cust_price_payment ?>" value="<?php echo $cust_price_payment ?>" required></td>
													</tr>
											</table>
										</div>
										<div class="row">
											<div class=" pull-right col-xs-12">
												<a class="btn btn-wide btn-danger col-xs-12 col-lg-1" href="{{url('/pengujian')}}">{{ trans('translate.back') }}</a>
												<button type="submit" class="btn btn-wide btn-danger pull-right col-xs-12 col-lg-1" style="margin-bottom:10px;">
													<i class="fa fa-btn"></i> {{ trans('translate.examination_upload_payment_file') }}
												</button>
											</div>
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


				<input type="hidden" name="spb_date" id="spd_date" value="<?php echo $examinationsData->spb_date;?>">
				</div>

			</div>

		</section><!-- #content end -->
		

@endsection
 
@section('content_js')

 		<script type="text/javascript">	
 	// 		$('.date').datepicker({  
		// 	"format": "dd-mm-yyyy",
		// 	"setDate": new Date(),
		// 	"autoclose": true
		// });		

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