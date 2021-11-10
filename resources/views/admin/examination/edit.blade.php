@extends('layouts.app')

@section('content')

@php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
	$type_of_test = $data['is_loc_test'] ? "Technical Meeting" : "Uji Fungsi";
	$type_of_test_result = $data['is_loc_test'] ? "Sesuai" : "Memenuhi";
	$url_generate_test = $data['is_loc_test'] ? "/cetakTechnicalMeeting/" : "/cetakUjiFungsi/" ;
@endphp

<div id="modal_status_uf" class="modal fade" role="dialog"  tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Status Uji Fungsi</h4>
          </div>
          <div class="modal-body">
               	<div class="row">
				   <div class="col-md-12">
						<div class="form-group">
							<h4 id='h2_modal_status_uf'> </h4>
						</div>
					</div>
                </div>
          </div>
          <div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="button" class="btn btn-danger" style="width:100%" data-dismiss="modal"><em class="fa fa-check-square-o"></em> OK</button>
						</th>
					</tr>
				</table>
			</div>
        </div>

      </div>
    </div> 

<input type="hide" id="hide_exam_id" name="hide_exam_id">
<div class="modal fade" id="myModal_reset_uf" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Uji Fungsi Akan Direset, Mohon Berikan Keterangan!</h4>
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
							<button type="button" id="btn-modal-reset_uf" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
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
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Silakan Isi Data-data Berikut !</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
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
											<em class="glyphicon glyphicon-calendar"></em>
										</button>
									</span>
								</p>
							</div>
						</th>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
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
											$data->certificate_status == '1' && $data->qa_passed == 1)
												<a href="#step-10" class="done">
											@elseif($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1' && $data->examination_status == '1' && $data->resume_status == '1' && $data->qa_status == '1' &&
											$data->certificate_status == '1' && $data->qa_passed == -1)
												<a href="#step-10" class="done done-failed">
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
											@if(strpos($data->keterangan, 'qa_date') !== false)
												@php $data_ket = explode("qa_date",$data->keterangan); @endphp
												<tr>
													<th colspan="3" class="left" scope="col"><p style="color:red">Perangkat ini sudah pernah diuji, dengan status "Tidak Lulus Uji" berdasarkan keputusan Sidang QA tanggal {{ $data_ket[1] }}</p></th>
												</tr>
											@endif
											@if($data->spb_number && $data->payment_status == 0 && $data->spb_date < date('Y-m-d', strtotime('-3 month')))
												<tr>
													<th colspan="3" class="left" scope="col"><p style="color:red">SPB sudah melebihi 3 bulan batas pembayaran.</p></th>
												</tr>
											@endif
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
													<td>Referensi Uji:</td>
													<td>
														{{ $data->device->test_reference }}
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
													<td>Nomor Registrasi:</td>
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
												<tr>
													<td>Tanggal Uji Fungsi:</td>
													<td>
														@if($data->function_test_date_approval)
															@if($data->function_date != null)
																{{ $data->function_date }}
															@else
																{{ $data->deal_test_date }}
															@endif
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
				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->registration_status)
					<!-- Datasheet, Prinsipal, PLG_ID & NIB -->
					@php 
						$datasheet = 0; $datasheet_file = ''; $datasheet_link = '';
						$prinsipal = 0; $prinsipal_file = ''; $prinsipal_link = '';
						$sp3 = 0; $sp3_file = ''; $sp3_link = '';
						
					@endphp
					@foreach($data->media as $item)
						@if($item->name == 'File Lainnya')
							@php 
								$datasheet = 1;
								$datasheet_file = $item->attachment;
								$datasheet_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

						@if($item->name == 'Surat Dukungan Prinsipal')
							@php 
								$prinsipal = 1;
								$prinsipal_file = $item->attachment;
								$prinsipal_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

						@if($item->name == 'SP3')
							@php 
								$sp3 = 1;
								$sp3_file = $item->attachment;
								$sp3_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

					@endforeach
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
										Form Uji
									</label>
									<label>
										: <a href="{{URL::to('cetakPengujian/'.$data->id)}}" target="_blank"> Download</a>
									</label>
									<br>
									<label>
										Datasheet
									</label>
									@if($datasheet_file != null)
										<label>
											: <a href="{{ $datasheet_link }}"> Download</a>
										</label>
									@else
										<label>
											: (Kosong)
										</label>
									@endif
									@if($prinsipal)
										<br>
										<label>
											Prinsipal
										</label>
										@if($prinsipal_file != null)
											<label>
												: <a href="{{ $prinsipal_link }}"> Download</a>
											</label>
										@else
											<label>
												: (Kosong)
											</label>
										@endif
									@endif
									@if($sp3)
										<br>
										<label>
											PLG_ID & NIB
										</label>
										@if($sp3_file != null)
											<label>
												: <a href="{{ $sp3_link }}"> Download</a>
											</label>
										@else
											<label>
												: (Kosong)
											</label>
										@endif
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Laboratorium Pengujian *
									</label>
									<select id="examination_lab_id" name="examination_lab_id" class="cs-select cs-skin-elastic">
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
										Lokasi Pengujian *
									</label>
									<input id="hide_is_loc_test" type="hidden" value="{{ $data->is_loc_test }}">
									<select name="is_loc_test" class="cs-select cs-skin-elastic" required>
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
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption></caption>
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
				@else
					<!-- Datasheet, Prinsipal, PLG_ID & NIB -->
					@php 
						$datasheet = 0; $datasheet_file = ''; $datasheet_link = '';
						$prinsipal = 0; $prinsipal_file = ''; $prinsipal_link = '';
						$sp3 = 0; $sp3_file = ''; $sp3_link = '';
						
					@endphp
					@foreach($data->media as $item)
						@if($item->name == 'File Lainnya')
							@php 
								$datasheet = 1;
								$datasheet_file = $item->attachment;
								$datasheet_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

						@if($item->name == 'Surat Dukungan Prinsipal')
							@php 
								$prinsipal = 1;
								$prinsipal_file = $item->attachment;
								$prinsipal_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

						@if($item->name == 'SP3')
							@php 
								$sp3 = 1;
								$sp3_file = $item->attachment;
								$sp3_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

					@endforeach
    				<fieldset>
						<legend>
							Step Registrasi
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Form Uji
									</label>
									<label>
										: <a href="{{URL::to('cetakPengujian/'.$data->id)}}" target="_blank"> Download</a>
									</label>
									<br>
									<label>
										Datasheet
									</label>
									@if($datasheet_file != null)
										<label>
											: <a href="{{ $datasheet_link }}"> Download</a>
										</label>
									@else
										<label>
											: (Kosong)
										</label>
									@endif
									@if($prinsipal)
										<br>
										<label>
											Prinsipal
										</label>
										@if($prinsipal_file != null)
											<label>
												: <a href="{{ $prinsipal_link }}"> Download</a>
											</label>
										@else
											<label>
												: (Kosong)
											</label>
										@endif
									@endif
									@if($sp3)
										<br>
										<label>
											PLG_ID & NIB
										</label>
										@if($sp3_file != null)
											<label>
												: <a href="{{ $sp3_link }}"> Download</a>
											</label>
										@else
											<label>
												: (Kosong)
											</label>
										@endif
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Laboratorium Pengujian *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->examination_lab_id != null)
											@foreach($labs as $item)
												@if($item->id == $data->examination_lab_id)
													<option value="{{$item->id}}" selected>{{$item->name}}</option>
												@endif
											@endforeach
										@else
											<option value="" disabled selected>Select...</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Lokasi Pengujian *
									</label>
									<select class="cs-select cs-skin-elastic" required>
										@if($data->is_loc_test == 1)
											<option value="1" selected>Uji Lokasi</option>
										@else
											<option value="0" selected>Uji Lab Telkom</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->registration_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->registration_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif
				@else
					<!-- Datasheet, Prinsipal, PLG_ID & NIB -->
					@php 
						$datasheet = 0; $datasheet_file = ''; $datasheet_link = '';
						$prinsipal = 0; $prinsipal_file = ''; $prinsipal_link = '';
						$sp3 = 0; $sp3_file = ''; $sp3_link = '';
						
					@endphp
					@foreach($data->media as $item)
						@if($item->name == 'File Lainnya')
							@php 
								$datasheet = 1;
								$datasheet_file = $item->attachment;
								$datasheet_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

						@if($item->name == 'Surat Dukungan Prinsipal')
							@php 
								$prinsipal = 1;
								$prinsipal_file = $item->attachment;
								$prinsipal_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

						@if($item->name == 'SP3')
							@php 
								$sp3 = 1;
								$sp3_file = $item->attachment;
								$sp3_link = URL::to('/admin/examination/media/download/'.$data->id.'/'.$item->name);
							@endphp
						@endif

					@endforeach
    				<fieldset>
						<legend>
							Step Registrasi
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Form Uji
									</label>
									<label>
										: <a href="{{URL::to('cetakPengujian/'.$data->id)}}" target="_blank"> Download</a>
									</label>
									<br>
									<label>
										Datasheet
									</label>
									@if($datasheet_file != null)
										<label>
											: <a href="{{ $datasheet_link }}"> Download</a>
										</label>
									@else
										<label>
											: (Kosong)
										</label>
									@endif
									@if($prinsipal)
										<br>
										<label>
											Prinsipal
										</label>
										@if($prinsipal_file != null)
											<label>
												: <a href="{{ $prinsipal_link }}"> Download</a>
											</label>
										@else
											<label>
												: (Kosong)
											</label>
										@endif
									@endif
									@if($sp3)
										<br>
										<label>
											PLG_ID & NIB
										</label>
										@if($sp3_file != null)
											<label>
												: <a href="{{ $sp3_link }}"> Download</a>
											</label>
										@else
											<label>
												: (Kosong)
											</label>
										@endif
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Laboratorium Pengujian *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->examination_lab_id != null)
											@foreach($labs as $item)
												@if($item->id == $data->examination_lab_id)
													<option value="{{$item->id}}" selected>{{$item->name}}</option>
												@endif
											@endforeach
										@else
											<option value="" disabled selected>Select...</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Lokasi Pengujian *
									</label>
									<select class="cs-select cs-skin-elastic" required>
										@if($data->is_loc_test == 1)
											<option value="1" selected>Uji Lokasi</option>
										@else
											<option value="0" selected>Uji Lab Telkom</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->registration_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->registration_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif
				
				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->function_status)
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-function-test')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Uji Fungsi"/>
    				<fieldset>
						<legend>
							Step Uji Fungsi
						</legend>
						@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
							<a class="btn btn-wide btn-primary" style="margin-bottom:10px" onclick="resetUF('{{ $data->id }}','{{ $data->function_test_TE_temp }}','{{ $data->function_test_date_temp }}')">Reset Uji Fungsi</a>
							@if($data->function_test_TE_temp)
								<a class="btn btn-wide btn-primary" style="margin-bottom:10px" onclick="ijinkanUF('{{ $data->id }}')">Ijinkan Kembali Uji Fungsi</a>
							@endif
						@endif
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<h4 style="display:inline">Pengajuan {{$type_of_test}}</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label style="font-weight: bold;">
										Tanggal {{$type_of_test}}
									</label>
									@if($data->function_test_date_approval == 1)
										<label>
											:  
											@if($data->function_date != null)
												@php echo $data->function_date; @endphp
											@else
												@php echo $data->deal_test_date; @endphp
											@endif
										</label>
									@else
										<label>
											: -
										</label>
									@endif
									<br>
									<label style="font-weight: bold;">
										Engineer
									</label>
									@if($data->function_test_date_approval == 1)
										<label>
											: {{ $data->function_test_PIC }}
										</label>
									@else
										<label>
											: -
										</label>
									@endif
								</div>
							</div>
							<input type="hidden" id="hide_approval_form-function-test" value="{{ $data->function_test_date_approval }}">
							<div class="col-md-12">
								<div class="form-group">
									<label class="pull-right">
										<a class="history-tanggal-uf-button" data-toggle="collapse" href="#collapse_history_tanggal_uf">Lihat Detail</a>
									</label>
								</div>
							</div>
							<div class="col-md-12">
								<div id="collapse_history_tanggal_uf" class="form-group collapse">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat Pengajuan Tanggal {{$type_of_test}}</th>
											</tr>
											<tr>
												<th>Pengajuan Tanggal Customer</th>
												<th>Jadwal dari Test Engineer</th>
												<th>Pengajuan Ulang dari Customer</th>
												<th>Jadwal dari Test Engineer</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td> @php echo $data->cust_test_date; @endphp </td>
												<td> @php echo $data->deal_test_date; @endphp </td>
												<td> @php echo $data->urel_test_date; @endphp </td>
												<td> @php echo $data->function_date; @endphp </td>
											</tr>
										</tbody>
									</table>
									@if($data->function_test_reason != '' && $data->function_test_date_approval != 1)
									<div class="form-group">
										<label for="alasan">Alasan Jadwal Ulang:</label>
										<textarea class="form-control" rows="2" name="reason" id="reason" readonly>{{ $data->function_test_reason }}</textarea>	
									</div>
									@endif
								</div>
							</div>
							@if (!$data['is_loc_test'])
								<div class="col-md-12">
									<div class="form-group">
										<h4 style="display:inline">Lokasi Barang</h4>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="masukkan_barang" name="masukkan_barang" class="cs-select cs-skin-elastic">
											@if(count($data->equipment)==0)
												<option value="1" selected>Customer (Applicant)</option>
											@else
												<option value="2" selected>URel (Store)</option>
											@endif
										</select>
									</div>
								</div>
							
								@if($data->function_date != null)
									@php $in_equip_date = $data->function_date; @endphp
								@elseif($data->function_date == null && $data->urel_test_date != null)
									@php $in_equip_date = $data->urel_test_date; @endphp
								@elseif($data->urel_test_date == null && $data->deal_test_date != null)
									@php $in_equip_date = $data->deal_test_date; @endphp
								@else
									@php $in_equip_date = $data->cust_test_date; @endphp
								@endif

								<input type="hidden" id="hide_count_equipment_form-function-test" value="{{ count($data->equipment) }}">
									
								@if(count($data->equipment)==0 && $data->function_test_date_approval == 1)
								<div class="col-md-12">
									<div class="form-group">
										<a onclick="masukkanBarang('{{ $data->id }}','{{ $in_equip_date }}')"> Perbarui Lokasi Barang</a>
									</div>									
								</div>									
								@endif
							@endif

							<input type="hidden" id="hide_test_TE_form-function-test" value="{{ $data->function_test_TE }}">
							@if($data->function_test_TE != 0 && $data->function_test_date_approval == 1)
								<div class="col-md-12">
									<div class="form-group">
										<h4 style="display:inline">
											Laporan {{$type_of_test}}
										</h4>
									</div>
									<div class="form-group">
										<label style="font-weight: bold;">
											Hasil
										</label>
										<label>
											:  
											@if($data->function_test_TE == 1)
												{{$type_of_test_result}}
											@elseif($data->function_test_TE == 2)
												Tidak {{$type_of_test_result}}
											@elseif($data->function_test_TE == 3)
												dll
											@else
												Tidak Ada
											@endif
										</label>
									</div>
									<div class="form-group">
										<label for="catatan">Catatan :</label>
										<textarea class="form-control" rows="5" name="catatan" id="catatan" readonly disabled>{{ $data->catatan }}</textarea>
									</div>
									<div class="form-group">
										<a href="{{URL::to($url_generate_test.$data->id)}}" target="_blank"> Buatkan Laporan {{$type_of_test}}</a>
									</div>
									@if (!$data['is_loc_test'])
									@if($data->function_test_TE == 1 && $data->function_test_date_approval == 1)
										<div class="form-group">
											<a href="{{URL::to('/cetakFormBarang/'.$data->id)}}" target="_blank"> Buatkan Bukti Penerimaan Perangkat</a>
										</div>
									@endif
									@endif
								</div>
							@endif
							@if(count($data->history_uf)>0)
								<div class="col-md-12">
									<div class="form-group">
										<label class="pull-right">
											<a class="history-uf-button" data-toggle="collapse" href="#collapse_history_uf">Lihat Riwayat Tidak {{$type_of_test_result}}</a>
										</label>
									</div>
								</div>
							@endif
							<div class="col-md-12">
								<div id="collapse_history_uf" class="form-group collapse">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat {{$type_of_test}}</th>
											</tr>
											<tr>
												<th style="width: 17%;">Uji Fungsi ke-</th>
												<th style="width: 17%;">Tanggal</th>
												<th style="width: 30%;">Engineer</th>
												<th>Catatan</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $count = 0; @endphp
											@foreach($data->history_uf as $item)
												@if($item->function_test_TE == 2)
													<tr>
														<td> {{ $no++ }}</td>
														<td> {{ $item->function_test_date }}</td>
														<td> {{ $item->function_test_PIC}}</td>
														<td> {{ $item->catatan }}</td>
													</tr>
													@php $count++; @endphp
												@endif
											@endforeach
											@if($count == 0)
												<tr style="text-align: center;"><td colspan="4">Data Not Found</td></tr>
											@endif
										</tbody>
									</table>
								</div>							
							</div>
							@if($data->function_test_TE != 0 && $data->function_test_date_approval == 1)
							<div class="col-md-12">
								<div class="form-group">
									<h4 style="display:inline">Dokumen</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Laporan {{$type_of_test}} : *
									</label>
									<input type="file" name="function_file" id="function_file" class="form-control" accept="application/pdf"/>
								</div>
								<div class="form-group">
									@php $function_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Laporan Hasil '.$type_of_test && $item->attachment != '')
											@php $function_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Laporan Hasil '.$type_of_test)}}"> Download Hasil {{$type_of_test}} "@php echo $function_attach; @endphp"</a>
										@endif
									@endforeach
									<input type="hidden" id="function_name" value="@php echo $function_attach; @endphp">
								</div>
								@if (!$data['is_loc_test'])
								@if($data->function_test_TE == 1 && $data->function_test_date_approval == 1)
									<div class="form-group">
										<label>
											Bukti Penerimaan Perangkat : *
										</label>
										<input type="file" name="barang_file" id="barang_file" class="form-control" accept="application/pdf"/>
									</div>
									<div class="form-group">
										@php $barang_attach = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji1' && $item->attachment != '')
												@php $barang_attach = $item->attachment; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji1')}}"> Download Bukti Penerimaan & Pengeluaran Perangkat Uji "@php echo $barang_attach; @endphp"</a>
											@endif
										@endforeach
										<input type="hidden" id="barang_name" value="@php echo $barang_attach; @endphp">
									</div>
								@endif
								@endif
							</div>
							@endif
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
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Uji Fungsi Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption></caption>
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
				@else
					<fieldset>
						<legend>
							Step Uji Fungsi
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<h4 style="display:inline">Pengajuan {{$type_of_test}}</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label style="font-weight: bold;">
										Tanggal {{$type_of_test}}
									</label>
									@if($data->function_test_date_approval == 1)
										<label>
											:  
											@if($data->function_date != null)
												@php echo $data->function_date; @endphp
											@else
												@php echo $data->deal_test_date; @endphp
											@endif
										</label>
									@else
										<label>
											: -
										</label>
									@endif
									<br>
									<label style="font-weight: bold;">
										Engineer
									</label>
									@if($data->function_test_date_approval == 1)
										<label>
											: {{ $data->function_test_PIC }}
										</label>
									@else
										<label>
											: -
										</label>
									@endif
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label class="pull-right">
										<a class="history-tanggal-uf-button" data-toggle="collapse" href="#collapse_history_tanggal_uf">Lihat Detail</a>
									</label>
								</div>
							</div>
							<div class="col-md-12">
								<div id="collapse_history_tanggal_uf" class="form-group collapse">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat Pengajuan Tanggal {{$type_of_test}}</th>
											</tr>
											<tr>
												<th>Pengajuan Tanggal Customer</th>
												<th>Jadwal dari Test Engineer</th>
												<th>Pengajuan Ulang dari Customer</th>
												<th>Jadwal dari Test Engineer</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td> @php echo $data->cust_test_date; @endphp </td>
												<td> @php echo $data->deal_test_date; @endphp </td>
												<td> @php echo $data->urel_test_date; @endphp </td>
												<td> @php echo $data->function_date; @endphp </td>
											</tr>
										</tbody>
									</table>
									@if($data->function_test_reason != '' && $data->function_test_date_approval != 1)
									<div class="form-group">
										<label for="alasan">Alasan Jadwal Ulang:</label>
										<textarea class="form-control" rows="2" name="reason" id="reason" readonly>{{ $data->function_test_reason }}</textarea>	
									</div>
									@endif
								</div>
							</div>
							@if (!$data['is_loc_test'])
								<div class="col-md-12">
									<div class="form-group">
										<h4 style="display:inline">Lokasi Barang</h4>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="masukkan_barang" name="masukkan_barang" class="cs-select cs-skin-elastic">
											@if(count($data->equipment)==0)
												<option value="1" selected>Customer (Applicant)</option>
											@else
												<option value="2" selected>URel (Store)</option>
											@endif
										</select>
									</div>
								</div>
							
								@if($data->function_date != null)
									@php $in_equip_date = $data->function_date; @endphp
								@elseif($data->function_date == null && $data->urel_test_date != null)
									@php $in_equip_date = $data->urel_test_date; @endphp
								@elseif($data->urel_test_date == null && $data->deal_test_date != null)
									@php $in_equip_date = $data->deal_test_date; @endphp
								@else
									@php $in_equip_date = $data->cust_test_date; @endphp
								@endif

							@endif
							@if($data->function_test_TE != 0 && $data->function_test_date_approval == 1)
								<div class="col-md-12">
									<div class="form-group">
										<h4 style="display:inline">
											Laporan {{$type_of_test}}
										</h4>
									</div>
									<div class="form-group">
										<label style="font-weight: bold;">
											Hasil
										</label>
										<label>
											:  
											@if($data->function_test_TE == 1)
												{{$type_of_test_result}}
											@elseif($data->function_test_TE == 2)
												Tidak {{$type_of_test_result}}
											@elseif($data->function_test_TE == 3)
												dll
											@else
												Tidak Ada
											@endif
										</label>
									</div>
									<div class="form-group">
										<label for="catatan">Catatan :</label>
										<textarea class="form-control" rows="5" name="catatan" id="catatan" readonly disabled>{{ $data->catatan }}</textarea>
									</div>
									<div class="form-group">
										<a href="{{URL::to($url_generate_test.$data->id)}}" target="_blank"> Buatkan Laporan {{$type_of_test}}</a>
									</div>
									@if (!$data['is_loc_test'])
									@if($data->function_test_TE == 1 && $data->function_test_date_approval == 1)
										<div class="form-group">
											<a href="{{URL::to('/cetakFormBarang/'.$data->id)}}" target="_blank"> Buatkan Bukti Penerimaan Perangkat</a>
										</div>
									@endif
									@endif
								</div>
							@endif
							@if(count($data->history_uf)>0)
								<div class="col-md-12">
									<div class="form-group">
										<label class="pull-right">
											<a class="history-uf-button" data-toggle="collapse" href="#collapse_history_uf">Lihat Riwayat Tidak {{$type_of_test_result}}</a>
										</label>
									</div>
								</div>
							@endif
							<div class="col-md-12">
								<div id="collapse_history_uf" class="form-group collapse">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat {{$type_of_test}}</th>
											</tr>
											<tr>
												<th style="width: 17%;">Uji Fungsi ke-</th>
												<th style="width: 17%;">Tanggal</th>
												<th style="width: 30%;">Engineer</th>
												<th>Catatan</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $count = 0; @endphp
											@foreach($data->history_uf as $item)
												@if($item->function_test_TE == 2)
													<tr>
														<td> {{ $no++ }}</td>
														<td> {{ $item->function_test_date }}</td>
														<td> {{ $item->function_test_PIC}}</td>
														<td> {{ $item->catatan }}</td>
													</tr>
													@php $count++; @endphp
												@endif
											@endforeach
											@if($count == 0)
												<tr style="text-align: center;"><td colspan="4">Data Not Found</td></tr>
											@endif
										</tbody>
									</table>
								</div>							
							</div>
							@if($data->function_test_TE != 0 && $data->function_test_date_approval == 1)
							<div class="col-md-12">
								<div class="form-group">
									<h4 style="display:inline">Dokumen</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Laporan {{$type_of_test}} : *
									</label>
								</div>
								<div class="form-group">
									@php $function_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Laporan Hasil '.$type_of_test && $item->attachment != '')
											@php $function_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Laporan Hasil '.$type_of_test)}}"> Download Hasil {{$type_of_test}} "@php echo $function_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
								@if (!$data['is_loc_test'])
								@if($data->function_test_TE == 1 && $data->function_test_date_approval == 1)
									<div class="form-group">
										<label>
											Bukti Penerimaan Perangkat : *
										</label>
									</div>
									<div class="form-group">
										@php $barang_attach = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji1' && $item->attachment != '')
												@php $barang_attach = $item->attachment; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji1')}}"> Download Bukti Penerimaan & Pengeluaran Perangkat Uji "@php echo $barang_attach; @endphp"</a>
											@endif
										@endforeach
									</div>
								@endif
								@endif
							</div>
							@endif
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="function_status" class="cs-select cs-skin-elastic">
										@if($data->function_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->function_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif
				@else
					<fieldset>
						<legend>
							Step Uji Fungsi
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<h4 style="display:inline">Pengajuan {{$type_of_test}}</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label style="font-weight: bold;">
										Tanggal {{$type_of_test}}
									</label>
									@if($data->function_test_date_approval == 1)
										<label>
											:  
											@if($data->function_date != null)
												@php echo $data->function_date; @endphp
											@else
												@php echo $data->deal_test_date; @endphp
											@endif
										</label>
									@else
										<label>
											: -
										</label>
									@endif
									<br>
									<label style="font-weight: bold;">
										Engineer
									</label>
									@if($data->function_test_date_approval == 1)
										<label>
											: {{ $data->function_test_PIC }}
										</label>
									@else
										<label>
											: -
										</label>
									@endif
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label class="pull-right">
										<a class="history-tanggal-uf-button" data-toggle="collapse" href="#collapse_history_tanggal_uf">Lihat Detail</a>
									</label>
								</div>
							</div>
							<div class="col-md-12">
								<div id="collapse_history_tanggal_uf" class="form-group collapse">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat Pengajuan Tanggal {{$type_of_test}}</th>
											</tr>
											<tr>
												<th>Pengajuan Tanggal Customer</th>
												<th>Jadwal dari Test Engineer</th>
												<th>Pengajuan Ulang dari Customer</th>
												<th>Jadwal dari Test Engineer</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td> @php echo $data->cust_test_date; @endphp </td>
												<td> @php echo $data->deal_test_date; @endphp </td>
												<td> @php echo $data->urel_test_date; @endphp </td>
												<td> @php echo $data->function_date; @endphp </td>
											</tr>
										</tbody>
									</table>
									@if($data->function_test_reason != '' && $data->function_test_date_approval != 1)
									<div class="form-group">
										<label for="alasan">Alasan Jadwal Ulang:</label>
										<textarea class="form-control" rows="2" name="reason" id="reason" readonly>{{ $data->function_test_reason }}</textarea>	
									</div>
									@endif
								</div>
							</div>
							@if (!$data['is_loc_test'])
								<div class="col-md-12">
									<div class="form-group">
										<h4 style="display:inline">Lokasi Barang</h4>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="masukkan_barang" name="masukkan_barang" class="cs-select cs-skin-elastic">
											@if(count($data->equipment)==0)
												<option value="1" selected>Customer (Applicant)</option>
											@else
												<option value="2" selected>URel (Store)</option>
											@endif
										</select>
									</div>
								</div>
							
								@if($data->function_date != null)
									@php $in_equip_date = $data->function_date; @endphp
								@elseif($data->function_date == null && $data->urel_test_date != null)
									@php $in_equip_date = $data->urel_test_date; @endphp
								@elseif($data->urel_test_date == null && $data->deal_test_date != null)
									@php $in_equip_date = $data->deal_test_date; @endphp
								@else
									@php $in_equip_date = $data->cust_test_date; @endphp
								@endif

							@endif
							@if($data->function_test_TE != 0 && $data->function_test_date_approval == 1)
								<div class="col-md-12">
									<div class="form-group">
										<h4 style="display:inline">
											Laporan {{$type_of_test}}
										</h4>
									</div>
									<div class="form-group">
										<label style="font-weight: bold;">
											Hasil
										</label>
										<label>
											:  
											@if($data->function_test_TE == 1)
												{{$type_of_test_result}}
											@elseif($data->function_test_TE == 2)
												Tidak {{$type_of_test_result}}
											@elseif($data->function_test_TE == 3)
												dll
											@else
												Tidak Ada
											@endif
										</label>
									</div>
									<div class="form-group">
										<label for="catatan">Catatan :</label>
										<textarea class="form-control" rows="5" name="catatan" id="catatan" readonly disabled>{{ $data->catatan }}</textarea>
									</div>
									<div class="form-group">
										<a href="{{URL::to($url_generate_test.$data->id)}}" target="_blank"> Buatkan Laporan {{$type_of_test}}</a>
									</div>
									@if (!$data['is_loc_test'])
									@if($data->function_test_TE == 1 && $data->function_test_date_approval == 1)
										<div class="form-group">
											<a href="{{URL::to('/cetakFormBarang/'.$data->id)}}" target="_blank"> Buatkan Bukti Penerimaan Perangkat</a>
										</div>
									@endif
									@endif
								</div>
							@endif
							@if(count($data->history_uf)>0)
								<div class="col-md-12">
									<div class="form-group">
										<label class="pull-right">
											<a class="history-uf-button" data-toggle="collapse" href="#collapse_history_uf">Lihat Riwayat Tidak {{$type_of_test_result}}</a>
										</label>
									</div>
								</div>
							@endif
							<div class="col-md-12">
								<div id="collapse_history_uf" class="form-group collapse">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat {{$type_of_test}}</th>
											</tr>
											<tr>
												<th style="width: 17%;">Uji Fungsi ke-</th>
												<th style="width: 17%;">Tanggal</th>
												<th style="width: 30%;">Engineer</th>
												<th>Catatan</th>
											</tr>
										</thead>
										<tbody>
											@php $no = 1; $count = 0; @endphp
											@foreach($data->history_uf as $item)
												@if($item->function_test_TE == 2)
													<tr>
														<td> {{ $no++ }}</td>
														<td> {{ $item->function_test_date }}</td>
														<td> {{ $item->function_test_PIC}}</td>
														<td> {{ $item->catatan }}</td>
													</tr>
													@php $count++; @endphp
												@endif
											@endforeach
											@if($count == 0)
												<tr style="text-align: center;"><td colspan="4">Data Not Found</td></tr>
											@endif
										</tbody>
									</table>
								</div>							
							</div>
							@if($data->function_test_TE != 0 && $data->function_test_date_approval == 1)
							<div class="col-md-12">
								<div class="form-group">
									<h4 style="display:inline">Dokumen</h4>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Laporan {{$type_of_test}} : *
									</label>
								</div>
								<div class="form-group">
									@php $function_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Laporan Hasil '.$type_of_test && $item->attachment != '')
											@php $function_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Laporan Hasil '.$type_of_test)}}"> Download Hasil {{$type_of_test}} "@php echo $function_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
								@if (!$data['is_loc_test'])
								@if($data->function_test_TE == 1 && $data->function_test_date_approval == 1)
									<div class="form-group">
										<label>
											Bukti Penerimaan Perangkat : *
										</label>
									</div>
									<div class="form-group">
										@php $barang_attach = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji1' && $item->attachment != '')
												@php $barang_attach = $item->attachment; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji1')}}"> Download Bukti Penerimaan & Pengeluaran Perangkat Uji "@php echo $barang_attach; @endphp"</a>
											@endif
										@endforeach
									</div>
								@endif
								@endif
							</div>
							@endif
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="function_status" class="cs-select cs-skin-elastic">
										@if($data->function_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->function_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif

				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->contract_status)
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
									<a onclick="makeContract('@php echo $data->id @endphp','@php echo $data->contract_date @endphp')"> Buatkan File Kontrak</a>
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
									@php $contract_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Tinjauan Kontrak' && $item->attachment != '')
											@php $contract_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tinjauan Kontrak')}}"> Download Tinjauan Kontrak "@php echo $contract_attach; @endphp"</a>
										@endif
									@endforeach
									<input type="hidden" id="contract_name" value="@php echo $contract_attach; @endphp">
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
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Tinjauan Kontrak Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption></caption>
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
				@else
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
									@php $contract_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Tinjauan Kontrak' && $item->attachment != '')
											@php $contract_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tinjauan Kontrak')}}"> Download Tinjauan Kontrak "@php echo $contract_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->contract_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->contract_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif
				@else
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
									@php $contract_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Tinjauan Kontrak' && $item->attachment != '')
											@php $contract_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tinjauan Kontrak')}}"> Download Tinjauan Kontrak "@php echo $contract_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->contract_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->contract_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif

				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->spb_status)
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
									<a onclick="makeSPB('@php echo $data->id @endphp','@php echo $data->spb_number @endphp','@php echo $data->spb_date @endphp')"> Buatkan File SPB</a>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										SPB File *
									</label>
									<input type="file" name="spb_file" id="spb_file" class="form-control" accept="application/pdf, image/*">
								</div>
								<div class="form-group">
									@php $spb_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'SPB' && $item->attachment != '')
											@php $spb_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/SPB')}}"> Download SPB "@php echo $spb_attach; @endphp"</a>
										@endif
									@endforeach
									<input type="hidden" id="spb_name" value="@php echo $spb_attach; @endphp">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor SPB *
									</label>
									<input type="text" name="spb_number" id="spb_number" class="form-control" placeholder="Nomor SPB" value="{{ $data->spb_number }}" readonly required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Total Biaya (Rp.) *
									</label>
									<input type="text" name="exam_price" id="exam_price" class="formatPrice form-control" placeholder="Total Biaya" value="{{ $data->price }}" readonly required>
								</div>
							</div>
							<input type="hidden" name="spb_date" id="spb_date" value="{{ $data->spb_date }}">
							<input type="hidden" name="PO_ID" id="PO_ID">
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
							<div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
	                                Update
	                            </button>
	                        </div>
						</div>
					@endif
						<div class="modal fade" id="myModalketerangan_spb" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step SPB Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption></caption>
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
				@else
					<fieldset>
						<legend>
							Step SPB
						</legend>
					@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1')
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										SPB File *
									</label>
								</div>
								<div class="form-group">
									@php $spb_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'SPB' && $item->attachment != '')
											@php $spb_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/SPB')}}"> Download SPB "@php echo $spb_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor SPB *
									</label>
									<input type="text" name="spb_number" id="spb_number" class="form-control" placeholder="Nomor SPB" value="{{ $data->spb_number }}" readonly required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Total Biaya *
									</label>
									<input type="text" class="form-control" placeholder="Total Biaya" value="{{ $data->price }}" readonly required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->spb_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->spb_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					@endif
					</fieldset>
				@endif
				@else
					<fieldset>
						<legend>
							Step SPB
						</legend>
					@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1')
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										SPB File *
									</label>
								</div>
								<div class="form-group">
									@php $spb_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'SPB' && $item->attachment != '')
											@php $spb_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/SPB')}}"> Download SPB "@php echo $spb_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor SPB *
									</label>
									<input type="text" name="spb_number" id="spb_number" class="form-control" placeholder="Nomor SPB" value="{{ $data->spb_number }}" readonly required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Total Biaya *
									</label>
									<input type="text" class="form-control" placeholder="Total Biaya" value="{{ $data->price }}" readonly required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->spb_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->spb_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					@endif
					</fieldset>
				@endif

				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->payment_status)
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-pembayaran')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Pembayaran"/>
    				<fieldset>
						<legend>
							Step Pembayaran
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" style="display: none;">
									<label>
										Bukti Pembayaran
									</label>
										@php $status = 0 @endphp
									@foreach($data->media as $item)
										@if($item->name == 'File Pembayaran' && $item->attachment !='')
											@php $status = 1;  @endphp
										@endif
									@endforeach
									<input type="hidden" id="hide_status_form-pembayaran" value="{{ $status }}">

									@if($status)
										<label>
											: <a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/File Pembayaran')}}"> Download</a>
										</label>
										<div class="form-group">
											<label>
												Banyak Uang (Rp.) *
											</label>
											<input type="text" name="cust_price_payment" id="cust_price_payment" class="formatPrice form-control" placeholder="Banyak Uang" value="{{ $data->cust_price_payment }}" required>
										</div>
									@else
										<label>
											: (Kosong)
										</label>
									@endif
								</div>
								<div class="form-group">
									<label>
										Billing Status
									</label>
										
									@if($data->PO_ID && $data->BILLING_ID == '')
										<label>
											: (Drafted)
										</label>
									@elseif($data->PO_ID && $data->BILLING_ID && $data->payment_status != 1)
										<label>
											: (Created)
										</label>
									@elseif($data->PO_ID && $data->BILLING_ID && $data->payment_status)
										<label>
											: (Paid)
										</label>
									@endif
								</div>
							</div>
							@php $find_kuitansi = 0; $find_faktur = 0; $kuitansi_attach = ''; $faktur_attach = '';@endphp
							@foreach($data->media as $item)
								@if($item->name == 'Kuitansi' && $item->attachment != '' && $find_kuitansi == 0)
									@php $find_kuitansi = 1; $kuitansi_attach = $item->attachment;@endphp
								@endif
								@if($item->name == 'Faktur Pajak' && $item->attachment != '' && $find_faktur == 0)
									@php $find_faktur = 1; $faktur_attach = $item->attachment;@endphp
								@endif
							@endforeach
							<div class="col-md-6">
								@if($find_kuitansi == 1)
									-
								@else
									<a onclick="checkFromTPN('@php echo $data->id @endphp', 'Kuitansi', '/exportpdf')"> Cek Kuitansi</a>
								@endif
							</div>
							<div class="col-md-6">
								<div class="form-group">
									@if($find_faktur == 1)
										-
									@else
										<a onclick="checkFromTPN('@php echo $data->id @endphp', 'Faktur Pajak', '/taxinvoice/pdf')"> Cek Faktur Pajak</a>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kuitansi File
									</label>
									<input type="file" name="kuitansi_file" id="kuitansi_file" class="form-control" accept="application/pdf, image/*">
								</div>
								<div class="form-group">
									@if($kuitansi_attach != '')
										<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/kuitansi')}}"> Download Kuitansi "@php echo $kuitansi_attach; @endphp"</a>
									@endif
									<input type="hidden" id="kuitansi_name" value="@php echo $kuitansi_attach; @endphp">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Faktur Pajak File
									</label>
									<input type="file" name="faktur_file" id="faktur_file" class="form-control" accept="application/pdf">
								</div>
								<div class="form-group">
									@if($faktur_attach != '')
										<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/faktur')}}"> Download Faktur Pajak "@php echo $faktur_attach; @endphp"</a>
									@endif
									<input type="hidden" id="faktur_name" value="@php echo $faktur_attach; @endphp">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select id="payment_status" name="payment_status" class="cs-select cs-skin-elastic">
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
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Pembayaran Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption></caption>
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
				@else
					<fieldset>
						<legend>
							Step Pembayaran
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" style="display: none;">
									<label>
										Bukti Pembayaran
									</label>
										@php $status = 0 @endphp
									@foreach($data->media as $item)
										@if($item->name == 'File Pembayaran' && $item->attachment !='')
											@php $status = 1;  @endphp
										@endif
									@endforeach

									@if($status)
										<label>
											: <a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/File Pembayaran')}}"> Download</a>
										</label>
										<div class="form-group">
											<label>
												Banyak Uang (Rp.) *
											</label>
											<input type="text" class="formatPrice form-control" placeholder="Banyak Uang" value="{{ $data->cust_price_payment }}" readonly required>
										</div>
									@else
										<label>
											: (Kosong)
										</label>
									@endif
								</div>
								<div class="form-group">
									<label>
										Billing Status
									</label>
										
									@if($data->PO_ID && $data->BILLING_ID == '')
										<label>
											: (Drafted)
										</label>
									@elseif($data->PO_ID && $data->BILLING_ID && $data->payment_status != 1)
										<label>
											: (Created)
										</label>
									@elseif($data->PO_ID && $data->BILLING_ID && $data->payment_status)
										<label>
											: (Paid)
										</label>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kuitansi File
									</label>
								</div>
								<div class="form-group">
									@php $kuitansi_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Kuitansi' && $item->attachment != '')
											@php $kuitansi_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/kuitansi')}}"> Download Kuitansi "@php echo $kuitansi_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Faktur Pajak File
									</label>
								</div>
								<div class="form-group">
									@php $faktur_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Faktur Pajak' && $item->attachment != '')
											@php $faktur_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/faktur')}}"> Download Faktur Pajak "@php echo $faktur_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->payment_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->payment_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif
				@else
					<fieldset>
						<legend>
							Step Pembayaran
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" style="display: none;">
									<label>
										Bukti Pembayaran
									</label>
										@php $status = 0 @endphp
									@foreach($data->media as $item)
										@if($item->name == 'File Pembayaran' && $item->attachment !='')
											@php $status = 1;  @endphp
										@endif
									@endforeach

									@if($status)
										<label>
											: <a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/File Pembayaran')}}"> Download</a>
										</label>
										<div class="form-group">
											<label>
												Banyak Uang (Rp.) *
											</label>
											<input type="text" class="formatPrice form-control" placeholder="Banyak Uang" value="{{ $data->cust_price_payment }}" readonly required>
										</div>
									@else
										<label>
											: (Kosong)
										</label>
									@endif
								</div>
								<div class="form-group">
									<label>
										Billing Status
									</label>
										
									@if($data->PO_ID && $data->BILLING_ID == '')
										<label>
											: (Drafted)
										</label>
									@elseif($data->PO_ID && $data->BILLING_ID && $data->payment_status != 1)
										<label>
											: (Created)
										</label>
									@elseif($data->PO_ID && $data->BILLING_ID && $data->payment_status)
										<label>
											: (Paid)
										</label>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kuitansi File
									</label>
								</div>
								<div class="form-group">
									@php $kuitansi_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Kuitansi' && $item->attachment != '')
											@php $kuitansi_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/kuitansi')}}"> Download Kuitansi "@php echo $kuitansi_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Faktur Pajak File
									</label>
								</div>
								<div class="form-group">
									@php $faktur_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Faktur Pajak' && $item->attachment != '')
											@php $faktur_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/faktur')}}"> Download Faktur Pajak "@php echo $faktur_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->payment_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->payment_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif

				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->spk_status)
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
									<p class="input-group input-append" data-date-format="yyyy-mm-dd">
										<input type="text" name="spk_date" class="form-control" value="{{ $data->spk_date }}" readonly required/>
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
										Nomor SPK *
									</label>
										<input type="text" name="spk_code" id="spk_code" class="form-control" placeholder="Nomor SPK" value="{{ $data->spk_code }}" required 
										@php if($data->spk_code != null){echo "readonly";}@endphp
										>
									@if($data->examination_lab_id != null && $data->spk_code == null)
										<button type="button" class="btn btn-wide btn-green btn-squared pull-right" onclick="generateSPKCode('@php echo $data->examinationLab->lab_code @endphp','@php echo $data->examinationType->name @endphp','@php echo date('Y') @endphp')">
											Generate
										</button>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
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
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Pembuatan SPK Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption></caption>
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
				@else
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
									<p class="input-group input-append" data-date-format="yyyy-mm-dd">
										<input type="text" class="form-control" value="{{ $data->spk_date }}" readonly required/>
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
										Nomor SPK *
									</label>
										<input type="text" class="form-control" placeholder="Nomor SPK" value="{{ $data->spk_code }}" required readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->spk_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->spk_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
	                    </div>
					</fieldset>
				@endif
				@else
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
									<p class="input-group input-append" data-date-format="yyyy-mm-dd">
										<input type="text" class="form-control" value="{{ $data->spk_date }}" readonly required/>
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
										Nomor SPK *
									</label>
										<input type="text" class="form-control" placeholder="Nomor SPK" value="{{ $data->spk_code }}" required readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select class="cs-select cs-skin-elastic">
										@if($data->spk_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->spk_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
	                    </div>
					</fieldset>
				@endif

				@if(isset($admin_roles[0]) && $admin_roles[0]->examination_status == 1)
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'id' => 'form-uji')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Pelaksanaan Uji"/>
				@endif
					<fieldset>
						<legend>
							Step Pelaksanaan Uji
						</legend>
						<div class="row">
						@php $reportFinalResultValue = '-'; @endphp
						@if($exam_schedule->code != 'MSTD0059AERR' && $exam_schedule->code != 'MSTD0000AERR')
							@php
								$start_date = new DateTime(date('Y-m-d'));
								$end_date = new DateTime($exam_schedule->data[0]->targetDt);
								$reportFinalResultValue = $exam_schedule->data[0]->reportFinalResultValue;
								if($start_date>$end_date){
									$sisa_spk = 0;
								}else{
									$interval = $start_date->diff($end_date);
									$sisa_spk = $interval->days;
								}
							@endphp
							<div class="col-md-12">
								<div class="form-group">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat Pelaksanaan Uji</th>
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
									<table class="table table-bordered"><caption></caption>
										<tbody>
											<tr>
												<th scope="col">Mulai Uji oleh Test Engineer</th>
												<th scope="col">Selesai Uji oleh Test Engineer</th>
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
										@if(isset($admin_roles[0]) && $admin_roles[0]->examination_status == 1)
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											@if($data_gudang[0]->action_date != NULL AND $data_gudang[0]->action_date != '0000-00-00')
												<input type="text" name="lab_to_gudang_date" class="form-control" value="{{ $data_gudang[0]->action_date }}" required/>
											@else
												<input type="text" name="lab_to_gudang_date" class="form-control" value="" required/>
											@endif
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
										@else
										<p class="input-group input-append" data-date-format="yyyy-mm-dd">
											@if($data_gudang[0]->action_date != NULL AND $data_gudang[0]->action_date != '0000-00-00')
												<input type="text" class="form-control" value="{{ $data_gudang[0]->action_date }}" readonly required/>
											@else
												<input type="text" class="form-control" value="" readonly required/>
											@endif
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
										@endif
									</div>
								</div>
							@endif
						@endif		
							<div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Hasil Pengujian
									</label>
									<label>
										: {{ $reportFinalResultValue }}
									</label>
								</div>
							</div>
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
									@if(isset($admin_roles[0]) && $admin_roles[0]->examination_status == 1)
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
									@else
									<select class="cs-select cs-skin-elastic">
										@if($data->examination_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->examination_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
									@endif
								</div>
							</div>
							@if(isset($admin_roles[0]) && $admin_roles[0]->examination_status == 1)
								@if($data->registration_status == '1' && $data->function_status == '1' && $data->contract_status == '1' && $data->spb_status == '1' && $data->payment_status == '1' && $data->spk_status == '1')
						        <div class="col-md-12">
						            <button type="submit" class="btn btn-wide btn-green btn-squared pull-right">
						                Update
						            </button>
						        </div>
						        @endif
						    @endif
						</div>
						@if(isset($admin_roles[0]) && $admin_roles[0]->examination_status == 1)
						<div class="modal fade" id="myModalketerangan_form_uji" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Pelaksanaan Uji Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption>
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
						@endif
					</fieldset>
				@if(isset($admin_roles[0]) && $admin_roles[0]->examination_status == 1)
				{!! Form::close() !!}
				@endif
				
				@if($data->examination_type_id !='1')
				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->equipment_status)
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-barang')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Edit Lokasi Barang"/>
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
								</div>
								<div class="form-group">
									@php $barang_attach2 = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
											@php $barang_attach2 = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji2')}}"> Download Bukti Pengeluaran Perangkat Uji</a>
										@endif
									@endforeach
									<input type="hidden" id="barang_name2" value="@php echo $barang_attach2; @endphp">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Lokasi Barang Sekarang
									</label>
									<select id="update_barang" name="update_barang" class="cs-select cs-skin-elastic">
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
							<div class="col-md-12">
								<div class="form-group">
									<button class="btn btn-wide btn-green btn-squared pull-right">
										Submit
									</button>
								</div>
							</div>
						</div>
					</fieldset>
				{!! Form::close() !!}
				@else
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
								</div>
								<div class="form-group">
									@php $barang_attach2 = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
											@php $barang_attach2 = $item->attachment; @endphp
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
									<select class="cs-select cs-skin-elastic">
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
							</div>
						</div>
					</fieldset>
				@endif
				@else
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
								</div>
								<div class="form-group">
									@php $barang_attach2 = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
											@php $barang_attach2 = $item->attachment; @endphp
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
									<select class="cs-select cs-skin-elastic">
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
							</div>
						</div>
					</fieldset>
				@endif

				<!-- @if(isset($admin_roles[0]))
				@if($admin_roles[0]->receipt_status)
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
									<a onclick="makeTandaTerima('@php echo $data->id @endphp')"> Buatkan File Tanda Terima</a>
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
									@php $tanda_terima_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
											@php $tanda_terima_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"@php echo $tanda_terima_attach; @endphp"</a>
										@endif
									@endforeach
									<input type="hidden" id="tanda_terima_name" value="@php echo $tanda_terima_attach; @endphp">
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
				@else
					<fieldset>
						<legend>
							Tanda Terima Hasil Pengujian
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File Tanda Terima *
									</label>
								</div>
								<div class="form-group">
									@php $tanda_terima_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
											@php $tanda_terima_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"@php echo $tanda_terima_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
						</div>
					</fieldset>
				@endif
				@else
					<fieldset>
						<legend>
							Tanda Terima Hasil Pengujian
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File Tanda Terima *
									</label>
								</div>
								<div class="form-group">
									@php $tanda_terima_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
											@php $tanda_terima_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"@php echo $tanda_terima_attach; @endphp"</a>
										@endif
									@endforeach
								</div>
							</div>
						</div>
					</fieldset>
				@endif -->
				@endif

				@php $rev_uji = 0; $lap_uji_url = null; $lap_uji_attach = null @endphp
				@if(isset($admin_roles[0]))
				@if($admin_roles[0]->resume_status)
				{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-lap-uji')) !!}
					{!! csrf_field() !!}
					<input type="hidden" name="status" class="form-control" value="Laporan Uji"/>
    				<fieldset>
						<legend>
							Step Laporan Uji
						</legend>
						<div class="row">
							@foreach($data->media as $item)
								@if($item->name == 'Laporan Uji')
									@if($rev_uji == 0)
										@php $lap_uji_url = $item->attachment;$lap_uji_attach = $item->attachment;@endphp
									@endif
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Laporan Hasil Pengujian dari OTR : @if($item->attachment != '') <a href="{{$item->attachment}}"> Download</a> @else Belum Tersedia @endif
											</label>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>
												No. Laporan Uji : @if($item->attachment != '') {{ $item->no }} @else Belum Tersedia @endif
											</label>
										</div>
									</div>
								@endif
								@if($item->name == 'Revisi Laporan Uji' && $rev_uji == 0)
									@php $rev_uji = 1; $lap_uji_url = URL::to('/admin/examination/media/download/'.$item->id); $lap_uji_attach = $item->attachment;@endphp
								@endif
							@endforeach
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
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Laporan yang Diterbitkan
									</label>
									<label>
										: @if($lap_uji_attach)
											<a href="{{ $lap_uji_url }}"> {{ $lap_uji_attach }}</a>
										@else
											Belum Tersedia
										@endif
										<a class="btn btn-primary rev-button" data-toggle="collapse" href="#collapse1"><strong>Revisi</strong></a>
									</label>
									<input type="hidden" name="hide_attachment_form-lap-uji" id="hide_attachment_form-lap-uji" value="{{ $lap_uji_attach }}">
								</div>
							</div>
							
							<div class="col-md-12" class="panel panel-info">
								<div id="collapse1" class="collapse">
									<div class="form-group">
										<label>
											Revisi Laporan Uji*
										</label>
										<input type="file" name="rev_lap_uji_file" id="rev_lap_uji_file" class="form-control" accept="application/pdf, image/*">
									</div>
									<div class="form-group">
										<table class="table table-bordered"><caption></caption>
											<thead>
												<tr>
													<th colspan="5" scope="col">Riwayat Revisi Laporan Uji</th>
												</tr>
											</thead>
											<tbody>
												<tr style="text-align: center;">
													<td>No</td>
													<td>Attachment</td>
													<td>Created By</td>
													<td>Created At</td>
													<td>Action</td>
												</tr>
												@php $no=0;@endphp
												@foreach($data->media as $item)
													@if($item->name == 'Revisi Laporan Uji')
														@php $no++;@endphp
														<tr>
															<td style="text-align: center;">
																<strong>{{ $no }}</strong>
															</td>
															<td>
																<strong><a href="{{URL::to('/admin/examination/media/download/'.$item->id)}}"> {{ $item->attachment }}</a></strong>
															</td>
															<td>
																<strong>{{ $item->user->name }}</strong>
															</td>
															<td>
																<strong>{{ $item->created_at }}</strong>
															</td>
															<td style="text-align: center;">
																<strong> <a class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Destroy" onclick="delete_rev_lap_uji_file('{{ $item->id }}')"><em class="fa fa-trash"></em></a> </strong>
															</td>
														</tr>
													@endif
												@endforeach
												@if($no == 0)
													<tr><td colspan="5" style="text-align: center;"> Data Not Found </td></tr>
												@endif
											</tbody>
										</table>
									</div>
								</div>
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
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Laporan Uji Not Completed, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;"><caption></caption>
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
										<table style="width: 100%;"><caption></caption>
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
				@else
					<fieldset>
						<legend>
							Step Laporan Uji
						</legend>
						<div class="row">
							@foreach($data->media as $item)
								@if($item->name == 'Laporan Uji')
									@php $lap_uji_url = $item->attachment;$lap_uji_attach = $item->attachment;@endphp
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Laporan Hasil Pengujian dari OTR : @if($item->attachment != '') <a href="{{$item->attachment}}"> Download</a> @else Belum Tersedia @endif
											</label>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>
												No. Laporan Uji : @if($item->attachment != '') {{ $item->no }} @else Belum Tersedia @endif
											</label>
										</div>
									</div>
								@endif
							@endforeach
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
							
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Revisi Laporan Uji*
									</label>
								</div>
								<div class="form-group">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="5" scope="col">Riwayat Revisi Laporan Uji</th>
											</tr> 
										</thead>
										<tbody>
											<tr style="text-align: center;">
												<td>No</td>
												<td>Attachment</td>
												<td>Created By</td>
												<td>Created At</td>
											</tr>
											@php $no=0;@endphp
											@foreach($data->media as $item)
												@if($item->name == 'Revisi Laporan Uji')
													@php $no++;@endphp
													<tr>
														<td style="text-align: center;">
															<strong>{{ $no }}</strong>
														</td>
														<td>
															<strong><a href="{{URL::to('/admin/examination/media/download/'.$item->id)}}"> {{ $item->attachment }}</a></strong>
														</td>
														<td>
															<strong>{{ $item->user->name }}</strong>
														</td>
														<td>
															<strong>{{ $item->created_at }}</strong>
														</td>
													</tr>
												@endif
											@endforeach
											@if($no == 0)
												<tr><td colspan="5" style="text-align: center;"> Data Not Found </td></tr>
											@else
												@php $lap_uji_url = "URL::to('/admin/examination/media/download/'.$item->id)"; @endphp
												@php $lap_uji_attach = $item->attachment; @endphp
											@endif
										</tbody>
									</table>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Laporan yang Diterbitkan
									</label>
									<label>
										: @if($lap_uji_attach)
											<a href="{{ $lap_uji_url }}"> {{ $lap_uji_attach }}</a>
										@else
											Belum Tersedia
										@endif
									</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Laporan Diterbitkan*
									</label>
									<p class="input-group input-append" data-date-format="yyyy-mm-dd">
										<input type="text" class="form-control" value="{{ $data->resume_date }}" readonly required/>
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
									<select class="cs-select cs-skin-elastic">
										@if($data->resume_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->resume_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif
				@else
					<fieldset>
						<legend>
							Step Laporan Uji
						</legend>
						<div class="row">
							@foreach($data->media as $item)
								@if($item->name == 'Laporan Uji')
									@php $lap_uji_url = $item->attachment;$lap_uji_attach = $item->attachment;@endphp
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Laporan Hasil Pengujian dari OTR : @if($item->attachment != '') <a href="{{$item->attachment}}"> Download</a> @else Belum Tersedia @endif
											</label>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>
												No. Laporan Uji : @if($item->attachment != '') {{ $item->no }} @else Belum Tersedia @endif
											</label>
										</div>
									</div>
								@endif
							@endforeach
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
							@if($data->examination_type_id =='2' || $data->examination_type_id =='3')
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Revisi Laporan Uji*
									</label>
								</div>
								<div class="form-group">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="5" scope="col">Riwayat Revisi Laporan Uji</th>
											</tr>
										</thead>
										<tbody>
											<tr style="text-align: center;">
												<td>No</td>
												<td>Attachment</td>
												<td>Created By</td>
												<td>Created At</td>
											</tr>
											@php $no=0;@endphp
											@foreach($data->media as $item)
												@if($item->name == 'Revisi Laporan Uji')
													@php $no++;@endphp
													<tr>
														<td style="text-align: center;">
															<strong>{{ $no }}</strong>
														</td>
														<td>
															<strong><a href="{{URL::to('/admin/examination/media/download/'.$item->id)}}"> {{ $item->attachment }}</a></strong>
														</td>
														<td>
															<strong>{{ $item->user->name }}</strong>
														</td>
														<td>
															<strong>{{ $item->created_at }}</strong>
														</td>
													</tr>
												@endif
											@endforeach
											@if($no == 0)
												<tr><td colspan="5" style="text-align: center;"> Data Not Found </td></tr>
											@else
												@php $lap_uji_url = "URL::to('/admin/examination/media/download/'.$item->id)"; @endphp
												@php $lap_uji_attach = $item->attachment; @endphp
											@endif
										</tbody>
									</table>
								</div>
							</div>
							@endif
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Laporan yang Diterbitkan
									</label>
									<label>
										: @if($lap_uji_attach)
											<a href="{{ $lap_uji_url }}"> {{ $lap_uji_attach }}</a>
										@else
											Belum Tersedia
										@endif
									</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Laporan Diterbitkan*
									</label>
									<p class="input-group input-append" data-date-format="yyyy-mm-dd">
										<input type="text" class="form-control" value="{{ $data->resume_date }}" readonly required/>
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
									<select class="cs-select cs-skin-elastic">
										@if($data->resume_status == 0)
											<option value="0" selected>Choose Status</option>
										@elseif($data->resume_status == 1)
											<option value="1" selected>Completed</option>
										@else
											<option value="-1" selected>Not Completed</option>
										@endif
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				@endif

				@if($data->examination_type_id =='1')
					@if(isset($admin_roles[0]))
					@if($admin_roles[0]->qa_status)
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
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="2" name="passed" id="pending">
													<label for="pending">
														Pending
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
													<input type="radio" value="-1" name="passed" id="notPassed">
													<label for="notPassed">
														Tidak Lulus
													</label>
												</div>
											</div>
											<div class="radio">
												<input type="radio" value="2" name="passed" id="pending" checked>
													<input type="radio" value="2">
													<label for="pending">
														Pending
													</label>
												</div>
											</div>
											@elseif($data->qa_passed == 2)
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
											<div class="radio">
												<input type="radio" value="2" name="passed" id="pending" checked>
													<input type="radio" value="2">
													<label for="pending">
														Pending
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
											<div class="radio">
												<input type="radio" value="2" name="passed" id="pending">
													<input type="radio" value="2">
													<label for="pending">
														Pending
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
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Sidang QA Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table style="width: 100%;"><caption></caption>
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
											<table style="width: 100%;"><caption></caption>
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
					@else
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
														<input type="radio" value="1" checked>
														<label for="passed">
															Lulus
														</label>
												</div>
											</div>
											@elseif($data->qa_passed == -1)
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="-1" checked>
													<label for="notPassed">
														Tidak Lulus
													</label>
												</div>
											</div>
											@elseif($data->qa_passed == 2)
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="2" checked>
													<label for="pending">
														Pending
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
										<p class="input-group input-append" data-date-format="yyyy-mm-dd">
											<input type="text" class="form-control" value="{{ $data->qa_date }}" readonly required/>
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
										<select class="cs-select cs-skin-elastic">
											@if($data->qa_status == 0)
												<option value="0" selected>Choose Status</option>
											@elseif($data->qa_status == 1)
												<option value="1" selected>Completed</option>
											@else
												<option value="-1" selected>Not Completed</option>
											@endif
										</select>
									</div>
								</div>
							</div>
						</fieldset>
					@endif
					@else
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
														<input type="radio" value="1" checked>
														<label for="passed">
															Lulus
														</label>
												</div>
											</div>
											@elseif($data->qa_passed == -1)
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="-1" checked>
													<label for="notPassed">
														Tidak Lulus
													</label>
												</div>
											</div>
											@elseif($data->qa_passed == 2)
											<div class="radio">
												<div class="radio clip-radio radio-primary">
													<input type="radio" value="2" checked>
													<label for="pending">
														Pending
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
										<p class="input-group input-append" data-date-format="yyyy-mm-dd">
											<input type="text" class="form-control" value="{{ $data->qa_date }}" readonly required/>
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
										<select class="cs-select cs-skin-elastic">
											@if($data->qa_status == 0)
												<option value="0" selected>Choose Status</option>
											@elseif($data->qa_status == 1)
												<option value="1" selected>Completed</option>
											@else
												<option value="-1" selected>Not Completed</option>
											@endif
										</select>
									</div>
								</div>
							</div>
						</fieldset>
					@endif
					
					@if(isset($admin_roles[0]))
					@if($admin_roles[0]->equipment_status)
					{!! Form::open(array('url' => 'admin/examination/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-barang')) !!}
						{!! csrf_field() !!}
						<input type="hidden" name="status" class="form-control" value="Edit Lokasi Barang"/>
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
									</div>
									<div class="form-group">
										@php $barang_attach2 = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
												@php $barang_attach2 = $item->attachment; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji2')}}"> Download Bukti Pengeluaran Perangkat Uji</a>
											@endif
										@endforeach
										<input type="hidden" id="barang_name2" value="@php echo $barang_attach2; @endphp">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="form-field-select-2">
											Lokasi Barang Sekarang
										</label>
										<select id="update_barang" name="update_barang" class="cs-select cs-skin-elastic">
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
								<div class="col-md-12">
									<div class="form-group">
										<button class="btn btn-wide btn-green btn-squared pull-right">
											Submit
										</button>
									</div>
								</div>
							</div>
						</fieldset>
					{!! Form::close() !!}
					@else
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
									</div>
									<div class="form-group">
										@php $barang_attach2 = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
												@php $barang_attach2 = $item->attachment; @endphp
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
										<select class="cs-select cs-skin-elastic">
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
								</div>
							</div>
						</fieldset>
					@endif
					@else
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
									</div>
									<div class="form-group">
										@php $barang_attach2 = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji2' && $item->attachment != '')
												@php $barang_attach2 = $item->attachment; @endphp
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
										<select class="cs-select cs-skin-elastic">
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
								</div>
							</div>
						</fieldset>
					@endif

					@if($data->examination_type_id == 1)
						<!-- @if(isset($admin_roles[0]))
						@if($admin_roles[0]->receipt_status)
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
											<a onclick="makeTandaTerima('@php echo $data->id @endphp')"> Buatkan File Tanda Terima</a>
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
											@php $tanda_terima_attach = ''; @endphp
											@foreach($data->media as $item)
												@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
													@php $tanda_terima_attach = $item->attachment; @endphp
													<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"@php echo $tanda_terima_attach; @endphp"</a>
												@endif
											@endforeach
											<input type="hidden" id="tanda_terima_name" value="@php echo $tanda_terima_attach; @endphp">
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
						@else
							<fieldset>
								<legend>
									Tanda Terima Hasil Pengujian
								</legend>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>
												File Tanda Terima *
											</label>
										</div>
										<div class="form-group">
											@php $tanda_terima_attach = ''; @endphp
											@foreach($data->media as $item)
												@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
													@php $tanda_terima_attach = $item->attachment; @endphp
													<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"@php echo $tanda_terima_attach; @endphp"</a>
												@endif
											@endforeach
										</div>
									</div>
								</div>
							</fieldset>
						@endif
						@else
							<fieldset>
								<legend>
									Tanda Terima Hasil Pengujian
								</legend>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>
												File Tanda Terima *
											</label>
										</div>
										<div class="form-group">
											@php $tanda_terima_attach = ''; @endphp
											@foreach($data->media as $item)
												@if($item->name == 'Tanda Terima Hasil Pengujian' && $item->attachment != '')
													@php $tanda_terima_attach = $item->attachment; @endphp
													<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Tanda Terima Hasil Pengujian')}}"> Download Tanda Terima Hasil Pengujian"@php echo $tanda_terima_attach; @endphp"</a>
												@endif
											@endforeach
										</div>
									</div>
								</div>
							</fieldset>
						@endif
					@endif -->

					@if(isset($admin_roles[0]))
					@if($admin_roles[0]->certificate_status)
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
											<input type="file" name="certificate_file" id="certificate_file" class="form-control" accept="application/pdf, image/*">
										</div>
										<div class="form-group">
										@php $certificate_name = ''; @endphp
										@if($data->certificate_status)
											@if($data->device->certificate)
												@php $certificate_name = $data->device->cert_number; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}"> Download Certificate {{ $data->device->cert_number }}</a>
											@endif
										@endif
										<input type="hidden" id="certificate_name" value="@php echo $certificate_name; @endphp">
										</div>
									</div>
									<div class="col-md-7">
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
												Tanggal Mulai Berlaku *
											</label>
											<p id="validFrom" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_from" id="valid_from" class="form-control" value="{{ $data->device->valid_from }}" required />
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
												Tanggal Akhir Berlaku *
											</label>
											<p id="validThru" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_thru" id="valid_thru" class="form-control" value="{{ $data->device->valid_thru }}" required />
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
											<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Step Penerbitan Sertifikat Not Completed, Mohon Berikan Keterangan!</h4>
										</div>
										
										<div class="modal-body">
											<table style="width: 100%;"><caption></caption>
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
											<table style="width: 100%;"><caption></caption>
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
					@else
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
										@php $certificate_name = ''; @endphp
										@if($data->certificate_status)
											@if($data->device->certificate)
												@php $certificate_name = $data->device->cert_number; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}"> Download Certificate {{ $data->device->cert_number }}</a>
											@endif
										@endif
										</div>
									</div>
									<div class="col-md-7">
										<div class="form-group">
											<label>
												Nomor Sertifikat *
											</label>
												<input type="text" class="form-control" placeholder="Nomor Sertifikat" value="{{ $data->device->cert_number }}" readonly required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal Mulai Berlaku *
											</label>
											<p class="input-group input-append" data-date-format="yyyy-mm-dd" />
												<input type="text" class="form-control" value="{{ $data->device->valid_from }}" readonly required/>
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
												Tanggal Akhir Berlaku *
											</label>
											<p class="input-group input-append" data-date-format="yyyy-mm-dd" />
												<input type="text" class="form-control" value="{{ $data->device->valid_thru }}" readonly required/>
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
											@elseif($data->certificate_status == 1)
												<option value="1" selected>Completed</option>
											@else
												<option value="-1" selected>Not Completed</option>
											@endif
										</select>
									</div>
								</div>
							</div>
						</fieldset>
					@endif
					@else
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
										@php $certificate_name = ''; @endphp
										@if($data->certificate_status)
											@if($data->device->certificate)
												@php $certificate_name = $data->device->cert_number; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}"> Download Certificate {{ $data->device->cert_number }}</a>
											@endif
										@endif
										</div>
									</div>
									<div class="col-md-7">
										<div class="form-group">
											<label>
												Nomor Sertifikat *
											</label>
												<input type="text" class="form-control" placeholder="Nomor Sertifikat" value="{{ $data->device->cert_number }}" readonly required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal Mulai Berlaku *
											</label>
											<p class="input-group input-append" data-date-format="yyyy-mm-dd" />
												<input type="text" class="form-control" value="{{ $data->device->valid_from }}" readonly required/>
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
												Tanggal Akhir Berlaku *
											</label>
											<p class="input-group input-append" data-date-format="yyyy-mm-dd" />
												<input type="text" class="form-control" value="{{ $data->device->valid_thru }}" readonly required/>
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
											@elseif($data->certificate_status == 1)
												<option value="1" selected>Completed</option>
											@else
												<option value="-1" selected>Not Completed</option>
											@endif
										</select>
									</div>
								</div>
							</div>
						</fieldset>
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

		$('.formatPrice').keyup(function () {
			this.value = formatPrice(this.value);
		});

		$('.rev-button').click(function () {
			if(this.text == 'Revisi'){
				this.text = 'Tutup';
			}else{
				this.text = 'Revisi';
			}
		});

		$('.history-uf-button').click(function () {
			if(this.text == 'Lihat Riwayat Tidak {{$type_of_test_result}}'){
				this.text = 'Tutup Riwayat Tidak {{$type_of_test_result}}';
			}else{
				this.text = 'Lihat Riwayat Tidak {{$type_of_test_result}}';
			}
		});

		$('.history-tanggal-uf-button').click(function () {
			if(this.text == 'Lihat Detail'){
				this.text = 'Tutup Detail';
			}else{
				this.text = 'Lihat Detail';
			}
		});

	});

	var exam_price = document.getElementById('exam_price');
	if($("#exam_price").length != 0) {
		$('#exam_price').val(formatPrice(exam_price.value));
	}
		
	var cust_price_payment = document.getElementById('cust_price_payment');
	if($("#cust_price_payment").length != 0) {
		$('#cust_price_payment').val(formatPrice(cust_price_payment.value));
	}

	/* Fungsi */
	function formatPrice(angka, prefix)
	{
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}

	function resetUF(a,b,c){
		document.getElementById('hide_exam_id').value = a;
		if(b == 1){
			document.getElementById("h2_modal_status_uf").innerHTML = "Pengujian ini sudah 2 kali gagal melakukan Uji Fungsi. <br> Fungsi Reset dapat digunakan kembali 2 bulan sejak Uji Fungsi terakhir, yaitu "+c;
			$('#modal_status_uf').modal('show');
			$('#myModal_reset_uf').modal('hide');
			return false;
		}else{
			// data-toggle="modal" data-target="#myModal_reset_uf";
			$('#myModal_status_uf').modal('hide');
			$('#myModal_reset_uf').modal('show');
			return false;
		}
	}

	function ijinkanUF(a){
		var baseUrl = "{{URL::to('/')}}";
		if (confirm('Are you sure want to give Function Test access to this data ?')) {
			document.getElementById("overlay").style.display="inherit";	
			document.location.href = baseUrl+'/admin/examination/ijinkanUjiFungsi/'+a;   
		}
	}

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
	
	function checkFromTPN(a,b,c){
		$.ajax({
			type: "POST",
			url : "generateFromTPN",
			data: {'_token':"{{ csrf_token() }}", 'id':a, 'type':b, 'filelink':c},
			beforeSend: function(){
				document.getElementById("overlay").style.display="inherit";
			},
			success: function(response){
				console.log(response);
				if(response){
					alert(response);
					if(response == b+" Berhasil Disimpan."){location.reload();}
				}else{
					alert("Gagal mengambil data (s)");
				}
				document.getElementById("overlay").style.display="none";
			},
			error:function(response){
				console.log(response);
				alert("Gagal mengambil data (e)");
				document.getElementById("overlay").style.display="none";
			}
		});
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

	function delete_rev_lap_uji_file(a){
		var baseUrl = "{{URL::to('/')}}";
		if (confirm('Are you sure want to delete this data?')) {
		    document.getElementById("overlay").style.display="inherit";	
		 	document.location.href = baseUrl+'/admin/examination/'+a+'/deleteRevLapUji';
		}
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
		if(values['registration_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_registrasi').modal('show');
				$('#myModalketerangan_registrasi').on('shown.bs.modal', function () {
				    $('#keterangan_registrasi').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_registrasi').modal('hide');
			}
		}else{
			// if(!document.getElementById('hide_attachment_form-registrasi').value){
			// 	alert("Form Uji belum ditanda tangan / di-upload oleh kastamer!");
			// 	return false;
			// }
			if(!document.getElementById('examination_lab_id').value){
				alert("Pilih Laboratorium Pengujian!");
				$('#examination_lab_id').focus();
				return false;
			}
		}
	});
	
	$('#form-function-test').submit(function () {
		var keterangan = document.getElementById('keterangan_function').value;
		var barang_file = document.getElementById('barang_file');
		var barang_name = document.getElementById('barang_name');
		var function_file = document.getElementById('function_file');
		var function_name = document.getElementById('function_name');
		var $inputs = $('#form-function-test :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['function_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_function').modal('show');
				$('#myModalketerangan_function').on('shown.bs.modal', function () {
				    $('#keterangan_function').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_function').modal('hide');
			}			
		}else{
			if(document.getElementById('hide_approval_form-function-test').value == 0){
				if(document.getElementById('hide_is_loc_test').value == 1){
					alert("Belum Ada Tanggal Technical Meeting FIX!");
					return false;
				}else{
					alert("Belum Ada Tanggal Uji Fungsi FIX!");
					return false;
				}
			}
			if(document.getElementById('hide_is_loc_test').value == 0){
				if(document.getElementById('hide_count_equipment_form-function-test').value == 0){
					alert("Uji Fungsi Belum dilakukan, Masukkan Barang terlebih dahulu!");
					return false;
				}
			}
			if(document.getElementById('hide_test_TE_form-function-test').value == 0){
				if(document.getElementById('hide_is_loc_test').value == 1){
					alert("Belum Ada Hasil Technical Meeting!");
					return false;
				}else{
					alert("Belum Ada Hasil Uji Fungsi!");
					return false;
				}
			}
			if(function_file.value == '' && function_name.value == ''){
				if(document.getElementById('hide_is_loc_test').value == 1){
					alert("File Laporan Hasil Technical Meeting belum diunggah");$('#function_file').focus();return false;
					return false;
				}else{
					alert("File Laporan Hasil Uji Fungsi belum diunggah");$('#function_file').focus();return false;
					return false;
				}
			}
			if(barang_file.value == '' && barang_name.value == ''){
				alert("File Bukti Penerimaan Perangkat belum diunggah");$('#barang_file').focus();return false;
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
				$('#myModalketerangan_contract').on('shown.bs.modal', function () {
				    $('#keterangan_contract').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_contract').modal('hide');
			}			
		}else{
			if(contract_file.value == '' && contract_name == ''){
				alert("File Tinjauan Kontrak belum diunggah");$('#contract_file').focus();return false;				
			}
		}
	});
	
	$('#form-spb').submit(function () {
		var keterangan = document.getElementById('keterangan_spb').value;
		var spb_file = document.getElementById('spb_file');
		var spb_name = document.getElementById('spb_name').value;
		var spb_number = document.getElementById('spb_number').value;
		var payment_status = document.getElementById('payment_status').value;
		var $inputs = $('#form-spb :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['spb_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_spb').modal('show');
				$('#myModalketerangan_spb').on('shown.bs.modal', function () {
				    $('#keterangan_spb').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_spb').modal('hide');
			}			
		}else{
			if(payment_status == 1){
				alert("SPB sudah dibayar oleh Kastamer!");
				return false;
			}
			if(!spb_number){
				alert("Silakan mengisi Nomor SPB terlebih dahulu (Buatkan File SPB)!");
				return false;
			}
			if(spb_file.value == '' && spb_name == ''){
				alert("File SPB belum diunggah");$('#spb_file').focus();return false;				
			}
		}
	});
	
	$('#form-pembayaran').submit(function () {
		/*var keterangan = document.getElementById('keterangan_pembayaran').value;
		var kuitansi_file = document.getElementById('kuitansi_file');
		var kuitansi_name = document.getElementById('kuitansi_name').value;*/
		var $inputs = $('#form-pembayaran :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['payment_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_pembayaran').modal('show');
				$('#myModalketerangan_pembayaran').on('shown.bs.modal', function () {
				    $('#keterangan_pembayaran').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_pembayaran').modal('hide');
			}			
		}else{
			/*if(document.getElementById('hide_status_form-pembayaran').value == 0){
				alert("Kastamer belum melakukan pembayaran / bukti bayar belum di-upload oleh kastamer!");
				return false;
			}*/
			/*if(kuitansi_file.value == '' && kuitansi_name == ''){
				alert("File Kuitansi belum diunggah");$('#kuitansi_file').focus();return false;				
			}*/
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
			$('#myModalketerangan_spk').on('shown.bs.modal', function () {
			    $('#keterangan_spk').focus();
			});
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
			$('#myModalketerangan_form_uji').on('shown.bs.modal', function () {
			    $('#keterangan_form_uji').focus();
			});
			return false;
		}else{
			$('#myModalketerangan_form_uji').modal('hide');
		}
	});

	$('#form-barang').submit(function () {
		var barang_file2 = document.getElementById('barang_file2');
		var barang_name2 = document.getElementById('barang_name2').value;
		var $inputs = $('#form-barang :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(barang_file2.value == '' && barang_name2 == ''){
			alert("File Tanda Terima belum diunggah");$('#barang_file2').focus();return false;				
		}
		if(document.getElementById('update_barang').value != 1){
			alert("Barang belum diambil kembali oleh kastamer, Update Lokasi Barang terlebih dahulu!");
			return false;
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
			alert("File Tanda Terima belum diunggah");$('#tanda_terima_file').focus();return false;				
		}
	});

	$('#form-lap-uji').submit(function () {
		var rev_lap_uji_file = document.getElementById('rev_lap_uji_file');
		var keterangan = document.getElementById('keterangan_lap_uji').value;
		var $inputs = $('#form-lap-uji :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['resume_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_lap_uji').modal('show');
				$('#myModalketerangan_lap_uji').on('shown.bs.modal', function () {
				    $('#keterangan_lap_uji').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_lap_uji').modal('hide');
			}			
		}else{
			if(!document.getElementById('hide_attachment_form-lap-uji').value && rev_lap_uji_file.value == ''){
				alert("Laporan Hasil Pengujian belum ada / di-upload oleh Test Engineer!");
				return false;
			}
		}
	});
</script>
@if($data->examination_type_id == '1')
<script type="text/javascript">	
	$('#form-sidang').submit(function () {
		var keterangan = document.getElementById('keterangan_sidang_qa').value;
		var $inputs = $('#form-sidang :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['qa_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_sidang_qa').modal('show');
				$('#myModalketerangan_sidang_qa').on('shown.bs.modal', function () {
				    $('#keterangan_sidang_qa').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_sidang_qa').modal('hide');
			}
		}else{
			if (document.getElementById("passed").checked == false && document.getElementById("notPassed").checked == false && document.getElementById("pending").checked == false) {
		     	alert("Belum Ada Hasil Sidang QA!");
				return false;
		    }
		}
	});
	
	$('#form-sertifikat').submit(function () {
		var keterangan = document.getElementById('keterangan_sertifikat').value;
		var certificate_file = document.getElementById('certificate_file');
		var certificate_name = document.getElementById('certificate_name').value;
		var $inputs = $('#form-sertifikat :input');
		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		if(values['certificate_status'] == '-1'){
			if(keterangan == ''){
				$('#myModalketerangan_sertifikat').modal('show');
				$('#myModalketerangan_sertifikat').on('shown.bs.modal', function () {
				    $('#keterangan_sertifikat').focus();
				});
				return false;
			}else{
				$('#myModalketerangan_sertifikat').modal('hide');
			}			
		}else{
			if(certificate_file.value == '' && certificate_name == ''){
				alert("File Sertifikat belum diunggah");$('#certificate_file').focus();return false;				
			}
		}
	});
</script>
@endif
@endsection
