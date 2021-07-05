@extends('layouts.app')

@section('content')

@php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
@endphp

<input type="hide" id="hide_stel_sales_detail_id" name="hide_stel_sales_detail_id">
<div class="modal fade" id="myModal_delete_detail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Detail Pembelian STEL Akan Dihapus, Mohon Berikan Keterangan!</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<div class="form-group">
								<label for="keterangan">Keterangan:</label>
								<textarea class="form-control" rows="5" name="keterangan" id="keterangan"></textarea>
							</div>
						</th>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="button" id="btn-modal-delete_detail" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
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
					<h1 class="mainTitle">Detail Referensi Uji</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Data Master</span>
					</li>
					<li>
						<span>Referensi Uji</span>
					</li>
					<li class="active">
						<span>Detail</span>
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

		<div class="container-fluid container-fullw bg-white">
			<div class="row">
				<div class="col-md-12">
					{!! Form::open(array('url' => 'admin/stel/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
						{!! csrf_field() !!}
						<fieldset>
							<legend>
								Edit Referensi Uji
							</legend>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>
											Tipe Referensi Uji *
										</label>
										<select name="type" class="cs-select cs-skin-elastic" required>
											<!-- <option value="" disabled selected>Select...</option> -->
											<option value="1" @if($data->type == '1') selected @endif>STEL</option>
											<option value="2" @if($data->type == '2') selected @endif>S-TSEL</option>
											<option value="3" @if($data->type == '3') selected @endif>PED / STD</option>
											<option value="4" @if($data->type == '4') selected @endif>INTERNAL</option>
											<option value="5" @if($data->type == '5') selected @endif>PERDIRJEN</option>
											<option value="6" @if($data->type == '6') selected @endif>PERMENKOMINFO</option>
											<option value="7" @if($data->type == '7') selected @endif>Lainnya ...</option>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>
											Kode *
										</label>
										<input type="text" id="code" name="code" class="form-control" placeholder="No. Dokumen" value="{{$data->code}}" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>
											Bahasa *
										</label>
										<select name="lang" class="cs-select cs-skin-elastic" required>
											<!-- <option value="" disabled selected>Select...</option> -->
											<option value="IDN" @if($data->lang == 'IDN') selected @endif>IDN</option>
											<option value="ENG" @if($data->lang == 'ENG') selected @endif>ENG</option>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
										Submit
									</button>
									<a style=" color:white !important;" href="{{URL::to('/admin/stel')}}">
										<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
										Cancel
										</button>
									</a>
								</div>
							</div>
						</fieldset>
					{!! Form::close() !!}
				</div>
				<div class="col-md-6 pull-right" style="margin-bottom:10px">
					<a style=" color:white !important;" href="{{URL::to('/admin/stel/create/'.$data->id)}}">
						<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Tambah
						</button>         
					</a>
		        </div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption></caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="left" scope="col">No. Dokumen</th>
									<th class="left" scope="col">Nama</th>
									<th class="left" scope="col">Bahasa</th>
									<th class="left" scope="col">Lab</th>
									<th class="center" scope="col">Status</th>
                                    <th class="center" colspan="2" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@php $no=1; @endphp
								@foreach($data->stels as $item)
									<tr>
										<td class="center">{{$no++}}</td>
										<td class="left">{{ $item->code }}</td>
										<td class="left">{{ $item->name }}</td>
										<td class="left">{{ $item->stelMaster->lang }}</td>
										<td class="left">{{ @$item->examinationLab->name }}</td>
										@if($item->is_active)
	                                    	<td class="center"><span class="label label-sm label-success">Active</span></td>
	                                    @else
	                                    	<td class="center"><span class="label label-sm label-warning">Inactive</span></td>
	                                    @endif
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/stel/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
											</div>
										</td>
										<td class="center">
											<div>
												{!! Form::open(array('url' => 'admin/stel/'.$item->id, 'method' => 'DELETE')) !!}
													{!! csrf_field() !!}
													<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><em class="fa fa-times fa fa-white"></em></button>
												{!! Form::close() !!}
											</div>
										</td>
									</tr>
								@endforeach
                            </tbody>
						</table>
					</div>
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