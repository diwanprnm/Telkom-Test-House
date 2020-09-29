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
					<li><a href="{{url('/purchase_history')}}">{{ trans('translate.payment_status') }}</a></li>
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
					<input type="hidden" name="stelsales_id" id="stelsales_id" value="@php echo $id @endphp"/>  
					<div id="wizard" class="swMain">
						@if(Session::has('message'))
							<div class="alert alert-info">{{ Session::get('message') }}</div>
						@endif
						<div class="form-group">
							<table class="table table-condensed">
								<caption></caption>
								<thead>
									<tr>
										<th colspan="3" scope="col">{{ trans('translate.examination_upload_payment') }}</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th colspan="3" scope="col">
										<input class="data-upload-pembayaran" id="data-upload-pembayaran" name="filePembayaran" type="file" accept="application/pdf,image/*" required> 
									 	</th>
									</tr>
									 <tr>
										<th colspan="3" scope="col">{{ trans('translate.examination_price_payment') }} : 
										<input type="text" id="jml-pembayaran" class="jml-pembayaran" name="jml-pembayaran" placeholder="0" value="{{ $data->total }}" required></th>
									</tr>
							</table>
						</div>
						<div class="row">
							<div class="col-md-6">
								<a class="button button-3d btn-sky nomargin" href="{{url('/purchase_history')}}">{{ trans('translate.back') }}</a>
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
