@extends('layouts.app')

@section('content')
<form id="form-tanggal-kontrak" role="form">
{!! csrf_field() !!}
<input type="hidden" name="hide_id_exam" id="hide_id_exam"/>
<div class="modal fade" id="contract-modal-content" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Silakan Isi Data-data Berikut !</h4>
			</div>
			
			<div class="modal-body">
				<table width=100%><caption></caption>
					<tr>
						<th scope="col">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="contract_date" id="contract_date" class="form-control"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Testing From *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
										<input type="text" name="testing_start" id="testing_start" class="form-control"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Testing Thru *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
										<input type="text" name="testing_end" id="testing_end" class="form-control"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
						</th>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table width=100%><caption></caption>
					<tr>
						<th scope="col">
							<button type="button" class="btn btn-danger btn-tgl-kontrak" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
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
										<table class="table table-condensed"><caption></caption>
											<thead>
												<tr>
													<th colspan="3" scope="col">Detail Informasi</th>
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
				@if($adminRole->registration_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'id' => 'form-registrasi')) !!}
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
										<label>
											Tanggal Uji Fungsi *
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											@if($data->deal_test_date != null)
												<input type="text" name="deal_test_date" class="form-control" value="{{ $data->deal_test_date }}"/>
											@elseif($data->deal_test_date == null && $data->cust_test_date != null)
												<input type="text" name="deal_test_date" class="form-control" value="{{ $data->cust_test_date }}"/>
											@else
												<input type="text" name="urel_test_date" class="form-control" value="{{ $data->urel_test_date }}"/>
											@endif
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
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
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Registrasi Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_registrasi"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif
				
				@if($adminRole->function_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'id' => 'form-function-test')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Uji Fungsi"/>
						<fieldset>
							<legend>
								Step Uji Fungsi
							</legend>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="catatan">Catatan :</label>
										<textarea class="form-control" rows="5" name="catatan" id="catatan">{{ $data->catatan }}</textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal *
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" name="function_date" class="form-control" value="{{ $data->function_date }}"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
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
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							<div class="modal fade" id="myModalketerangan_function" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Uji Fungsi Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_function"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif
				
				@if($adminRole->contract_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-contract')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Tinjauan Kontrak"/>
						<fieldset>
							<legend>
								Step Tinjauan Kontrak
							</legend>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<a onclick="makeContract('<?php echo $data->id ?>','<?php echo $data->contract_date ?>','<?php echo $data->testing_start ?>','<?php echo $data->testing_end ?>')"> Buatkan File Kontrak</a>
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
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							<div class="modal fade" id="myModalketerangan_contract" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Tinjauan Kontrak Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_contract"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif

				@if($adminRole->spb_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-spb')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="SPB"/>
						<fieldset>
							<legend>
								Step SPB
							</legend>
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
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Total Biaya (Biaya Belum Termasuk PPN)*
										</label>
										<input type="text" name="exam_price" id="exam_price" class="form-control" placeholder="Total Biaya" value="{{ $data->price }}" readonly required>
									</div>
								</div>
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							<div class="modal fade" id="myModalketerangan_spb" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step SPB Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_spb"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif

				@if($adminRole->payment_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'id' => 'form-pembayaran')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Pembayaran"/>
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
								</div>
									<input type="hidden" name="spb_number" id="spb_number" value="{{ $data->spb_number }}">
									<input type="hidden" name="exam_price" id="exam_price" value="{{ $data->price }}">
									<input type="hidden" name="spb_date" id="spb_date" value="{{ $data->spb_date }}">
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							<div class="modal fade" id="myModalketerangan_pembayaran" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Pembayaran Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_pembayaran"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif

				@if($adminRole->spk_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'id' => 'form-spk')) !!}
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
													<em class="glyphicon glyphicon-calendar"></em>
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
										@if($data->spk_code == '') 
											<input type="text" name="spk_code" id="spk_code" class="form-control" placeholder="Nomor SPK" value="{{ $gen_spk_code }}" required>
										@else
											<input type="text" name="spk_code" id="spk_code" class="form-control" placeholder="Nomor SPK" value="{{ $data->spk_code }}" required>
										@endif
										<button type="button" class="btn btn-wide btn-green btn-squared pull-right" onclick="generateSPKCode('<?php echo $data->examinationLab->lab_code ?>','<?php echo $data->examinationType->name ?>','<?php echo date('Y') ?>')">
											Generate
										</button>
									</div>
								</div>
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							<div class="modal fade" id="myModalketerangan_spk" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Pembuatan SPK Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_spk"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption>,/caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif

				@if($adminRole->examination_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'id' => 'form-uji')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Pelaksanaan Uji"/>
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
											<input type="text" name="examination_date" class="form-control" value="{{ $data->examination_date }}" required/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
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
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							<div class="modal fade" id="myModalketerangan_form_uji" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Pelaksanaan Uji Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_form_uji"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif

				@if($adminRole->resume_status == 1)
					{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-lap-uji')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Laporan Uji"/>
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
										<input type="file" name="lap_uji_file" id="lap_uji_file" class="form-control" accept="application/pdf, image/*">
									</div>
									<div class="form-group">
										<?php $lap_uji_attach = ''; ?>
										@foreach($data->media as $item)
											@if($item->name == 'Laporan Uji' && $item->attachment != '')
												<?php $lap_uji_attach = $item->attachment; ?>
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Laporan Uji')}}"> Download Laporan Uji "<?php echo $lap_uji_attach; ?>"</a>
											@endif
										@endforeach
										<input type="hidden" id="lap_uji_name" value="<?php echo $lap_uji_attach; ?>">
									</div>
								</div>
								@endif
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal *
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" name="resume_date" class="form-control" value="{{ $data->resume_date }}" required/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
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
								<div class="col-md-12">
									<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
										Update
									</button>
								</div>
							</div>
							<div class="modal fade" id="myModalketerangan_lap_uji" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Laporan Uji Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<div class="form-group">
															<label for="keterangan">Keterangan:</label>
															<textarea class="form-control" rows="5" name="keterangan" id="keterangan_lap_uji"></textarea>
														</div>
													</th>
												</tr>
											</table>
										</div><!-- /.modal-content -->
										<div class="modal-footer">
											<table width=100%><caption></caption>
												<tr>
													<th scope="col">
														<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
													</th>
												</tr>
											</table>
										</div>
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
							</div>
						</fieldset>
					{!! Form::close() !!}
				@endif
				
				@if($data->examination_type_id !='2' && $data->examination_type_id !='3')
					@if($adminRole->qa_status == 1)
						{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'id' => 'form-sidang')) !!}
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
												@if($data->qa_passed)
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
														<input type="radio" value="0" name="passed" id="notPassed">
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
												<input type="text" name="qa_date" class="form-control" value="{{ $data->qa_date }}" required/>
												<span class="input-group-btn">
													<button type="button" class="btn btn-default">
														<em class="glyphicon glyphicon-calendar"></em>
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
									<div class="col-md-12">
										<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
											Update
										</button>
									</div>
								</div>
								<div class="modal fade" id="myModalketerangan_sidang_qa" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Sidang QA Not Completed, Mohon Berikan Keterangan!</h4>
											</div>
											
											<div class="modal-body">
												<table width=100%><caption></caption>
													<tr>
														<th scope="col">
															<div class="form-group">
																<label for="keterangan">Keterangan:</label>
																<textarea class="form-control" rows="5" name="keterangan" id="keterangan_sidang_qa"></textarea>
															</div>
														</th>
													</tr>
												</table>
											</div><!-- /.modal-content -->
											<div class="modal-footer">
												<table width=100%><caption></caption>
													<tr>
														<th scope="col">
															<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
														</th>
													</tr>
												</table>
											</div>
										</div><!-- /.modal-dialog -->
									</div><!-- /.modal -->
								</div>
							</fieldset>
						{!! Form::close() !!}
					@endif

					@if($adminRole->certificate_status == 1)
						{!! Form::open(array('url' => 'admin/myexam/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-sertifikat')) !!}
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
												<input type="file" name="certificate_file" class="form-control" accept="application/pdf, image/*"/>
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
													<input type="text" name="certificate_date" class="form-control" value="{{ $data->certificate_date }}"/>
													<span class="input-group-btn">
														<button type="button" class="btn btn-default">
															<em class="glyphicon glyphicon-calendar"></em>
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
													<input type="text" name="valid_from" id="valid_from" class="form-control" value="{{ $data->device->valid_from }}"/>
													<span class="input-group-btn">
														<button type="button" class="btn btn-default">
															<em class="glyphicon glyphicon-calendar"></em>
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
													<input type="text" name="valid_thru" id="valid_thru" class="form-control" value="{{ $data->device->valid_thru }}"/>
													<span class="input-group-btn">
														<button type="button" class="btn btn-default">
															<em class="glyphicon glyphicon-calendar"></em>
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
									<div class="col-md-12">
										<button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
											Update
										</button>
									</div>
								</div>
								<div class="modal fade" id="myModalketerangan_sertifikat" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Penerbitan Sertifikat Not Completed, Mohon Berikan Keterangan!</h4>
											</div>
											
											<div class="modal-body">
												<table width=100%><caption></caption>
													<tr>
														<th scope="col">
															<div class="form-group">
																<label for="keterangan">Keterangan:</label>
																<textarea class="form-control" rows="5" name="keterangan" id="keterangan_sertifikat"></textarea>
															</div>
														</th>
													</tr>
												</table>
											</div><!-- /.modal-content -->
											<div class="modal-footer">
												<table width=100%><caption></caption>
													<tr>
														<th scope="col">
															<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
														</th>
													</tr>
												</table>
											</div>
										</div><!-- /.modal-dialog -->
									</div><!-- /.modal -->
								</div>
							</fieldset>
						{!! Form::close() !!}
					@endif
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
	
	function makeContract(a,b,c,d){
		$('#contract-modal-content').modal('show');
		$('#hide_id_exam').val(a);
		$('#contract-modal-content').on('shown.bs.modal', function() {
			$('#contract_date').val(b);
			$('#testing_start').val(c);
			$('#testing_end').val(d);
			// $("#cust_test_date").focus();
		})
	}
	
	function makeSPB(a,b,c){
		var APP_URL = {!! json_encode(url('/admin/myexam/generateSPB')) !!};		
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
	
	$('.btn-tgl-kontrak').click(function () {
		var a = document.getElementById('hide_id_exam').value;
		var b = document.getElementById('contract_date').value;
		var c = document.getElementById('testing_start').value;
		var d = document.getElementById('testing_end').value;
		
		var APP_URL = {!! json_encode(url('/cetakKontrak')) !!};
		
		$.ajax({
			type: "POST",
			url : "tanggalkontrak",
			data: {'_token':"{{ csrf_token() }}", 'hide_id_exam':a, 'contract_date':b, 'testing_start':c, 'testing_end':d},
			beforeSend: function(){
				
			},
			success: function(response){
				if(response == 1){
					$('#contract-modal-content').modal('hide');
					window.open(APP_URL);
					location.reload();
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
		var $inputs = $('#form-function-test :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['function_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_function').modal('show');
			return false;
		}else{
			$('#myModalketerangan_function').modal('hide');
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
		var $inputs = $('#form-pembayaran :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['payment_status'] == '-1' && keterangan == ''){
			$('#myModalketerangan_pembayaran').modal('show');
			return false;
		}else{
			$('#myModalketerangan_pembayaran').modal('hide');
		}
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
</script>
@if($data->examination_type_id =='2' || $data->examination_type_id =='3')
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
@if($data->examination_type_id !='2' && $data->examination_type_id !='3')
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