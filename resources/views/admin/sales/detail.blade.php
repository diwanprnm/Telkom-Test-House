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
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center">No</th> 
									<th class="center">Document Name</th> 
									<th class="center">Document Code</th>  
									<th class="center">Price</th> 
									<th class="center">QTY</th> 
									<th class="center">Total</th>
								</tr>
							</thead>
							<tbody> 
							<?php $total = 0;?>
								@foreach($data as $keys => $item)
									<tr>
										<td class="center">{{++$keys}}</td> 
										<td class="center">{{ $item->name }}</td>
										<td class="center">{{ $item->code }}</td>
										<td class="center">{{ trans('translate.stel_rupiah') }}. <?php echo number_format($item->price, 0, '.', ','); ?></td>
										<td class="center"><?php echo $item->qty; ?></td>
										<td align="right">{{ trans('translate.stel_rupiah') }}. <?php echo number_format($item->price * $item->qty, 0, '.', ','); ?></td> 
									</tr> 
									<?php $total +=($item->price * $item->qty); ?>
								@endforeach
                            </tbody>
                            <tfoot>
                            	<tr>
                            		<td colspan="5" align="right"> Total</td>
                            		<td align="right">{{ trans('translate.stel_rupiah') }}. <?php 
                            			echo	number_format($total, 0, '.', ',');?></td>
                            	</tr>
                           		<tr>
                            		<td colspan="5" align="right"> Tax</td>
                            		<td align="right">{{ trans('translate.stel_rupiah') }}. <?php $tax =  $total * (config("cart.tax")/100);
                            			echo	number_format($tax, 0, '.', ',');?></td>
                            	</tr>
                            	<tr>
                            		<td colspan="5" align="right"> Total</td>
                            		<td align="right">{{ trans('translate.stel_rupiah') }}. <?php echo number_format($total+$tax, 0, '.', ',');?></td>
                            	</tr>
                            </tfoot>
						</table>
					</div>
				</div>
				<div class="col-md-12">
					<a href="{{URL::to('/admin/sales')}}">
                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right: 1%;">Kembali</button>
                    </a>
                </div>
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
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
@endsection