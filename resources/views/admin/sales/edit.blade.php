@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Update Status Sales</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Sales</span>
					</li>
					<li class="active">
						<span>Update Status</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->

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

		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/sales/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
					<fieldset>
						<legend>
							Update Status Sales
						</legend>
						<div class="row"> 
							 <div class="col-md-8">
								<div class="form-group">
									 
									@if($data->attachment != '')
										<a href="{{ URL::to('/admin/downloadbukti/'.$data->id) }}" target="_blank">Lihat Bukti Pembayaran</a>
									@else
										Belum ada Bukti Pembayaran
									@endif
								</div>
							</div>
							<!-- <div class="col-md-6">
								@if($data->id_kuitansi != '')
									-
								@else
									<a onclick="checkKuitansi('<?php echo $data->id ?>')"> Cek Kuitansi</a>
								@endif
								<div class="form-group">
									<a onclick="makeKuitansi('<?php echo $data->id ?>')"> Buatkan File Kuitansi</a>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									@if($data->faktur_file != '')
										-
									@else
										<a onclick="checkTaxInvoice('<?php echo $data->id ?>')"> Cek Faktur Pajak</a>
									@endif
								</div>
							</div> -->
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kuitansi File *
									</label>
									<input type="file" name="kuitansi_file" id="kuitansi_file" class="form-control" accept="application/pdf, image/*">
								</div>
								<div class="form-group">
									@if($data->id_kuitansi != '')
										<a href="{{ URL::to('/admin/downloadkuitansistel/'.$data->id_kuitansi) }}" target="_blank">
					                    	{{ $data->id_kuitansi }}
					                    </a>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Faktur Pajak File *
									</label>
									<input type="file" name="faktur_file" id="faktur_file" class="form-control" accept="application/pdf">
								</div>
								<div class="form-group">
									@if($data->faktur_file != '')
										<a href="{{ URL::to('/admin/downloadfakturstel/'.$data->id) }}" target="_blank">
					                    	{{ $data->faktur_file }}
					                    </a>
									@endif
								</div>
							</div>
	                        <!-- <div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="payment_status" class="cs-select cs-skin-elastic" required> 
										@if($dataStel[0]->payment_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Success</option>
											<option value="-1">Decline</option>
										@elseif($dataStel[0]->payment_status == -1)
											<option value="0">Choose Status</option>
											<option value="1">Success</option>
											<option value="-1" selected>Decline</option>
										@else
											<option value="{{ $dataStel[0]->payment_status }}" selected>Choose Status</option>
											<option value="1">Success</option>
											<option value="-1">Decline</option>
										@endif
									</select>
								</div>
							</div>
	                       
							
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                            <a style=" color:white !important;" href="{{URL::to('/admin/sales')}}">
									<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
									Cancel
									</button>
								</a>
	                        </div> -->
	                        <div class="col-md-12">
	                            <a style=" color:white !important;" href="{{URL::to('/admin/sales')}}">
									<button type="button" class="btn btn-wide btn-red btn-squared pull-left">
									Kembali
									</button>
								</a>
	                        </div>
						</div>
					</fieldset>
				{!! Form::close() !!}
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
	</div>
</div>
@endsection

@section('content_js')
<script src={{ asset("vendor/maskedinput/jquery.maskedinput.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js") }}></script>
<script src={{ asset("vendor/autosize/autosize.min.js") }}></script>
<script src={{ asset("vendor/selectFx/classie.js") }}></script>
<script src={{ asset("vendor/selectFx/selectFx.js") }}></script>
<script src={{ asset("vendor/select2/select2.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.js") }}></script>
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
<script type="text/javascript">
	$('#txt-price').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
	
	$('#txt-total').priceFormat({
		prefix: '',
		clearPrefix: true,
		centsLimit: 0
	}); 
	
	function makeKuitansi(a){
		var APP_URL = {!! json_encode(url('/admin/kuitansi/create')) !!};		
		$.ajax({
			type: "POST",
			url : "generateKuitansiParamSTEL",
			data: {'_token':"{{ csrf_token() }}", 'exam_id':a},
			beforeSend: function(){
				
			},
			success: function(response){
				if(response == 1){
					window.open(APP_URL, 'mywin','status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0,scrollbars=0,width=720,height=500');
				}else{
					alert("Gagal mengambil data");
				}
			},
			error:function(){
				alert("Gagal mengambil data");
			}
		});
		
		/* $("#1").load("../loadDataKet",{pgw_id6:res[3]}, function() {
			document.getElementById("overlay").style.display="none";
		}); */
	}

	function checkKuitansi(a){
		$.ajax({
			type: "POST",
			url : "generateKuitansi",
			data: {'_token':"{{ csrf_token() }}", 'id':a},
			beforeSend: function(){
				document.getElementById("overlay").style.display="inherit";
			},
			success: function(response){
				console.log(response);
				if(response){
					alert(response);
					if(response == "Kuitansi Berhasil Disimpan."){location.reload();}
				}else{
					alert("Gagal mengambil data (s)");
				}
				document.getElementById("overlay").style.display="none";
			},
			error:function(response){
				console.log(response);
				alert("Gagal mengambil data (e)");
				document.getElementById("overlay").style.display="none";
			}
		});
		
		/* $("#1").load("../loadDataKet",{pgw_id6:res[3]}, function() {
			document.getElementById("overlay").style.display="none";
		}); */
	}

	function checkTaxInvoice(a){
		$.ajax({
			type: "POST",
			url : "generateTaxInvoice",
			data: {'_token':"{{ csrf_token() }}", 'id':a},
			beforeSend: function(){
				document.getElementById("overlay").style.display="inherit";
			},
			success: function(response){
				console.log(response);
				if(response){
					alert(response);
					if(response == "Faktur Pajak Berhasil Disimpan."){location.reload();}
				}else{
					alert("Gagal mengambil data (s)");
				}
				document.getElementById("overlay").style.display="none";
			},
			error:function(response){
				console.log(response);
				alert("Gagal mengambil data (e)");
				document.getElementById("overlay").style.display="none";
			}
		});
		
		/* $("#1").load("../loadDataKet",{pgw_id6:res[3]}, function() {
			document.getElementById("overlay").style.display="none";
		}); */
	}
</script>
@endsection