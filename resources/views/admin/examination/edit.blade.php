@extends('layouts.app')

@section('content')

<?php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
?>

<input type="hide" id="hide_exam_id" name="hide_exam_id">
<div class="modal fade" id="myModal_reset_uf" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Uji Fungsi Akan Direset, Mohon Berikan Keterangan!</h4>
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
							<button type="button" id="btn-modal-reset_uf" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<form id="form-tanggal-kontrak" role="form">
{!! csrf_field() !!}
<input type="hidden" name="hide_id_exam" id="hide_id_exam"/>
<div class="modal fade" id="contract-modal-content" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Silakan Isi Data-data Berikut !</h4>
			</div>
			
			<div class="modal-body">
				<table width=100%>
					<tr>
						<td>
							<div class="form-group">
								<label>
									Tanggal Surat *
								</label>
								<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
									@if($data->contract_date)
										<input type="text" name="contract_date" id="contract_date" value="{{$data->contract_date}}" class="form-control"/>
									@else
										<input type="text" name="contract_date" id="contract_date" value="{{date('Y-m-d')}}" class="form-control"/>
									@endif
									<span class="input-group-btn">
										<button type="button" class="btn btn-default">
											<i class="glyphicon glyphicon-calendar"></i>
										</button>
									</span>
								</p>
							</div>
						</td>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table width=100%>
					<tr>
						<td>
							<button type="button" class="btn btn-danger btn-tgl-kontrak" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
