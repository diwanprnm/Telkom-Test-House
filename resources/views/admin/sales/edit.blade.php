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
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
										<thead>
											<tr>
												<th class="center">No</th> 
												<th class="center">Document Name</th> 
												<th class="center">Document Code</th> 
												<th class="center">Attachment</th> 
												<th class="center">Upload File</th> 
											</tr>
										</thead>
										<tbody>
											@foreach($dataStel as $keys => $item)
												<tr>
													<td class="center">{{++$keys}}</td> 
													<td class="center">{{ $item->name }}</td>
													<td class="center">{{ $item->code }}</td>
													<td class="center"><a href="{{ URL::to('/admin/downloadstelwatermark/'.$item->id) }}" target="_blank">{{ $item->attachment }}</a></td>
													<td class="center">
														@if($item->stelAttach !='')
															<a href="{!! url("cetakstel?invoice_id={$item->invoice}&attach={$item->stelAttach}&company_name={$item->company_name}") !!}" target="_blank"> Generate Watermark</a>
														@endif
														<input type="file" name="stel_file[]" class="form-control" accept="application/pdf">
														<input type="hidden" name="stels_sales_detail_id[]" value="{{ $item->id }}">
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
									<div>
										<button class="btn btn-wide btn-green btn-squared pull-right">
											Upload
										</button>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<a onclick="makeKuitansi('<?php echo $data->id ?>')"> Buatkan File Kuitansi</a>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kuitansi File *
									</label>
									<input type="file" name="kuitansi_file" id="kuitansi_file" class="form-control" accept="application/pdf, image/*">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Faktur Pajak File *
									</label>
									<input type="file" name="faktur_file" id="faktur_file" class="form-control" accept="application/pdf">
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="payment_status" class="cs-select cs-skin-elastic" required> 
										@if($dataStel[0]->payment_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Success</option>
											<option value="-1">Decline</option>
										@elseif($dataStel[0]->payment_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Success</option>
											<option value="-1">Decline</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Success</option>
											<option value="-1" selected>Decline</option>
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
</script>
@endsection