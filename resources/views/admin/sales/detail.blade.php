@extends('layouts.app')

@section('content')

<?php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
?>

<input type="hide" id="hide_stel_sales_detail_id" name="hide_stel_sales_detail_id">
<div class="modal fade" id="myModal_delete_detail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Detail Pembelian STEL Akan Dihapus, Mohon Berikan Keterangan!</h4>
			</div>
			
			<div class="modal-body">
				<table width=100%>
					<tr>
						<td>
							<div class="form-group">
								<label for="keterangan">Keterangan:</label>
								<textarea class="form-control" rows="5" name="keterangan" id="keterangan"></textarea>
							</div>
						</td>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table width=100%>
					<tr>
						<td>
							<button type="button" id="btn-modal-delete_detail" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

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
							<thead>
								<tr>
									<th class="center">No</th> 
									<th class="center">Document Name</th> 
									<th class="center">Document Code</th>  
									<th class="center">Price</th> 
									<th class="center">QTY</th> 
									<th class="center">Total</th>
									<th class="center">Attachment</th>
									@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
										<th class="center">Action</th>
									@endif
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
										<td class="center"><a href="{{ URL::to('/admin/downloadstelwatermark/'.$item->id) }}" target="_blank">{{ $item->attachment }}</a></td>
										@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
										<td class="center">
											<div>
												<a class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Destroy" data-toggle="modal" data-target="#myModal_delete_detail" onclick="document.getElementById('hide_stel_sales_detail_id').value = '{{ $item->id }}'"><i class="fa fa-trash"></i></a>
											</div>
										</td>
										@endif
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
                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-left" style="margin-right: 1%;">Kembali</button>
                    </a>
					@if($id_kuitansi != '')
					<a href="{{ URL::to('/admin/downloadkuitansistel/'.$id_kuitansi) }}" target="_blank">
                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right: 1%;">Lihat Kuitansi</button>
                    </a>
					@endif
					@if($faktur_file != '')
					<a href="{{ URL::to('/admin/downloadfakturstel/'.$id_sales) }}" target="_blank">
                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right: 1%;">Lihat Faktur Pajak</button>
                    </a>
					@endif
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

		$('#myModal_delete_detail').on('shown.bs.modal', function () {
		    $('#keterangan').focus();
		});

		$('#btn-modal-delete_detail').click(function () {
		 	var baseUrl = "{{URL::to('/')}}";
			var keterangan = document.getElementById('keterangan').value;
			var stel_sales_detail_id = document.getElementById('hide_stel_sales_detail_id').value;
			if(keterangan == ''){
				$('#myModal_delete_detail').modal('show');
				return false;
			}else{
				$('#myModal_delete_detail').modal('hide');
				if (confirm('Are you sure want to delete this data?')) {
				    document.getElementById("overlay").style.display="inherit";	
				 	document.location.href = baseUrl+'/admin/sales/'+stel_sales_detail_id+'/'+encodeURIComponent(encodeURIComponent(keterangan))+'/deleteProduct';
				}
			}
		});
	});
</script>
@endsection