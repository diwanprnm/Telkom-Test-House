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
											<a href="#step-3" class="done wait">
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
											<a href="#step-4" class="done wait">
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
											<a href="#step-5" class="done wait">
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
											<a href="#step-6" class="done wait">
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
											<a href="#step-7" class="done wait">
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
											<a href="#step-8" class="done wait">
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
									@if($data->examination_type_id !='2' && $data->examination_type_id !='3' && $data->examination_type_id !='4')
										<li>
											@if($data->resume_status == '1' && $data->qa_status != '1')
												<a href="#step-9" class="done wait">
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
												<a href="#step-10" class="done wait">
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
											@if(strpos($data->keterangan, 'qa_date') !== false)
												@php $data_ket = explode("qa_date",$data->keterangan); @endphp
												<tr>
													<th colspan="3" class="center" scope="col"><p style="color:red">Perangkat ini sudah pernah diuji, dengan status "Tidak Lulus Uji" berdasarkan keputusan Sidang QA tanggal {{ $data_ket[1] }}</p></th>
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
				
					<!-- Datasheet, Prinsipal -->
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
								</div>
							@endif
							<div class="col-md-12">
								<div class="form-group">
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="5" scope="col">Evidence</th>
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
												@if($item->name == 'Evidence UF')
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
												<tr><td colspan="4" style="text-align: center;"> Data Not Found </td></tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
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
					
    				<fieldset>
						<legend>
							Step SPB
						</legend>
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
					</fieldset>

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
										<label>
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
					</fieldset>

				@if($data->examination_type_id !='1')
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
				@php $rev_uji = 0; $lap_uji_url = null; $lap_uji_attach = null @endphp
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
						@if(count($data->questioner)>0)
							<fieldset>
								<legend>
									Feedback & Complaint
								</legend>
								<div class="col-md-6">
									<div class="form-group">
										<a href="{{URL::to('/cetakKepuasanKonsumen/'.$data->id)}}" target="_blank"> Download Feedback</a>
									</div>
								</div>	
								<div class="col-md-6">
									<div class="form-group">
										<a href="{{URL::to('/cetakComplaint/'.$data->id)}}" target="_blank"> Download Complaint</a>
									</div>
								</div>	
							</fieldset>
						@endif
				@if($data->examination_type_id =='1')
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
						@php $rev_sertifikat = 0; $sertifikat_url = null; $sertifikat_attach = null @endphp
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
									</div>
									<div class="col-md-12">
										<div class="form-group">
											@php $certificate_name = ''; @endphp
											@if($data->certificate_status)
												@if($data->device->certificate)
													@php $certificate_name = $data->device->cert_number; @endphp
													@php $sertifikat_url = URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate');$sertifikat_attach = $certificate_name;@endphp
												@endif
											@endif
											Sertifikat dari Sistem : @if($certificate_name != '') <a href="{{URL::to('/admin/examination/media/download/'.$data->device_id.'/certificate')}}"> Download</a> @else Belum Tersedia @endif
											<input type="hidden" id="certificate_name" value="@php echo $certificate_name; @endphp">
										</div>
									</div>
									@foreach($data->media as $item)
										@if($item->name == 'Revisi Sertifikat' && $rev_sertifikat == 0)
											@php $rev_sertifikat = 1; $sertifikat_url = URL::to('/admin/examination/media/download/'.$item->id); $sertifikat_attach = $item->attachment;@endphp
										@endif
									@endforeach
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Nomor Sertifikat *
											</label>
												<input type="text" class="form-control" placeholder="Nomor Sertifikat" value="{{ $data->device->cert_number }}" readonly required>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Sertifikat yang Diterbitkan
											</label>
											<label>
												: @if($sertifikat_attach)
													<a href="{{ $sertifikat_url }}"> {{ $sertifikat_attach }}</a>
												@else
													Belum Tersedia
												@endif
											</label>
										</div>
									</div>
									<div class="col-md-12" class="panel panel-info">
										<div class="form-group">
											<label>
												Revisi Sertifikat*
											</label>
										</div>
										<div class="form-group">
											<table class="table table-bordered"><caption></caption>
												<thead>
													<tr>
														<th colspan="5" scope="col">Riwayat Revisi Sertifikat</th>
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
														@if($item->name == 'Revisi Sertifikat')
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
														<tr><td colspan="4" style="text-align: center;"> Data Not Found </td></tr>
													@endif
												</tbody>
											</table>
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

						<fieldset>
							<legend>
								Histori Download Laporan Pengujian dan Sertifikat
							</legend>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										@php $count_l = 0; $count_s = 0; @endphp
										@foreach($data->examinationHistory as $item)
										@php
											if( strpos( $item->tahap, "Download Laporan Uji" ) !== false ) 
											{
												$count_l++;
											}
											if( strpos( $item->tahap, "Download Sertifikat" ) !== false ) 
											{
												$count_s++;
											}
										@endphp
										@endforeach
										Laporan Pengujian sudah terdownload sebanyak {{ $count_l }} kali, dan Sertifikat sudah terdownload sebanyak {{ $count_s }} kali.
									</div>
									<div class="form-group" style="overflow-y: scroll; height: 300px;">
										<table class="table table-bordered"><caption></caption>
											<thead>
												<tr>
													<th colspan="4" scope="col">Riwayat Download</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><strong>PIC</td>
													<td><strong>File yang di-download</td>
													<td><strong>Keterangan</td>
													<td><strong>Waktu Download</td>
												</tr>
												@foreach($exam_hist as $data)
												<tr>
													<td>@php echo $data->user->name; @endphp</td>
													<td>@php echo substr(strstr($data->tahap," "), 1); @endphp</td>
													<td>@php echo $data->keterangan; @endphp</td>
													<td>@php echo $data->created_at; @endphp</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</fieldset>
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