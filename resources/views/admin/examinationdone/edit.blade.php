@extends('layouts.app')

@section('content')

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
								
								<div id="step-1">
									<div class="form-group">
										<table class="table table-condensed">
											<thead>
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
													<td>Kapasitas:</td>
													<td>
														{{ $data->device->capacity }}
													</td>
												</tr>	
												<tr>
													<td>Model:</td>
													<td>
														{{ $data->device->model }}
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
				{!! Form::open() !!}
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
									<select name="examination_lab_id" class="cs-select cs-skin-elastic" disabled>
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
									<label>
										Tanggal Uji Fungsi *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										@if($data->deal_test_date != null)
											<input type="text" name="deal_test_date" class="form-control" value="{{ $data->deal_test_date }}" readonly/>
										@elseif($data->deal_test_date == null && $data->cust_test_date != null)
											<input type="text" name="deal_test_date" class="form-control" value="{{ $data->cust_test_date }}" readonly/>
										@else
											<input type="text" name="urel_test_date" class="form-control" value="{{ $data->urel_test_date }}" readonly/>
										@endif
									</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>
										Pengajuan Tanggal Uji dari URel
									</label>
									<label>
										: <?php echo $data->urel_test_date; ?>
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>
										Pengajuan Tanggal Uji dari Customer
									</label>
									<label>
										: <?php echo $data->cust_test_date; ?>
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>
										Penetapan akhir Tanggal Uji
									</label>
									<label>
										: <?php echo $data->deal_test_date; ?>
									</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="registration_status" class="cs-select cs-skin-elastic" disabled>
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
				
				{!! Form::open() !!}
					<fieldset>
						<legend>
							Step Uji Fungsi
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="catatan">Catatan :</label>
									<textarea class="form-control" rows="5" name="catatan" id="catatan" disabled>{{ $data->catatan }}</textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="function_date" class="form-control" value="{{ $data->function_date }}" readonly/>
									</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="function_status" class="cs-select cs-skin-elastic" disabled>
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

				{!! Form::open() !!}
					<fieldset>
						<legend>
							Step Tinjauan Kontrak
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Tinjauan Kontrak File *
									</label>
								</div>
								<div class="form-group">
									<?php $contract_attach = ''; ?>
									@foreach($data->media as $item)
										@if($item->name == 'Tinjauan Kontrak' && $item->attachment != '')
											<?php $contract_attach = $item->attachment; ?>
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tinjauan Kontrak')}}"> Download Tinjauan Kontrak "<?php echo $contract_attach; ?>"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="contract_status" class="cs-select cs-skin-elastic" disabled>
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

				{!! Form::open() !!}
					<fieldset>
						<legend>
							Step SPB
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										SPB File *
									</label>
								</div>
								<div class="form-group">
									<?php $spb_attach = ''; ?>
									@foreach($data->media as $item)
										@if($item->name == 'SPB' && $item->attachment != '')
											<?php $spb_attach = $item->attachment; ?>
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/SPB')}}"> Download SPB "<?php echo $spb_attach; ?>"</a>
										@endif
									@endforeach
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="spb_status" class="cs-select cs-skin-elastic" disabled>
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
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Total Biaya (Biaya Belum Termasuk PPN)*
									</label>
									<input type="text" name="exam_price" id="exam_price" class="form-control" placeholder="Total Biaya" value="{{ $data->price }}" readonly>
								</div>
							</div>
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

				{!! Form::open() !!}
					<fieldset>
						<legend>
							Step Pembayaran
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Status Pembayaran
									</label>
									@foreach($data->media as $item)
										@if($item->name == 'File Pembayaran' && $item->attachment !='')
											<?php $status = 1; break; ?>
										@else
											<?php $status = 0 ?>
										@endif
									@endforeach

									@if($status)
										<label>
											: Sudah di bayar
										</label>
										</div>
										<div class="form-group">
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/File Pembayaran')}}"> Download Bukti Pembayaran</a>
										</div>
									@else
										<label>
											: Belum di bayar
										</label>
										</div>
									@endif
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="payment_status" class="cs-select cs-skin-elastic" disabled>
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
							</div>
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

				{!! Form::open() !!}
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
										<input type="text" name="spk_date" class="form-control" value="{{ $data->spk_date }}" readonly/>
									</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="spk_status" class="cs-select cs-skin-elastic" disabled>
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
										<input type="text" name="spk_code" id="spk_code" class="form-control" placeholder="Nomor SPK" value="{{ $data->spk_code }}" readonly>
								</div>
							</div>
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

				{!! Form::open() !!}
					<fieldset>
						<legend>
							Step Pelaksanaan Uji
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Selesai *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="examination_date" class="form-control" value="{{ $data->examination_date }}" readonly/>
									</p>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="examination_status" class="cs-select cs-skin-elastic" disabled>
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

				{!! Form::open() !!}
					<fieldset>
						<legend>
							Step Laporan Uji
						</legend>
						<div class="row">
							@if($data->examination_type_id =='2' || $data->examination_type_id =='3')
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Laporan Uji File *
									</label>
								</div>
								<div class="form-group">
									<?php $lap_uji_attach = ''; ?>
									@foreach($data->media as $item)
										@if($item->name == 'Laporan Uji' && $item->attachment != '')
											<?php $lap_uji_attach = $item->attachment; ?>
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Laporan Uji')}}"> Download Laporan Uji "<?php echo $lap_uji_attach; ?>"</a>
										@endif
									@endforeach
								</div>
							</div>
							@endif
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="resume_date" class="form-control" value="{{ $data->resume_date }}" readonly/>
									</p>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="resume_status" class="cs-select cs-skin-elastic" disabled>
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
					{!! Form::open() !!}
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
											@if($data->qa_passed)
											<div class="radio">
												<div class="radio clip-radio radio-primary">
														<input type="radio" value="1" name="passed" id="passed" checked>
														<label for="passed">
															Lulus
														</label>
												</div>
											</div>
											@else
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="0" name="passed" id="notPassed" checked>
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
											Tanggal *
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" name="qa_date" class="form-control" value="{{ $data->qa_date }}" readonly/>
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
										<select name="qa_status" class="cs-select cs-skin-elastic" disabled>
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
					
					{!! Form::open() !!}
						<fieldset>
							<legend>
								Feedback
							</legend>
							<div class="form-group">
								<a href="{{URL::to('/cetakKepuasanKonsumen')}}" target="_blank"> Download Feedback</a>
							</div>
						</fieldset>
					{!! Form::close() !!}

					{!! Form::open() !!}
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
										</div>
										<div class="form-group">
										@if($data->certificate_status)
											@if($data->device->certificate)
												<a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}"> Download Certificate</a>
											@endif
										@endif
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal *
											</label>
											<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
												<input type="text" name="certificate_date" class="form-control" value="{{ $data->certificate_date }}" readonly/>
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
												Valid From *
											</label>
											<p id="validFrom" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_from" id="valid_from" class="form-control" value="{{ $data->device->valid_from }}" readonly/>
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
												Valid Thru *
											</label>
											<p id="validThru" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_thru" id="valid_thru" class="form-control" value="{{ $data->device->valid_thru }}" readonly/>
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
										<select name="certificate_status" class="cs-select cs-skin-elastic" disabled>
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
					{!! Form::open() !!}
						<fieldset>
							<legend>
								Histori Download Sertifikat
							</legend>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<?php $count = 0; ?>
										@foreach($data->examinationHistory as $item)
										<?php
											if( strpos( $item->tahap, "Sertifikat" ) !== false ) 
											{
												$count++;
											}
										?>
										@endforeach
										Sertifikat Sudah terdownload sebanyak {{ $count }} kali
									</div>
								</div>
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
	});
</script>
@endsection