</form>

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Pengujian</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Pengujian</span>
					</li>
					<li class="active">
						<span>Edit</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
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
			
			<div class="col-md-12">
				<div class="table-responsive">
					<div class="panel panel-default" style="border:solid; border-width:1px">
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
								
								<div id="step-1">
									<div class="form-group">
										<table class="table table-condensed">
											<thead>
											@if($data->keterangan == -1)
												<tr>
													<th colspan="3" align="center"><font color="red">Perangkat ini sudah pernah diuji, dengan status "Tidak Lulus Uji"</font></th>
												</tr>
											@endif
												<tr>
													<th colspan="3">Detail Informasi</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Tipe Pengujian:</td>
													<td>
														{{ $data->examinationType->name }} ({{ $data->examinationType->description }})
													</td>
												</tr>
												<tr>
													<td>Pemohon:</td>
													<td>
														{{ $data->user->name }}
													</td>
												</tr>
												<tr>
													<td>Perusahaan:</td>
													<td>
														{{ $data->company->name }}
													</td>
												</tr>
												<tr>
													<td>Tanggal Pengajuan:</td>
													<td>
														{{ $data->created_at }}
													</td>
												</tr>
												<tr>
													<td>Perangkat:</td>
													<td>
														{{ $data->device->name }}
													</td>
												</tr>	
												<tr>
													<td>Merek:</td>
													<td>
														{{ $data->device->mark }}
													</td>
												</tr>	
												<tr>
													<td>Model/Tipe:</td>
													<td>
														{{ $data->device->model }}
													</td>
												</tr>	
												<tr>
													<td>Kapasitas:</td>
													<td>
														{{ $data->device->capacity }}
													</td>
												</tr>	
												<tr>
													<td>Serial Number:</td>
													<td>
														{{ $data->device->serial_number }}
													</td>
												</tr>
												<tr>
													<td>Nomor Form Uji:</td>
													<td>
														{{ $data->function_test_NO }}
													</td>
												</tr>
												<tr>
													<td>Nama Lab:</td>
													<td>
														@if($data->examinationLab)
															{{ $data->examinationLab->name }}
														@endif
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							</div>
					</div>
				</div>
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'id' => 'form-registrasi')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Registrasi"/>
    				<fieldset>
						<legend>
							Step Registrasi
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Tanda Tangan Form Uji
									</label>
									@if($data->attachment != null)
										<label>
											: Sudah di tanda tangan
										</label>
								</div>
										<div class="form-group">
											<a href="{{URL::to('/admin/examination/download/'.$data->id)}}"> Download Form Uji</a>
										</div>
									@else
										<label>
											: Belum di tanda tangan
										</label>
									@endif
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Laboratorium Pengujian
									</label>
									<select name="examination_lab_id" class="cs-select cs-skin-elastic" required>
										@if($data->examination_lab_id != null)
											@foreach($labs as $item)
												@if($item->id == $data->examination_lab_id)
													<option value="{{$item->id}}" selected>{{$item->name}}</option>
												@else
													<option value="{{$item->id}}">{{$item->name}}</option>
												@endif
											@endforeach
										@else
											<option value="" disabled selected>Select...</option>
											@foreach($labs as $item)
												<option value="{{$item->id}}">{{$item->name}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Lokasi Pengujian
									</label>
									<select name="is_loc_test" class="cs-select cs-skin-elastic">
										@if($data->is_loc_test == 1)
											<option value="0">Uji Lab Telkom</option>
											<option value="1" selected>Uji Lokasi</option>
										@else
											<option value="0" selected>Uji Lab Telkom</option>
											<option value="1">Uji Lokasi</option>
										@endif
									</select>
								</div>
							</div>
							<!-- <div class="col-md-12">
								<div class="form-group">
									<label>
										Kelengkapan Registrasi
									</label>
									<label>
										: {{ $data->company->keterangan }}
									</label>
								</div>
							</div> -->
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="registration_status" class="cs-select cs-skin-elastic">
										@if($data->registration_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->registration_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
	                                Update
	                            </button>
	                        </div>
						</div>
						<div class="modal fade" id="myModalketerangan_registrasi" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Registrasi Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_registrasi"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}
				
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-function-test')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Uji Fungsi"/>
    				<fieldset>
						<legend>
							Step Uji Fungsi
						</legend>
						@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
							<a class="btn btn-wide btn-primary pull-left" style="margin-bottom:10px" data-toggle="modal" data-target="#myModal_reset_uf" onclick="document.getElementById('hide_exam_id').value = '{{ $data->id }}'">Reset Uji Fungsi</a>
						@endif
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th colspan="4">Riwayat Pengajuan Tanggal Uji Fungsi</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Pengajuan Tanggal Customer</td>
												<td>Jadwal dari Test Engineer</td>
												<td>Pengajuan Ulang dari Customer</td>
												<td>Jadwal dari Test Engineer</td>
											</tr>
											<tr>
												<td>
													<strong><?php echo $data->cust_test_date; ?></strong>
												</td>
												<td>
													<strong><?php echo $data->deal_test_date; ?></strong>
												</td>
												<td>
													<strong><?php echo $data->urel_test_date; ?></strong>
												</td>
												<td>
													<strong><?php echo $data->function_date; ?></strong>
												</td>
											</tr>
										</tbody>
									</table>

									@if($data->function_test_reason != '' && $data->function_test_date_approval != 1)
										<label for="alasan">Alasan Jadwal Ulang:</label>
										<textarea class="form-control" rows="2" name="reason" id="reason" readonly>{{ $data->function_test_reason }}</textarea>
									@endif
								</div>
								@if($data->function_test_date_approval == 1)
									<div class="col-md-6 center">
										<div class="form-group">
											<h4 style="display:inline">
												Jadwal FIX Uji Fungsi : 
												@if($data->function_date != null)
													<?php echo $data->function_date; ?>
												@else
													<?php echo $data->deal_test_date; ?>
												@endif
											</h4>
										</div>
									</div>
									<div class="col-md-6 center">
										<div class="form-group">
											<h4 style="display:inline">
												Disetujui oleh : {{ $data->function_test_PIC }}
											</h4>
										</div>
									</div>
								@endif
							</div>							

							<div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Lokasi Barang Sebelum Uji Fungsi
									</label>
									<select name="masukkan_barang" class="cs-select cs-skin-elastic">
										@if(count($data->equipment)==0)
											<option value="1" selected>Customer (Applicant)</option>
										@else
											<option value="2" selected>URel (Store)</option>
										@endif
									</select>
								</div>
							</div>
								@if($data->function_date != null)
									<?php $in_equip_date = $data->function_date; ?>
								@elseif($data->function_date == null && $data->urel_test_date != null)
									<?php $in_equip_date = $data->urel_test_date; ?>
								@elseif($data->urel_test_date == null && $data->deal_test_date != null)
									<?php $in_equip_date = $data->deal_test_date; ?>
								@else
									<?php $in_equip_date = $data->cust_test_date; ?>
								@endif
								@if(count($data->equipment)==0)
								<div class="col-md-12">
									<div class="form-group">
										<a onclick="masukkanBarang('{{ $data->id }}','{{ $in_equip_date }}')"> Masukkan Barang</a>
									</div>									
								</div>									
								@endif
							@if($data->function_test_TE == 1)
								<div class="col-md-12">
									<div class="form-group">
										<a href="{{URL::to('/cetakFormBarang/'.$data->id)}}" target="_blank"> Buatkan Bukti Penerimaan & Pengeluaran Perangkat Uji</a>
									</div>
								</div>
								<div class="col-md-12">
									<label>
										Bukti Penerimaan & Pengeluaran Perangkat Uji File *
									</label>
									<input type="file" name="barang_file" id="barang_file" class="form-control" accept="application/pdf"/>
									<button class="btn btn-wide btn-green btn-squared pull-right">
										Submit
									</button>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										@foreach($data->media as $item)
											@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji1' && $item->attachment != '')
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji1')}}"> Download Bukti Penerimaan & Pengeluaran Perangkat Uji</a>
											@endif
										@endforeach
									</div>
								</div>
							@endif
							<div class="col-md-12">
								@if($data->function_test_TE != 0)
								<div class="col-md-12 center">
									<div class="form-group">
										<h4 style="display:inline">
											Hasil Uji Fungsi
										</h4>
										<h4 style="display:inline">
											: @if($data->function_test_TE == 1)
												Memenuhi
											@elseif($data->function_test_TE == 2)
												Tidak Memenuhi
											@elseif($data->function_test_TE == 3)
												dll
											@else
												Tidak Ada
											@endif
										</h4>
									</div>
								</div>
								<div class="form-group">
									<a href="{{URL::to('/cetakUjiFungsi/'.$data->id)}}" target="_blank"> Buatkan Laporan Uji Fungsi</a>
								</div>
								@endif
								<div class="form-group">
									<label>
										Hasil Uji Fungsi File *
									</label>
									<input type="file" name="function_file" id="function_file" class="form-control" accept="application/pdf"/>
								</div>
								<div class="form-group">
									<?php $function_attach = ''; ?>
									@foreach($data->media as $item)
										@if($item->name == 'Laporan Hasil Uji Fungsi' && $item->attachment != '')
											<?php $function_attach = $item->attachment; ?>
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Laporan Hasil Uji Fungsi')}}"> Download Hasil Uji Fungsi "<?php echo $function_attach; ?>"</a>
										@endif
									@endforeach
									<input type="hidden" id="function_name" value="<?php echo $function_attach; ?>">
								</div>
								@if($data->function_test_TE != 0)
								<div class="form-group">
									<label for="catatan">Catatan :</label>
									<textarea class="form-control" rows="5" name="catatan" id="catatan" readonly disabled>{{ $data->catatan }}</textarea>
								</div>
								@endif
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="function_status" class="cs-select cs-skin-elastic">
										@if($data->function_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->function_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
							@if($data->registration_status == '1')
							<div class="col-md-12">
								<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
									Update
								</button>
							</div>
							@endif
						</div>
						<div class="modal fade" id="myModalketerangan_function" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Uji Fungsi Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_function"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-contract')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Tinjauan Kontrak"/>
    				<fieldset>
						<legend>
							Step Tinjauan Kontrak
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<a onclick="makeContract('<?php echo $data->id ?>','<?php echo $data->contract_date ?>')"> Buatkan File Kontrak</a>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Tinjauan Kontrak File *
									</label>
									<input type="file" name="contract_file" id="contract_file" class="form-control" accept="application/pdf"/>
								</div>
								<div class="form-group">
									<?php $contract_attach = ''; ?>
									@foreach($data->media as $item)
										@if($item->name == 'Tinjauan Kontrak' && $item->attachment != '')
											<?php $contract_attach = $item->attachment; ?>
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tinjauan Kontrak')}}"> Download Tinjauan Kontrak "<?php echo $contract_attach; ?>"</a>
										@endif
									@endforeach
									<input type="hidden" id="contract_name" value="<?php echo $contract_attach; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="contract_status" class="cs-select cs-skin-elastic">
										@if($data->contract_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->contract_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
							@if($data->registration_status == '1' && $data->function_status == '1')
							<div class="col-md-12">
								<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
									Update
								</button>
							</div>
							@endif
						</div>
						<div class="modal fade" id="myModalketerangan_contract" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Tinjauan Kontrak Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_contract"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-spb')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="SPB"/>
    				<fieldset>
						<legend>
							Step SPB
						</legend>
					@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1')
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<a onclick="makeSPB('<?php echo $data->id ?>','<?php echo $data->spb_number ?>','<?php echo $data->spb_date ?>')"> Buatkan File SPB</a>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										SPB File *
									</label>
									<input type="file" name="spb_file" id="spb_file" class="form-control" accept="application/pdf, image/*">
								</div>
								<div class="form-group">
									<?php $spb_attach = ''; ?>
									@foreach($data->media as $item)
										@if($item->name == 'SPB' && $item->attachment != '')
											<?php $spb_attach = $item->attachment; ?>
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/SPB')}}"> Download SPB "<?php echo $spb_attach; ?>"</a>
										@endif
									@endforeach
									<input type="hidden" id="spb_name" value="<?php echo $spb_attach; ?>">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="spb_status" class="cs-select cs-skin-elastic">
										@if($data->spb_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->spb_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					@endif
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Total Biaya *
									</label>
									<input type="text" name="exam_price" id="exam_price" class="form-control" placeholder="Total Biaya" value="{{ $data->price }}" readonly required>
								</div>
							</div>
							<input type="hidden" name="spb_number" id="spb_number" value="{{ $data->spb_number }}">
							<input type="hidden" name="spb_date" id="spb_date" value="{{ $data->spb_date }}">
							@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1')
							<div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
	                                Update
	                            </button>
	                        </div>
	                        @endif
						</div>
						<div class="modal fade" id="myModalketerangan_spb" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step SPB Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_spb"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-pembayaran')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Pembayaran"/>
    				<fieldset>
						<legend>
							Step Pembayaran
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Status Pembayaran
									</label>
										<?php $status = 0 ?>
									@foreach($data->media as $item)
										@if($item->name == 'File Pembayaran' && $item->attachment !='')
											<?php $status = 1; break; ?>
										@endif
									@endforeach

									@if($status)
										<label>
											: Sudah di bayar, pada {{ $item->updated_at }}
										</label>
										</div>
										<div class="form-group">
											<label>
												Banyak Uang *
											</label>
											<input type="text" name="cust_price_payment" id="cust_price_payment" class="form-control" placeholder="Banyak Uang" value="{{ $data->cust_price_payment }}" required>
										</div>
										<div class="form-group">
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/File Pembayaran')}}"> Download Bukti Pembayaran</a>
										</div>
									@else
										<label>
											: Belum di bayar
										</label>
									@endif
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
									<div class="form-group">
										<?php $kuitansi_attach = ''; ?>
										@foreach($data->media as $item)
											@if($item->name == 'Kuitansi' && $item->attachment != '')
												<?php $kuitansi_attach = $item->attachment; ?>
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/kuitansi')}}"> Download Kuitansi "<?php echo $kuitansi_attach; ?>"</a>
											@endif
										@endforeach
										<input type="hidden" id="kuitansi_name" value="<?php echo $kuitansi_attach; ?>">
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
										<?php $faktur_attach = ''; ?>
										@foreach($data->media as $item)
											@if($item->name == 'Faktur Pajak' && $item->attachment != '')
												<?php $faktur_attach = $item->attachment; ?>
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/faktur')}}"> Download Faktur Pajak "<?php echo $faktur_attach; ?>"</a>
											@endif
										@endforeach
										<input type="hidden" id="faktur_name" value="<?php echo $faktur_attach; ?>">
									</div>
								</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="payment_status" class="cs-select cs-skin-elastic">
										@if($data->payment_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->payment_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
								<input type="hidden" name="spb_number" id="spb_number" value="{{ $data->spb_number }}">
								<input type="hidden" name="exam_price" id="exam_price" value="{{ $data->price }}">
								<input type="hidden" name="spb_date" id="spb_date" value="{{ $data->spb_date }}">
							</div>
							@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1')
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							@endif
						</div>
						<div class="modal fade" id="myModalketerangan_pembayaran" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Pembayaran Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_pembayaran"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'id' => 'form-spk')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Pembuatan SPK"/>
    				<fieldset>
						<legend>
							Step Pembuatan SPK
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal SPK Dikeluarkan *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="spk_date" class="form-control" value="{{ $data->spk_date }}" required/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="spk_status" class="cs-select cs-skin-elastic">
										@if($data->spk_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->spk_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor SPK *
									</label>
										<input type="text" name="spk_code" id="spk_code" class="form-control" placeholder="Nomor SPK" value="{{ $data->spk_code }}" required 
										<?php if($data->spk_code != null){echo "readonly";}?>
										>
									@if($data->examination_lab_id != null && $data->spk_code == null)
										<button type="button" class="btn btn-wide btn-green btn-squared pull-right" onclick="generateSPKCode('<?php echo $data->examinationLab->lab_code ?>','<?php echo $data->examinationType->name ?>','<?php echo date('Y') ?>')">
											Generate
										</button>
									@endif
								</div>
							</div>
							@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1')
							<div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
	                                Update
	                            </button>
	                        </div>
	                        @endif
	                    </div>
						<div class="modal fade" id="myModalketerangan_spk" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Pembuatan SPK Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_spk"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'id' => 'form-uji')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Pelaksanaan Uji"/>
    				<fieldset>
						<legend>
							Step Pelaksanaan Uji
						</legend>
						<div class="row">
						@if($exam_schedule->code != 'MSTD0059AERR' && $exam_schedule->code != 'MSTD0000AERR')
							<?php
								$start_date = new DateTime(date('Y-m-d'));
								$end_date = new DateTime($exam_schedule->data[0]->targetDt);
								if($start_date>$end_date){
									$sisa_spk = 0;
								}else{
									$interval = $start_date->diff($end_date);
									$sisa_spk = $interval->days;
								}
							?>
							<div class="col-md-12">
								<div class="form-group">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th colspan="4">Riwayat Pelaksanaan Uji</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Tanggal Approve Manager Lab</td>
												<td>Target Selesai Uji</td>
												<td>Sisa Waktu Pengujian</td>
											</tr>
											<tr>
												<td>
													<strong>{{ $exam_schedule->data[0]->startTestDt }}</strong>
												</td>
												<td>
													<strong>{{ $exam_schedule->data[0]->targetDt }}</strong>
												</td>
												<td>
													<strong>{{ $sisa_spk }} hari</strong>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>	
							@if(count($data_lab)>0)
								<div class="col-md-12">
									<div class="form-group">
										<label for="form-field-select-2">
											Barang pindah dari Gudang ke Lab tanggal : {{ $data_lab[0]->action_date }}
										</label>
									</div>
								</div>
							@endif
							<div class="col-md-12">
								<div class="form-group">
									<table class="table table-bordered">
										<tbody>
											<tr>
												<td>Mulai Uji oleh Test Engineer</td>
												<td>Selesai Uji oleh Test Engineer</td>
											</tr>
											<tr>
												<td>
													<strong>{{ $exam_schedule->data[0]->actualStartTestDt }}</strong>
												</td>
												<td>
													<strong>{{ $exam_schedule->data[0]->actualFinishTestDt }}</strong>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							@if(count($data_gudang)>1)
								<div class="col-md-12">
									<div class="form-group">
										<label for="form-field-select-2"> 
											Barang pindah dari Lab ke Gudang tanggal : 
											@if($data_gudang[0]->action_date != NULL AND $data_gudang[0]->action_date != '0000-00-00')
												tanggal : {{ $data_gudang[0]->action_date }}
											@endif
										</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal Barang Dikembalikan *
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											@if($data_gudang[0]->action_date != NULL AND $data_gudang[0]->action_date != '0000-00-00')
												<input type="text" name="lab_to_gudang_date" class="form-control" value="{{ $data_gudang[0]->action_date }}" required/>
											@else
												<input type="text" name="lab_to_gudang_date" class="form-control" value="" required/>
											@endif
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
												</button>
											</span>
										</p>
									</div>
								</div>
							@endif
						@endif						
							<div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Lokasi Barang Sekarang
									</label>
									<label>
										: 
										@if(count($data->equipment)==0)
											Lab (Laboratory)
										@elseif($data->equipment[0]->location==1)
											Customer (Applicant)
										@elseif($data->equipment[0]->location==2)
											URel (Store)
										@elseif($data->equipment[0]->location==3)
											Lab (Laboratory)
										@endif
									</label>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="examination_status" class="cs-select cs-skin-elastic">
										@if($data->examination_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->examination_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
							@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1')
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
	                                Update
	                            </button>
	                        </div>
	                        @endif
						</div>
						<div class="modal fade" id="myModalketerangan_form_uji" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Pelaksanaan Uji Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_form_uji"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}
				
				@if($data->examination_type_id !='1')
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-barang')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value=""/>
					<input type="hidden" name="keterangan" class="form-control" value=""/>
					<fieldset>
						<legend>
							Edit Lokasi Barang
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji1' && $item->attachment != '')
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji1')}}"> Download Bukti Penerimaan Perangkat Uji</a>
										@endif
									@endforeach
								</div>
								<div class="form-group">
									<label>
										Bukti Penerimaan & Pengeluaran Perangkat Uji File *
									</label>
									<input type="file" name="barang_file2" id="barang_file2" class="form-control" accept="application/pdf"/>
									<button class="btn btn-wide btn-green btn-squared pull-right">
										Submit
									</button>
								</div>
								<div class="form-group">
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji2')}}"> Download Bukti Pengeluaran Perangkat Uji</a>
										@endif
									@endforeach
								</div>
								</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Lokasi Barang Sekarang
									</label>
									<select name="update_barang" class="cs-select cs-skin-elastic">
										@if(count($data->equipment)==0)
											<option value="2" selected>URel (Store)</option>
										@elseif($data->equipment[0]->location==1)
											<option value="1" selected>Customer (Applicant)</option>
										@elseif($data->equipment[0]->location==2)
											<option value="2" selected>URel (Store)</option>
										@elseif($data->equipment[0]->location==3)
											<option value="3" selected>Lab (Laboratory)</option>
										@endif
									</select>
								</div>
								<div class="form-group">
									<a onclick="updateBarang('{{ $data->id }}')"> Update Lokasi Barang</a>
								</div>	
							</div>
						</div>
					</fieldset>
				{!! Form::close() !!}
				@endif
				@if($data->examination_type_id != 1)
						{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-tanda-terima')) !!}
							{!! csrf_field() !!}
							<input type="hidden" name="status" class="form-control" value=""/>
							<input type="hidden" name="keterangan" class="form-control" value=""/>
		    				<fieldset>
								<legend>
									Tanda Terima Hasil Pengujian
								</legend>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<a onclick="makeTandaTerima('<?php echo $data->id ?>')"> Buatkan File Tanda Terima</a>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>
												File Tanda Terima *
											</label>
											<input type="file" name="tanda_terima_file" id="tanda_terima_file" class="form-control" accept="application/pdf"/>
										</div>
										<div class="form-group">
											<?php $tanda_terima_attach = ''; ?>
											@foreach($data->media as $item)
												@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
													<?php $tanda_terima_attach = $item->attachment; ?>
													<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"<?php echo $tanda_terima_attach; ?>"</a>
												@endif
											@endforeach
											<input type="hidden" id="tanda_terima_name" value="<?php echo $tanda_terima_attach; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
											Submit
										</button>
									</div>
								</div>
							</fieldset>
						{!! Form::close() !!}
					@endif
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-lap-uji')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Laporan Uji"/>
    				<fieldset>
						<legend>
							Step Laporan Uji
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									@foreach($data->media as $item)
										@if($item->name == 'Laporan Uji')
											@if($item->attachment != '')
											<div class="col-md-4">
												<div class="form-group">
													<a href="{{$item->attachment}}&isCover=true&isIsi=false"> Download Sampul/Judul Laporan </a>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<a href="{{$item->attachment}}&isCover=false&isIsi=true"> Download Isi Laporan </a>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<a href="{{$item->attachment}}"> Download Keseluruhan Laporan </a>
												</div>
											</div>
											@else
											<label>
												Laporan Hasil Pengujian
											</label>
											<label>
												: Belum Tersedia
											</label>
											@endif
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-12">
								@if($exam_schedule->code != 'MSTD0059AERR' && $exam_schedule->code != 'MSTD0000AERR')
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Mulai Pembuatan Laporan
										</label>
										<label>
											: {{ $exam_schedule->data[0]->startReportDt }}
										</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Selesai Pembuatan Laporan
										</label>
										<label>
											: {{ $exam_schedule->data[0]->finishReportDt }}
										</label>
									</div>
								</div>
								@endif
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Laporan Diterbitkan*
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="resume_date" class="form-control" value="{{ $data->resume_date }}" required/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="resume_status" class="cs-select cs-skin-elastic">
										@if($data->resume_status == 0)
											<option value="0" selected>Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1">Not Completed</option>
										@elseif($data->resume_status == 1)
											<option value="0">Choose Status</option>
											<option value="1" selected>Completed</option>
											<option value="-1">Not Completed</option>
										@else
											<option value="0">Choose Status</option>
											<option value="1">Completed</option>
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
							@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1')
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
	                                Update
	                            </button>
	                        </div>
	                        @endif
						</div>
						<div class="modal fade" id="myModalketerangan_lap_uji" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Laporan Uji Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table width=100%>
											<tr>
												<td>
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan_lap_uji"></textarea>
													</div>
												</td>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table width=100%>
											<tr>
												<td>
													<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
												</td>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
				{!! Form::close() !!}
				@if($data->examination_type_id !='2' && $data->examination_type_id !='3' && $data->examination_type_id !='4')
					{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'id' => 'form-sidang')) !!}
					{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Sidang QA"/>
	    				<fieldset>
							<legend>
								Step Sidang QA
							</legend>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>
											Status Pengujian *
										</label>
										<div class="radio-list">
											@if($data->qa_passed == 1)
											<div class="radio">
												<div class="radio clip-radio radio-primary">
														<input type="radio" value="1" name="passed" id="passed" checked>
														<label for="passed">
															Lulus
														</label>
												</div>
											</div>
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="-1" name="passed" id="notPassed">
													<label for="notPassed">
														Tidak Lulus
													</label>
												</div>
											</div>
											@elseif($data->qa_passed == -1)
											<div class="radio">
												<div class="radio clip-radio radio-primary">
														<input type="radio" value="1" name="passed" id="passed">
														<label for="passed">
															Lulus
														</label>
												</div>
											</div>
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="-1" name="passed" id="notPassed" checked>
													<label for="notPassed">
														Tidak Lulus
													</label>
												</div>
											</div>
											@else
											<div class="radio">
												<div class="radio clip-radio radio-primary">
														<input type="radio" value="1" name="passed" id="passed">
														<label for="passed">
															Lulus
														</label>
												</div>
											</div>
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="-1" name="passed" id="notPassed">
													<label for="notPassed">
														Tidak Lulus
													</label>
												</div>
											</div>
											@endif
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal Verifikasi *
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" name="qa_date" class="form-control" value="{{ $data->qa_date }}" required/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
												</button>
											</span>
										</p>
									</div>
								</div>
		                        <div class="col-md-6">
									<div class="form-group">
										<label for="form-field-select-2">
											Status *
										</label>
										<select name="qa_status" class="cs-select cs-skin-elastic">
											@if($data->qa_status == 0)
												<option value="0" selected>Choose Status</option>
												<option value="1">Completed</option>
												<option value="-1">Not Completed</option>
											@elseif($data->qa_status == 1)
												<option value="0">Choose Status</option>
												<option value="1" selected>Completed</option>
												<option value="-1">Not Completed</option>
											@else
												<option value="0">Choose Status</option>
												<option value="1">Completed</option>
												<option value="-1" selected>Not Completed</option>
											@endif
										</select>
									</div>
								</div>
								@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && $data->resume_status == '1')
		                        <div class="col-md-12">
		                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
		                                Update
		                            </button>
		                        </div>
		                       	@endif
							</div>
							<div class="modal fade" id="myModalketerangan_sidang_qa" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Sidang QA Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%>
												<tr>
													<td>
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_sidang_qa"></textarea>
														</div>
													</td>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%>
												<tr>
													<td>
														<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
													</td>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
					
					{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-barang')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value=""/>
						<input type="hidden" name="keterangan" class="form-control" value=""/>
						<fieldset>
							<legend>
								Edit Lokasi Barang
							</legend>
							<div class="row">
								<div class="form-group">
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji1' && $item->attachment != '')
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji1')}}"> Download Bukti Penerimaan Perangkat Uji</a>
										@endif
									@endforeach
								</div>
								<div class="form-group">
									<label>
										Bukti Penerimaan & Pengeluaran Perangkat Uji File *
									</label>
									<input type="file" name="barang_file2" id="barang_file2" class="form-control" accept="application/pdf"/>
									<button class="btn btn-wide btn-green btn-squared pull-right">
										Submit
									</button>
								</div>
								<div class="form-group">
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji2')}}"> Download Bukti Pengeluaran Perangkat Uji</a>
										@endif
									@endforeach
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="form-field-select-2">
											Lokasi Barang Sekarang
										</label>
										<select name="update_barang" class="cs-select cs-skin-elastic">
											@if(count($data->equipment)==0)
												<option value="2" selected>URel (Store)</option>
											@elseif($data->equipment[0]->location==1)
												<option value="1" selected>Customer (Applicant)</option>
											@elseif($data->equipment[0]->location==2)
												<option value="2" selected>URel (Store)</option>
											@elseif($data->equipment[0]->location==3)
												<option value="3" selected>Lab (Laboratory)</option>
											@endif
										</select>
									</div>
									<div class="form-group">
										<a onclick="updateBarang('{{ $data->id }}')"> Update Lokasi Barang</a>
									</div>	
								</div>
							</div>
						</fieldset>
					{!! Form::close() !!}
					@if($data->examination_type_id == 1)
						{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-tanda-terima')) !!}
							{!! csrf_field() !!}
							<input type="hidden" name="status" class="form-control" value=""/>
							<input type="hidden" name="keterangan" class="form-control" value=""/>
		    				<fieldset>
								<legend>
									Tanda Terima Hasil Pengujian
								</legend>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<a onclick="makeTandaTerima('<?php echo $data->id ?>')"> Buatkan File Tanda Terima</a>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>
												File Tanda Terima *
											</label>
											<input type="file" name="tanda_terima_file" id="tanda_terima_file" class="form-control" accept="application/pdf"/>
										</div>
										<div class="form-group">
											<?php $tanda_terima_attach = ''; ?>
											@foreach($data->media as $item)
												@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
													<?php $tanda_terima_attach = $item->attachment; ?>
													<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"<?php echo $tanda_terima_attach; ?>"</a>
												@endif
											@endforeach
											<input type="hidden" id="tanda_terima_name" value="<?php echo $tanda_terima_attach; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
											Submit
										</button>
									</div>
								</div>
							</fieldset>
						{!! Form::close() !!}
					@endif
					{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-sertifikat')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Penerbitan Sertifikat"/>
	    				<fieldset>
							<legend>
								Step Penerbitan Sertifikat
							</legend>
							<div class="row">
								@if($data->qa_passed == 1)
								<div>
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Sertifikat File *
											</label>
											<input type="file" name="certificate_file" class="form-control" accept="application/pdf, image/*" required />
										</div>
										<div class="form-group">
										@if($data->certificate_status)
											@if($data->device->certificate)
												<a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}"> Download Certificate {{ $data->device->cert_number }}</a>
											@endif
										@endif
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Nomor Sertifikat *
											</label>
												<input type="text" name="cert_number" id="cert_number" class="form-control" placeholder="Nomor Sertifikat" value="{{ $data->device->cert_number }}" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal Penerbitan Sertifikat *
											</label>
											<input type="text" name="certificate_date" class="form-control" value="{{ $data->certificate_date }}" readonly>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal Mulai Berlaku *
											</label>
											<p id="validFrom" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_from" id="valid_from" class="form-control" value="{{ $data->device->valid_from }}"/>
												<span class="input-group-btn">
													<button type="button" class="btn btn-default">
														<i class="glyphicon glyphicon-calendar"></i>
													</button>
												</span>
											</p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal Akhir Berlaku *
											</label>
											<p id="validThru" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_thru" id="valid_thru" class="form-control" value="{{ $data->device->valid_thru }}"/>
												<span class="input-group-btn">
													<button type="button" class="btn btn-default">
														<i class="glyphicon glyphicon-calendar"></i>
													</button>
												</span>
											</p>
										</div>
									</div>
								</div>
								@endif
		                        <div class="col-md-6">
									<div class="form-group">
										<label for="form-field-select-2">
											Status *
										</label>
										<select name="certificate_status" class="cs-select cs-skin-elastic">
											@if($data->certificate_status == 0)
												<option value="0" selected>Choose Status</option>
												<option value="1">Completed</option>
												<option value="-1">Not Completed</option>
											@elseif($data->certificate_status == 1)
												<option value="0">Choose Status</option>
												<option value="1" selected>Completed</option>
												<option value="-1">Not Completed</option>
											@else
												<option value="0">Choose Status</option>
												<option value="1">Completed</option>
												<option value="-1" selected>Not Completed</option>
											@endif
										</select>
									</div>
								</div>
								@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && $data->resume_status == '1' && $data->qa_status == '1')
		                        <div class="col-md-12">
		                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
		                                Update
		                            </button>
		                        </div>
		                        @endif
							</div>
							<div class="modal fade" id="myModalketerangan_sertifikat" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><i class="fa fa-eyes-open"></i> Step Penerbitan Sertifikat Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%>
												<tr>
													<td>
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_sertifikat"></textarea>
														</div>
													</td>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%>
												<tr>
													<td>
														<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
													</td>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif
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
		$('#valid_from').change(function() {
			var dateThru = $('#validFrom').datepicker('getDate');
			dateThru.setYear(dateThru.getYear()+1903);
			$('#validThru').datepicker('setDate', dateThru);
		});

		$('#myModal_reset_uf').on('shown.bs.modal', function () {
		    $('#keterangan').focus();
		});

		$('#btn-modal-reset_uf').click(function () {
		 	var baseUrl = "{{URL::to('/')}}";
			var keterangan = document.getElementById('keterangan').value;
			var exam_id = document.getElementById('hide_exam_id').value;
			if(keterangan == ''){
				$('#myModal_reset_uf').modal('show');
				return false;
			}else{
				$('#myModal_reset_uf').modal('hide');
				if (confirm('Are you sure want to reset ?')) {
				    document.getElementById("overlay").style.display="inherit";	
				 	document.location.href = baseUrl+'/admin/examination/resetUjiFungsi/'+exam_id+'/'+encodeURIComponent(encodeURIComponent(keterangan));   
				}
			}
		});
	});
	
	function generateSPKCode(a,b,c){
		$.ajax({
			type: "POST",
			url : "generateSPKCode",
			data: {'_token':"{{ csrf_token() }}", 'lab_code':a, 'exam_type':b, 'year':c},
			beforeSend: function(){
				document.getElementById("spk_code").disabled = true;
			},
			success: function(response){
				document.getElementById("spk_code").disabled = false;
				document.getElementById("spk_code").value = response;
				$('#spk_code').val(response);
			},
			error:function(){
				alert("Gagal mengambil data");
				document.getElementById("spk_code").disabled = false;
			}
		});
	}
	
	function makeContract(a,b){
		$('#contract-modal-content').modal('show');
		$('#hide_id_exam').val(a);
		$('#contract-modal-content').on('shown.bs.modal', function() {
			// $('#contract_date').val(b);
			// $('#testing_start').val(c);
			// $('#testing_end').val(d);
			$("#contract_date").focus();
		})
	}
	
	function makeSPB(a,b,c){
		var APP_URL = {!! json_encode(url('/admin/examination/generateSPB')) !!};		
		$.ajax({
			type: "POST",
			url : "generateSPBParam",
			data: {'_token':"{{ csrf_token() }}", 'exam_id':a, 'spb_number':b, 'spb_date':c},
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
	
	function makeKuitansi(a){
		var APP_URL = {!! json_encode(url('/admin/kuitansi/create')) !!};		
		$.ajax({
			type: "POST",
			url : "generateKuitansiParam",
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
	
	function masukkanBarang(a,b){
		var APP_URL = {!! json_encode(url('/admin/equipment/create')) !!};		
		$.ajax({
			type: "POST",
			url : "generateEquipParam",
			data: {'_token':"{{ csrf_token() }}", 'exam_id':a, 'in_equip_date':b},
			beforeSend: function(){
				
			},
			success: function(response){
				if(response == 1){
					window.open(APP_URL, 'mywin','status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0,scrollbars=1,width=720,height=500');
				}else{
					alert("Gagal mengambil data");
				}
			},
			error:function(){
				alert("Gagal mengambil data");
			}
		});
	}
	
	function updateBarang(a){
		var APP_URL = {!! json_encode(url('/admin/examination/generateEquip')) !!}
		$.ajax({
			type: "POST",
			url : "generateEquipParam",
			data: {'_token':"{{ csrf_token() }}", 'exam_id':a},
			beforeSend: function(){
				
			},
			success: function(response){
				if(response == 1){
					window.open(APP_URL, 'mywin','status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0,scrollbars=1,width=720,height=500');
				}else{
					alert("Gagal mengambil data");
				}
			},
			error:function(){
				alert("Gagal mengambil data");
			}
		});
	}

	function makeTandaTerima(a){
		
		var APP_URL = {!! json_encode(url('/cetakTandaTerima')) !!};
		
		$.ajax({
			type: "POST",
			url : "tandaterima",
			data: {'_token':"{{ csrf_token() }}", 'hide_id_exam':a},
			beforeSend: function(){
				
			},
			success: function(response){
				if(response == 1){
					window.open(APP_URL);
					// location.reload();
				}else{
					alert("Gagal mengambil data");
				}
			},
			error:function(){
				alert("Gagal mengambil data");
			}
		});
	}
	
	$('.btn-tgl-kontrak').click(function () {
		var a = document.getElementById('hide_id_exam').value;
		var b = document.getElementById('contract_date').value;
		
		var APP_URL = {!! json_encode(url('/cetakKontrak')) !!};
		
		$.ajax({
			type: "POST",
			url : "tanggalkontrak",
			data: {'_token':"{{ csrf_token() }}", 'hide_id_exam':a, 'contract_date':b},
			beforeSend: function(){
				
			},
			success: function(response){
				if(response == 1){
					$('#contract-modal-content').modal('hide');
					window.open(APP_URL);
					// location.reload();
				}else{
					alert("Gagal mengambil data");
					$('#contract-modal-content').modal('hide');
				}
			},
			error:function(){
				alert("Gagal mengambil data");
				$('#contract-modal-content').modal('hide');
			}
		});
	});
	
	$('#form-registrasi').submit(function () {
		var keterangan = document.getElementById('keterangan_registrasi').value;
		var $inputs = $('#form-registrasi :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['registration_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_registrasi').modal('show');
			return false;
		}else{
			$('#myModalketerangan_registrasi').modal('hide');
		}
	});
	
	$('#form-function-test').submit(function () {
		var keterangan = document.getElementById('keterangan_function').value;
		var barang_file = document.getElementById('barang_file');
		var function_file = document.getElementById('function_file');
		var function_name = document.getElementById('function_name').value;
		var $inputs = $('#form-function-test :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(barang_file.value ==''){
			if(values['function_status'] == '-1'){
				if(keterangan == ''){
					$('#myModalketerangan_function').modal('show');
					return false;
				}else{
					$('#myModalketerangan_function').modal('hide');
				}			
			}else{
				if(function_file.value == '' && function_name == ''){
					alert("File Laporan Hasil Uji Fungsi harus dipilih");$('.function_file').focus();return false;				
				}
			}
		}
	});
	
	$('#form-contract').submit(function () {
		var keterangan = document.getElementById('keterangan_contract').value;
		var contract_file = document.getElementById('contract_file');
		var contract_name = document.getElementById('contract_name').value;
		var $inputs = $('#form-contract :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['contract_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_contract').modal('show');
				return false;
			}else{
				$('#myModalketerangan_contract').modal('hide');
			}			
		}else{
			if(contract_file.value == '' && contract_name == ''){
				alert("File Tinjauan Kontrak harus dipilih");$('.contract_file').focus();return false;				
			}
		}
	});
	
	$('#form-spb').submit(function () {
		var keterangan = document.getElementById('keterangan_spb').value;
		var spb_file = document.getElementById('spb_file');
		var spb_name = document.getElementById('spb_name').value;
		var $inputs = $('#form-spb :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		/* if(values['spb_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_spb').modal('show');
			return false;
		}else{
			$('#myModalketerangan_spb').modal('hide');
		} */
		if(values['spb_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_spb').modal('show');
				return false;
			}else{
				$('#myModalketerangan_spb').modal('hide');
			}			
		}else{
			if(spb_file.value == '' && spb_name == ''){
				alert("File SPB harus dipilih");$('.spb_file').focus();return false;				
			}
		}
	});
	
	$('#form-pembayaran').submit(function () {
		var keterangan = document.getElementById('keterangan_pembayaran').value;
		var kuitansi_file = document.getElementById('kuitansi_file');
		var kuitansi_name = document.getElementById('kuitansi_name').value;
		var $inputs = $('#form-pembayaran :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['payment_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_pembayaran').modal('show');
				return false;
			}else{
				$('#myModalketerangan_pembayaran').modal('hide');
			}			
		}else{
			if(kuitansi_file.value == '' && kuitansi_name == ''){
				alert("File Kuitansi harus dipilih");$('.kuitansi_file').focus();return false;				
			}
		}
		/* if(values['payment_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_pembayaran').modal('show');
			return false;
		}else{
			$('#myModalketerangan_pembayaran').modal('hide');
		} */
	});
	
	$('#form-spk').submit(function () {
		var keterangan = document.getElementById('keterangan_spk').value;
		var $inputs = $('#form-spk :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['spk_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_spk').modal('show');
			return false;
		}else{
			$('#myModalketerangan_spk').modal('hide');
		}
	});
	
	$('#form-uji').submit(function () {
		var keterangan = document.getElementById('keterangan_form_uji').value;
		var $inputs = $('#form-uji :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['examination_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_form_uji').modal('show');
			return false;
		}else{
			$('#myModalketerangan_form_uji').modal('hide');
		}
	});

	$('#form-tanda-terima').submit(function () {
		var tanda_terima_file = document.getElementById('tanda_terima_file');
		var tanda_terima_name = document.getElementById('tanda_terima_name').value;
		var $inputs = $('#form-tanda-terima :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(tanda_terima_file.value == '' && tanda_terima_name == ''){
			alert("File Tanda Terima harus dipilih");$('.tanda_terima_file').focus();return false;				
		}
	});
</script>
@if($data->examination_type_id =='2' || $data->examination_type_id =='3' || $data->examination_type_id =='4')
<script type="text/javascript">
	$('#form-lap-uji').submit(function () {
		var keterangan = document.getElementById('keterangan_lap_uji').value;
		var lap_uji_file = document.getElementById('lap_uji_file');
		var lap_uji_name = document.getElementById('lap_uji_name').value;
		var $inputs = $('#form-lap-uji :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['resume_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_lap_uji').modal('show');
				return false;
			}else{
				$('#myModalketerangan_lap_uji').modal('hide');
			}			
		}else{
			if(lap_uji_file.value == '' && lap_uji_name == ''){
				alert("File Laporan Uji harus dipilih");$('.lap_uji_file').focus();return false;				
			}
		}
	});
</script>
@else
<script type="text/javascript">
	$('#form-lap-uji').submit(function () {
		var keterangan = document.getElementById('keterangan_lap_uji').value;
		var $inputs = $('#form-lap-uji :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['resume_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_lap_uji').modal('show');
			return false;
		}else{
			$('#myModalketerangan_lap_uji').modal('hide');
		}
	});
</script>
@endif
@if($data->examination_type_id !='2' && $data->examination_type_id !='3' && $data->examination_type_id !='4')
<script type="text/javascript">	
	$('#form-sidang').submit(function () {
		var keterangan = document.getElementById('keterangan_sidang_qa').value;
		var $inputs = $('#form-sidang :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['qa_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_sidang_qa').modal('show');
			return false;
		}else{
			$('#myModalketerangan_sidang_qa').modal('hide');
		}
	});
	
	$('#form-sertifikat').submit(function () {
		var keterangan = document.getElementById('keterangan_sertifikat').value;
		var $inputs = $('#form-sertifikat :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['certificate_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_sertifikat').modal('show');
			return false;
		}else{
			$('#myModalketerangan_sertifikat').modal('hide');
		}
	});
</script>
@endif
@endsection
