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
											@if($data->keterangan == -1)
												<tr>
													<th colspan="3" class="center" scope="col"><p style="color:red">Perangkat ini sudah pernah diuji, dengan status "Tidak Lulus Uji"</p></th>
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
									<label for="form-field-select-2">
										Lokasi Pengujian
									</label>
									<select name="is_loc_test" class="cs-select cs-skin-elastic" disabled>
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
									<table class="table table-bordered"><caption></caption>
										<thead>
											<tr>
												<th colspan="4" scope="col">Riwayat Pengajuan Tanggal Uji Fungsi</th>
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
													<strong>@php echo $data->cust_test_date; @endphp</strong>
												</td>
												<td>
													<strong>@php echo $data->deal_test_date; @endphp</strong>
												</td>
												<td>
													<strong>@php echo $data->urel_test_date; @endphp</strong>
												</td>
												<td>
													<strong>@php echo $data->function_date; @endphp</strong>
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
									<div class="col-md-12 center">
										<div class="form-group">
											<h4 style="display:inline">Jadwal FIX Uji Fungsi</h4>
											@if($data->function_date != null)		
												<h4 style="display:inline">: @php echo $data->function_date; @endphp</h4>
											@else
												<h4 style="display:inline">: @php echo $data->deal_test_date; @endphp</h4>
											@endif
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
									@php $in_equip_date = $data->function_date; @endphp
								@elseif($data->function_date == null && $data->urel_test_date != null)
									@php $in_equip_date = $data->urel_test_date; @endphp
								@elseif($data->urel_test_date == null && $data->deal_test_date != null)
									@php $in_equip_date = $data->deal_test_date; @endphp
								@else
									@php $in_equip_date = $data->cust_test_date; @endphp
								@endif
							
							<div class="col-md-12">
								<div class="form-group">
									@foreach($data->media as $item)
										@if($item->name == 'Bukti Penerimaan & Pengeluaran Perangkat Uji1' && $item->attachment != '')
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Bukti Penerimaan & Pengeluaran Perangkat Uji1')}}"> Download Bukti Penerimaan & Pengeluaran Perangkat Uji</a>
										@endif
									@endforeach
								</div>
							</div>
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
								@endif
								<div class="form-group">
									@php $function_attach = ''; @endphp
									@foreach($data->media as $item)
										@if($item->name == 'Laporan Hasil Uji Fungsi' && $item->attachment != '')
											@php $function_attach = $item->attachment; @endphp
											<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/Laporan Hasil Uji Fungsi')}}"> Download Hasil Uji Fungsi "@php echo $function_attach; @endphp"</a>
										@endif
									@endforeach
									<input type="hidden" id="function_name" value="@php echo $function_attach; @endphp">
								</div>
								<div class="form-group">
									<label for="catatan">Catatan :</label>
									<textarea class="form-control" rows="5" name="catatan" id="catatan" readonly>{{ $data->catatan }}</textarea>
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
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open() !!}
    				<fieldset>
						<legend>
							Step SPB
						</legend>
					@if($data->registration_status == 1 && $data->function_status == 1)
						<div class="row">
							<div class="col-md-6">
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
					@endif
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Total Biaya *
									</label>
									<input type="text" name="exam_price" id="exam_price" class="form-control" placeholder="Total Biaya" value="{{ $data->price }}" readonly>
								</div>
							</div>
							<input type="hidden" name="spb_number" id="spb_number" value="{{ $data->spb_number }}">
							<input type="hidden" name="spb_date" id="spb_date" value="{{ $data->spb_date }}">
						</div>
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open() !!}
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
										@php $status = 0 @endphp
									@foreach($data->media as $item)
										@if($item->name == 'File Pembayaran' && $item->attachment !='')
											@php $status = 1; break; @endphp
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
											<input type="text" name="cust_price_payment" id="cust_price_payment" class="form-control" placeholder="Banyak Uang" value="{{ $data->cust_price_payment }}" readonly>
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
								<div class="col-md-6">
									<div class="form-group">
										@php $kuitansi_attach = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Kuitansi' && $item->attachment != '')
												@php $kuitansi_attach = $item->attachment; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/kuitansi')}}"> Download Kuitansi "@php echo $kuitansi_attach; @endphp"</a>
											@endif
										@endforeach
										<input type="hidden" id="kuitansi_name" value="@php echo $kuitansi_attach; @endphp">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										@php $faktur_attach = ''; @endphp
										@foreach($data->media as $item)
											@if($item->name == 'Faktur Pajak' && $item->attachment != '')
												@php $faktur_attach = $item->attachment; @endphp
												<a href="{{URL::to('/admin/examination/media/download/'.$data->id.'/faktur')}}"> Download Faktur Pajak "@php echo $faktur_attach; @endphp"</a>
											@endif
										@endforeach
										<input type="hidden" id="faktur_name" value="@php echo $faktur_attach; @endphp">
									</div>
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
								<input type="hidden" name="spb_number" id="spb_number" value="{{ $data->spb_number }}">
								<input type="hidden" name="exam_price" id="exam_price" value="{{ $data->price }}">
								<input type="hidden" name="spb_date" id="spb_date" value="{{ $data->spb_date }}">
							</div>
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
										<input type="text" name="spk_code" id="spk_code" class="form-control" placeholder="Nomor SPK" value="{{ $data->spk_code }}" required 
										@php if($data->spk_code != null){echo "readonly";}@endphp
										>
								</div>
							</div>
	                    </div>
					</fieldset>
				{!! Form::close() !!}

				{!! Form::open() !!}
    				<fieldset>
						<legend>
							Step Pelaksanaan Uji
						</legend>
						<div class="row">
						@if($exam_schedule->code != 'MSTD0059AERR' && $exam_schedule->code != 'MSTD0000AERR')
							@php
								$start_date = new DateTime($exam_schedule->data[0]->targetDt);
								$end_date = new DateTime(date('Y-m-d'));
								$interval = $start_date->diff($end_date);
							@endphp
							<div class="col-md-12">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal Mulai Uji
										</label>
										<label>
											: {{ $exam_schedule->data[0]->startTestDt }}
										</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal Selesai Uji
										</label>
										<label>
											: {{ $exam_schedule->data[0]->targetDt }}
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Sisa SPK
									</label>
									<label>
										: {{ $interval->days }} hari
									</label>
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
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Mulai Uji
										</label>
										<label>
											: {{ $exam_schedule->data[0]->actualStartTestDt }}
										</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Selesai Uji
										</label>
										<label>
											: {{ $exam_schedule->data[0]->actualFinishTestDt }}
										</label>
									</div>
								</div>
								@if(count($data_gudang)>1)
								<div class="col-md-12">
									<div class="form-group">
										<label>
											Barang pindah dari Lab ke Gudang tanggal : {{ $data_gudang[0]->action_date }}
										</label>
									</div>
								</div>
								@endif
							</div>
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
				{!! Form::close() !!}
				
				@if($data->examination_type_id !='1')
				{!! Form::open() !!}
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
									<select name="update_barang" class="cs-select cs-skin-elastic" disabled>
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
				{!! Form::close() !!}
				@endif
				@php $rev_uji = 0; $lap_uji_url = null; $lap_uji_attach = null @endphp
				{!! Form::open() !!}
					<fieldset>
						<legend>
							Step Laporan Uji
						</legend>
						<div class="row">
							@foreach($data->media as $item)
								@if($item->name == 'Laporan Uji')
									@php $lap_uji_url = $item->attachment;$lap_uji_attach = $item->attachment;@endphp
									<input type="hidden" id="hide_attachment_form-lap-uji" value="{{ $item->attachment }}">
									@if($item->attachment != '')
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Laporan Hasil Pengujian dari OTR :
											</label>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
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
										</div>
									</div>
									@else
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Laporan Hasil Pengujian dari OTR : Belum Tersedia
											</label>
										</div>
									</div>
									@endif
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
									<input type="file" name="rev_lap_uji_file" id="rev_lap_uji_file" class="form-control" accept="application/pdf, image/*">
								</div>
								<div class="form-group">
									<table class="table table-bordered">
										<caption></caption>
										<thead>
											<tr>
												<th colspan="5" scope="colgroup">Riwayat Revisi Laporan Uji</th>
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
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="resume_date" class="form-control" value="{{ $data->resume_date }}" readonly/>
									</p>
								</div>
							</div>
	                        <div class="col-md-12">
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
					</fieldset>
				{!! Form::close() !!}
				
					{!! Form::open() !!}
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
											@if($data->qa_passed == 1)
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
													<input type="radio" value="-1" name="passed" id="notPassed" checked>
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
											<input type="text" name="qa_date" class="form-control" value="{{ $data->qa_date }}" readonly/>
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
						</fieldset>
					{!! Form::close() !!}
					
					{!! Form::open() !!}
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
										<select name="update_barang" class="cs-select cs-skin-elastic" disabled>
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
												<input type="text" name="cert_number" id="cert_number" class="form-control" placeholder="Nomor Sertifikat" value="{{ $data->device->cert_number }}" readonly/>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal *
											</label>
											<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
												<input type="text" name="certificate_date" class="form-control" value="{{ $data->certificate_date }}" readonly/>
											</p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal Mulai Berlaku *
											</label>
											<p id="validFrom" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_from" id="valid_from" class="form-control" value="{{ $data->device->valid_from }}" readonly/>
											</p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>
												Tanggal Akhir Berlaku *
											</label>
											<p id="validThru" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
												<input type="text" name="valid_thru" id="valid_thru" class="form-control" value="{{ $data->device->valid_thru }}" readonly/>
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
										@php $count = 0; @endphp
										@foreach($data->examinationHistory as $item)
										@php
											if( strpos( $item->tahap, "Download Sertifikat" ) !== false ) 
											{
												$count++;
											}
										@endphp
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