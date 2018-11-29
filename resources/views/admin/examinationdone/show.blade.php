@extends('layouts.app')

@section('content')

<?php
	$currentUser = Auth::user();
	$is_super = $currentUser['id'];
?>

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
						Data Perusahaan
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
											@elseif($data->function_status == '1')
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
											@if($data->function_status == '1' && $data->contract_status != '1')
												<a href="#step-2" class="done wait">
											@elseif($data->contract_status == '1')
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
											@if($data->contract_status == '1' && $data->spb_status != '1')
												<a href="#step-2" class="done wait">
											@elseif($data->spb_status == '1')
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
											@if($data->spb_status == '1' && $data->payment_status != '1')
												<a href="#step-2" class="done wait">
											@elseif($data->payment_status == '1')
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
											@if($data->payment_status == '1' && $data->spk_status != '1')
												<a href="#step-2" class="done wait">
											@elseif($data->spk_status == '1')
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
											@if($data->spk_status == '1' && $data->examination_status != '1')
												<a href="#step-2" class="done wait">
											@elseif($data->examination_status == '1')
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
											@if($data->examination_status == '1' && $data->resume_status != '1')
												<a href="#step-2" class="done wait">
											@elseif($data->resume_status == '1')
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
										@if($data->examination_type_id !='2' && $data->examination_type_id !='3')
											<li>
												@if($data->resume_status == '1' && $data->qa_status != '1')
													<a href="#step-2" class="done wait">
												@elseif($data->qa_status == '1')
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
												@if($data->qa_status == '1' && $data->certificate_status != '1')
													<a href="#step-2" class="done wait">
												@elseif($data->certificate_status == '1')
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
								<table class="table table-condensed">
									<thead>
										<tr>
											<td class="center">Tahap</td>
											<td class="center">Status</td>
											<td class="center">Keterangan</td>
											<td class="center">Tanggal</td>
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
					<a href="{{URL::to('/admin/examinationdone')}}">
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
<script src={{ asset("vendor/maskedinput/jquery.maskedinput.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js") }}></script>
<script src={{ asset("vendor/autosize/autosize.min.js") }}></script>
<script src={{ asset("vendor/selectFx/classie.js") }}></script>
<script src={{ asset("vendor/selectFx/selectFx.js") }}></script>
<script src={{ asset("vendor/select2/select2.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.js") }}></script>
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
@endsection