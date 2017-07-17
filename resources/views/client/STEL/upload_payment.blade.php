@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.stel_payment_upload') }} - Telkom DDS</title>
@section('content')
 <!-- Page Title
	============================================= -->
	<section id="page-title">

		<div class="container clearfix">
			<h1>{{ trans('translate.stel_payment_upload') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">STEL</a></li>
					<li><a href="{{url('/payment_status')}}">{{ trans('translate.payment_status') }}</a></li>
					<li class="active">{{ trans('translate.stel_payment_upload') }}</li>
				</ol>
		</div>

	</section><!-- #page-title end -->

	<!-- Content
	============================================= -->
	<section id="content">

		<div class="content-wrap"> 

			<div class="container clearfix"> 
				<form id="form" class="smart-wizard" role="form" method="POST" action="{{ url('/pembayaranstel') }}" enctype="multipart/form-data">
					{!! csrf_field() !!}
					<input type="hidden" name="stelsales_id" id="stelsales_id" value="<?php echo $id ?>"/>  
					<div id="wizard" class="swMain">
						@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
						<div class="form-group">
							<table class="table table-condensed">
								<thead>
									<tr>
										<th colspan="3">{{ trans('translate.examination_upload_payment') }}</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th colspan="3">
										<input class="data-upload-pembayaran" id="data-upload-pembayaran" name="filePembayaran" type="file" accept="application/pdf,image/*" required>
									 	<!-- <input type="hidden" name="hide_file_pembayaran" id="hide_file_pembayaran" value="<?php // echo $data->attachment?>"/> 
									 	<div id="file-pembayaran"><?php // echo $data->attachment ?></div> -->
									 	</th>
									</tr>
									 
							</table>
						</div>
						<div class="row">
							<div class="col-md-6">
								<a class="button button-3d btn-sky nomargin" href="{{url('/payment_status')}}">{{ trans('translate.back') }}</a>
							</div>
							<div class=" pull-right col-md-6">
								<button type="submit" class="button button-3d btn-sky nomargin pull-right" style="margin-bottom:10px;">
									{{ trans('translate.examination_upload_payment_file') }}
								</button>
							</div>
						</div>										
					</div>
				</div>
				</form>
			</div>



			</div>

		</div>

	</section><!-- #content end --> 
@endsection
@section('content_js')
<script type="text/javascript">	
 			 
			$("#file-pembayaran").click(function() {
				var file = $('#hide_file_pembayaran').val();
				downloadFile(file);
			});
			
			function downloadFile(file){
				var path = "{{ URL::asset('media/stel') }}";
				var id_exam = $('#stelsales_id').val();
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
