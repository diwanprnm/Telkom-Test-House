@extends('layouts.app')

@section('content')

<?php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
?>

<input type="hide" id="hide_exam_id" name="hide_exam_id">
<div class="modal fade" id="myModal_delete" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Data Pengujian Akan Dihapus, Mohon Berikan Keterangan!</h4>
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
							<button type="button" id="btn-modal-delete" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
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
					<h1 class="mainTitle">Detail Pengujian</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Pengujian</span>
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
			<div class="col-md-12">				
				<fieldset>
					<legend>
						Data Pemohon
					</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Nama Pemohon *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->user->name }}" placeholder="Nama Pemohon" disabled>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Alamat *
								</label>
								<textarea class="form-control" disabled>{{ $data->company->address }}</textarea>
							</div>
						</div>
                        <div class="col-md-6">
							<div class="form-group">
								<label>
									Telepon *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->company->phone_number }}" placeholder="Telepon" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Email *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->user->email }}" placeholder="Email" disabled>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						Data Perusahaan [{{ $data->jns_perusahaan }}]
					</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Nama Perusahaan *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->company->name }}" placeholder="Nama Pemohon" disabled>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Alamat *
								</label>
								<textarea class="form-control" disabled>{{ $data->company->address }}</textarea>
							</div>
						</div>
						@if($data->examination_type_id == 2)
                        <div class="col-md-6">
							<div class="form-group">
								<label>
									PLG_ID *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->company->plg_id }}" placeholder="PLG_ID" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									NIB *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->company->nib }}" placeholder="NIB" disabled>
							</div>
						</div>
						@endif
                        <div class="col-md-6">
							<div class="form-group">
								<label>
									Telepon *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->company->phone_number }}" placeholder="Telepon" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Faksimile *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->company->fax }}" placeholder="Faksimile" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Email *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->company->email }}" placeholder="Email" disabled>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						Data Perangkat
					</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>
									Perangkat *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->device->name }}" placeholder="Perangkat" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Merek *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->device->mark }}" placeholder="Merek" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Kapasitas *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->device->capacity }}" placeholder="Merek" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Negara Pembuat *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->device->manufactured_by }}" placeholder="Merek" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Model / Tipe *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->device->model }}" placeholder="Merek" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>
									Referensi Uji *
								</label>
								<input type="text" name="name" class="form-control" value="{{ $data->device->test_reference }}" placeholder="Merek" disabled>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						Dokumen Pendukung
					</legend>
					<div class="row">
						@if($data->attachment != '')
							<div class="col-md-12">
								<div class="form-group">
									<a href="{{URL::to('/admin/examination/download/'.$data->id)}}">Download Form Uji</a>
								</div>
							</div>
						@endif

						@foreach($data->media as $item)
							@if($item->attachment != '')
								<div class="col-md-12">
									<div class="form-group">
										<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name)}}">Download {{ $item->name }}</a>
									</div>
								</div>
							@endif
						@endforeach

						@if($data->device->certificate != '')
							<div class="col-md-12">
								<div class="form-group">
									<a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}">Download Sertifikat</a>
								</div>
							</div>
						@endif
					</div>
				</fieldset>
				<div class="table-responsive">
					<div class="panel panel-default">
						<fieldset>
							<legend>
								Status Pengujian
							</legend>
							<div class="panel-body">
								<div id="wizard" class="swMain">
									<!-- start: WIZARD SEPS -->
									<ul>
										<li>
											@if($data->registration_status == '1')
												<a href="#step-1" class="done">
											@else
												<a href="#step-1" class="done wait">
											@endif
												<div class="stepNumber">
													1
												</div>
												<span class="stepDesc"><small> Registrasi </small></span>
											</a>
										</li>
										<li>
											@if($data->registration_status == '1' && $data->function_status != '1')
												<a href="#step-2" class="done wait">
											@elseif($data->registration_status == '1' && 
											$data->function_status == '1')
												<a href="#step-2" class="done">
											@else
												<a href="#step-2">
											@endif
												<div class="stepNumber">
													2
												</div>
												<span class="stepDesc"><small> Uji Fungsi </small></span>
											</a>
										</li>
										<li>
											@if($data->registration_status == '1' && 
											$data->function_status == '1' && $data->contract_status != '1')
												<a href="#step-3" class="done wait">
											@elseif($data->registration_status == '1' && $data->function_status == '1' &&
											$data->contract_status == '1')
												<a href="#step-3" class="done">
											@else
												<a href="#step-3">
											@endif
												<div class="stepNumber">
													3
												</div>
												<span class="stepDesc"><small> Tinjauan Kontrak </small></span>
											</a>
										</li>
										<li>
											@if($data->registration_status == '1' && $data->function_status == '1' && 
											$data->contract_status == '1' && $data->spb_status != '1')
												<a href="#step-4" class="done wait">
											@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && 
											$data->spb_status == '1')
												<a href="#step-4" class="done">
											@else
												<a href="#step-4">
											@endif
												<div class="stepNumber">
													4
												</div>
												<span class="stepDesc"><small> SPB </small></span>
											</a>
										</li>
										<li>
											@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && 
											$data->spb_status == '1' && $data->payment_status != '1')
												<a href="#step-5" class="done wait">
											@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && 
											$data->payment_status == '1')
												<a href="#step-5" class="done">
											@else
												<a href="#step-5">
											@endif
												<div class="stepNumber">
													5
												</div>
												<span class="stepDesc"><small> Pembayaran </small></span>
											</a>
										</li>
										<li>
											@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && 
											$data->payment_status == '1' && $data->spk_status != '1')
												<a href="#step-6" class="done wait">
											@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && 
											$data->spk_status == '1')
												<a href="#step-6" class="done">
											@else
												<a href="#step-6">
											@endif
												<div class="stepNumber">
													6
												</div>
												<span class="stepDesc"><small> Pembuatan SPK </small></span>
											</a>
										</li>
										<li>
											@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && 
											$data->spk_status == '1' && $data->examination_status != '1')
												<a href="#step-7" class="done wait">
											@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && 
											$data->examination_status == '1')
												<a href="#step-7" class="done">
											@else
												<a href="#step-7">
											@endif
												<div class="stepNumber">
													7
												</div>
												<span class="stepDesc"><small> Pelaksanaan Uji </small></span>
											</a>
										</li>
										<li>
											@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && 
											$data->examination_status == '1' && $data->resume_status != '1')
												<a href="#step-8" class="done wait">
											@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && 
											$data->resume_status == '1')
												<a href="#step-8" class="done">
											@else
												<a href="#step-8">
											@endif
												<div class="stepNumber">
													8
												</div>
												<span class="stepDesc"><small> Laporan Uji </small></span>
											</a>
										</li>
										@if($data->examination_type_id !='2' && $data->examination_type_id !='3' && $data->examination_type_id !='4')
											<li>
												@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && 
												$data->resume_status == '1' && $data->qa_status != '1')
													<a href="#step-9" class="done wait">
												@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && $data->resume_status == '1' && 
												$data->qa_status == '1')
													<a href="#step-9" class="done">
												@else
													<a href="#step-9">
												@endif
													<div class="stepNumber">
														9
													</div>
													<span class="stepDesc"><small> Sidang QA </small></span>
												</a>
											</li>
										
											<li>
												@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && $data->resume_status == '1' && 
												$data->qa_status == '1' && $data->certificate_status != '1')
													<a href="#step-10" class="done wait">
												@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && $data->resume_status == '1' && $data->qa_status == '1' &&
												$data->certificate_status == '1')
													<a href="#step-10" class="done">
												@else
													<a href="#step-10">
												@endif
													<div class="stepNumber">
														10
													</div>
													<span class="stepDesc"><small> Penerbitan Sertifikat </small></span>
												</a>
											</li>
										@endif
									</ul>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<fieldset>
					<legend>
						Riwayat Pengujian
					</legend>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-condensed"><caption></caption>
									<thead>
										<tr>
											<th class="center" scope="col">Tahap</th>
											<th class="center" scope="col">Status</th>
											<th class="center" scope="col">Keterangan</th>
											<th class="center" scope="col">Tanggal</th>
										</tr>
									</thead>
									<tbody>
									@foreach($exam_history as $item)
										<tr>
											<td class="center">{{ $item->tahap }}</td>
											@if($item->status == 1)
												<td class="center">Completed</td>
											@else
												<td class="center">Not Completed</td>
											@endif
											<td>{{ $item->keterangan }} oleh {{ $item->user->name }}</td>
											<td class="center">{{ $item->date_action }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</fieldset>
				<div class="col-md-12">
					<a href="{{URL::to('/admin/examination')}}">
                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-left" style="margin-right: 1%;">Kembali</button>
                    </a>
					<a href="{{URL::to('admin/examination/revisi/'.$data->id)}}">
                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-left">Edit</button>
                    </a>
					@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
						<a class="btn btn-wide btn-red btn-squared pull-right" style="margin-left:10px" data-toggle="modal" data-target="#myModal_delete" onclick="document.getElementById('hide_exam_id').value = '{{ $data->id }}'">Delete</a>
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
<script type="text/javascript">
jQuery(document).ready(function() {
	$('#myModal_delete').on('shown.bs.modal', function () {
		    $('#keterangan').focus();
		})
	});

	$('#btn-modal-delete').click(function () {
	 	var baseUrl = "{{URL::to('/')}}";
		var keterangan = document.getElementById('keterangan').value;
		var exam_id = document.getElementById('hide_exam_id').value;
		if(keterangan == ''){
			$('#myModal_delete').modal('show');
			return false;
		}else{
			$('#myModal_delete').modal('hide');
			if (confirm('Are you sure want to delete ? SPK Data in OTR will be deleted too.')) {
			    document.getElementById("overlay").style.display="inherit";	
			 	document.location.href = baseUrl+'/admin/examination/harddelete/'+exam_id+'/Pengujian/'+encodeURIComponent(encodeURIComponent(keterangan));   
			}
		}
	});
</script>
@endsection