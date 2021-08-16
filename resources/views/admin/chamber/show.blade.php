@extends('layouts.app')

@section('content')

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Detail Pembelian STEL</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Keuangan</span>
					</li>
					<li>
						<span>Rekap Pembelian STEL</span>
					</li>
					<li class="active">
						<span>Detail</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

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

		<div class="container-fluid container-fullw bg-white">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Sales document table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Company Name</th> 
									<th class="center" scope="col">Sales Date</th>  
									<th class="center" scope="col">Invoice</th> 
									<th class="center" scope="col">Price</th>
									<th class="center" scope="col">Attachment</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="center">1</td> 
									<td class="center">{{ $data->company_name }}</td>
									<td class="center">{{ $data->start_date }}</td>
									<td class="center">{{ $data->invoice }} </td>
									<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. {{ number_format($data->price, 0, '.', ',') }}</td> 
									<td class="center"><a href="{{ URL::to('/cetakTiketChamber/'.$data->id) }}" target="_blank">{{ 'Download' }}</a></td>
								</tr>
                            </tbody>
                            <tfoot>
								<tr>
                            		<td colspan="4" class="text-align-right"> {{ trans('translate.stel_unique_code') }}</td>
                            		<td class="text-align-right">
										{{ trans('translate.stel_rupiah') }}. {{ number_format(0, 0, '.', ',') }}
									</td>
                            	</tr>
                            	<tr>
                            		<td colspan="4" class="text-align-right"> Sub Total</td>
                            		<td class="text-align-right">
										{{ trans('translate.stel_rupiah') }}. {{ number_format($data->price, 0, '.', ',') }}
									</td>
                            	</tr>
                           		<tr>
                            		<td colspan="4" class="text-align-right"> Tax</td>
									<td class="text-align-right">{{ trans('translate.stel_rupiah') }}. 
										@php
											$tax =  ($data->price) * (config("cart.tax")/100);
										@endphp
										{{ number_format($tax, 0, '.', ',') }}
									</td>
									<td class="center" style="border: 0;">
										@if($id_kuitansi != '')
											@php
												$id_kuitansi = preg_replace('/\\.[^.\\s]{3,4}$/', '', $id_kuitansi);
											@endphp
										<a href="{{ URL::to('/admin/downloadkuitansichamber/'.$id_kuitansi) }}" target="_blank">
											<button type="button" class="btn btn-wide btn-red btn-squared" style="margin-right: 1%; width:9rem;">Lihat Kuitansi</button>
										</a>
										@endif
									</td>
                            	</tr>
                            	<tr style="font-weight: bold;">
                            		<td colspan="4" class="text-align-right">Total</td>
                            		<td class="text-align-right">
										{{ trans('translate.stel_rupiah') }}. {{ number_format($data->total, 0, '.', ',') }}
									</td>
									<td class="center" style="border: 0;">
										@if($faktur_file != '')
										<a href="{{ URL::to('/admin/downloadfakturchamber/'.$id_sales) }}" target="_blank">
											<button type="button" class="btn btn-wide btn-red btn-squared" style="margin-right: 1%; width:9rem;">Lihat Faktur Pajak</button>
										</a>
										@endif
									</td>
                            	</tr>
                            </tfoot>
						</table>
					</div>
				</div>
				<div class="col-md-12">
					<a href="{{URL::to('/admin/chamber')}}">
                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-left" style="margin-right: 1%;">Kembali</button>
                    </a>
                </div>
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
	</div>
</div>
@endsection

@section('content_js')
<style>
	.text-align-right{
		text-align: right;
	}
</style>
<script src={{ asset("vendor/maskedinput/jquery.maskedinput.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js") }}></script>
<script src={{ asset("vendor/autosize/autosize.min.js") }}></script>
<script src={{ asset("vendor/selectFx/classie.js") }}></script>
<script src={{ asset("vendor/selectFx/selectFx.js") }}></script>
<script src={{ asset("vendor/select2/select2.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.js") }}></script>
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
	
</script>
@endsection