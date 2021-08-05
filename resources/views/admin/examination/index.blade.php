@extends('layouts.app')

@section('content')

@php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
@endphp

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
					<h1 class="mainTitle">Pengujian</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Pengujian</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="row">
				<div class="col-md-6">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em> Filter</a>
	    			<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	            <div class="col-md-12 panel panel-info">
			    	<div id="collapse1" class="panel-collapse collapse">
			     		<fieldset>
							<legend>
								Filter
							</legend>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group typeHTML">
										<label>
											Tipe Pengujian
										</label>
										<select id="type" name="type" class="cs-select cs-skin-elastic" required>
											@if($filterType == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($filterType == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@foreach($type as $item)
												@if($item->id == $filterType)
													<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->id }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group tahapHTML">
										<label>
											Tahap Pengujian
										</label>
										<select id="status" name="status" class="cs-select cs-skin-elastic" required>
											@if ($status == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($status == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@if ($status == 1)
												<option value="1" selected>Registrasi</option>
											@else
												<option value="1">Registrasi</option>
											@endif
											@if ($status == 2)
												<option value="2" selected>Uji Fungsi</option>
											@else
												<option value="2">Uji Fungsi</option>
											@endif
											@if ($status == 3)
												<option value="3" selected>Tinjauan Kontrak</option>
											@else
												<option value="3">Tinjauan Kontrak</option>
											@endif
											@if ($status == 4)
												<option value="4" selected>SPB</option>
											@else
												<option value="4">SPB</option>
											@endif
											@if ($status == 5)
												<option value="5" selected>Pembayaran</option>
											@else
												<option value="5">Pembayaran</option>
											@endif
											@if ($status == 6)
												<option value="6" selected>Pembuatan SPK</option>
											@else
												<option value="6">Pembuatan SPK</option>
											@endif
											@if ($status == 7)
												<option value="7" selected>Pelaksanaan Uji</option>
											@else
												<option value="7">Pelaksanaan Uji</option>
											@endif
											@if ($status == 8)
												<option value="8" selected>Laporan Uji</option>
											@else
												<option value="8">Laporan Uji</option>
											@endif
											@if ($status == 9)
												<option value="9" selected>Sidang QA</option>
											@else
												<option value="9">Sidang QA</option>
											@endif
											@if ($status == 10)
												<option value="10" selected>Penerbitan Sertifikat</option>
											@else
												<option value="10">Penerbitan Sertifikat</option>
											@endif
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal SPK Dikeluarkan
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control"/>
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
											&nbsp;
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date }}" name="before_date" id="before_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
		                        </div>
		                    </div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal Uji Fungsi
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date_exam }}" name="after_date_exam" id="after_date_exam" class="form-control"/>
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
											&nbsp;
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date_exam }}" name="before_date_exam" id="before_date_exam" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
		                        </div>
		                    </div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group sortHTML">
										<label>
											Sort by :
										</label>
										<select id="sort_from" name="sort_from" class="cs-select cs-skin-elastic" required>
											<option value="updated_at" @if ($sort_from == 'updated_at') selected @endif >Update Terakhir</option>
											<option value="created_at" @if ($sort_from == 'created_at') selected @endif >Tanggal Registrasi</option>
											<option value="device_name" @if ($sort_from == 'device_name') selected @endif  >Nama Perangkat</option>
										</select>
										<select id="sort_by" name="sort_by" class="cs-select cs-skin-elastic" required>
											<option value="asc" @if ($sort_by == 'asc') selected @endif  >ASC</option>
											<option value="desc" @if ($sort_by == 'desc') selected @endif>DESC</option>
										</select>
									</div>
		                        </div>
								<div class="col-md-6">
									<div class="form-group labHTML">
										<label>
											Lab
										</label>
										<select id="exam_lab" name="exam_lab" class="cs-select cs-skin-elastic" required>
											@if($selected_exam_lab == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($selected_exam_lab == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@foreach($examinationLab as $lab)
												@if($selected_exam_lab == $lab->id)
													<option value="{{ $lab->id }}" selected>{{ $lab->name }}</option>
												@else
													<option value="{{ $lab->id }}">{{ $lab->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
		                        </div>       
		                    </div>
							<div class="col-md-12">
								<button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right">
									Filter
								</button>
								<button id="reset-filter" class="btn btn-wide btn-white btn-squared pull-right" style="margin-right: 10px;">
									Reset
								</button>
							</div>
						</fieldset>
			    	</div>
			    </div>
	        </div>

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

			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						@foreach($data as $item)
							<div class="panel panel-default" style="border:solid; border-width:1px">
	  							<div class="panel-body">
	  								<div id="wizard" class="swMain">
										<!-- start: WIZARD SEPS -->
										<ul>
											<li>
												@if($item->registration_status == '1')
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
												@if($item->registration_status == '1' && $item->function_status != '1')
													<a href="#step-2" class="done wait">
												@elseif($item->registration_status == '1' && 
												$item->function_status == '1')
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
												@if($item->registration_status == '1' && 
												$item->function_status == '1' && $item->contract_status != '1')
													<a href="#step-3" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' &&
												$item->contract_status == '1')
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
												@if($item->registration_status == '1' && $item->function_status == '1' && 
												$item->contract_status == '1' && $item->spb_status != '1')
													<a href="#step-4" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && 
												$item->spb_status == '1')
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
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && 
												$item->spb_status == '1' && $item->payment_status != '1')
													<a href="#step-5" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && 
												$item->payment_status == '1')
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
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && 
												$item->payment_status == '1' && $item->spk_status != '1')
													<a href="#step-6" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && 
												$item->spk_status == '1')
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
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && 
												$item->spk_status == '1' && $item->examination_status != '1')
													<a href="#step-7" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && 
												$item->examination_status == '1')
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
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && 
												$item->examination_status == '1' && $item->resume_status != '1')
													<a href="#step-8" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && 
												$item->resume_status == '1')
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
											@if($item->examination_type_id !='2' && $item->examination_type_id !='3' && $item->examination_type_id !='4')
												<li>
													@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && 
													$item->resume_status == '1' && $item->qa_status != '1')
														<a href="#step-9" class="done wait">
													@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && 
													$item->qa_status == '1')
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
													@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && 
													$item->qa_status == '1' && $item->certificate_status != '1')
														<a href="#step-10" class="done wait">
													@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && $item->qa_status == '1' &&
													$item->certificate_status == '1')
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
													@if(strpos($item->keterangan, 'qa_date') !== false)
														@php $data_ket = explode("qa_date",$item->keterangan); @endphp
														<tr>
															<th colspan="3" class="center" scope="col"><p style="color:red">Perangkat ini sudah pernah diuji, dengan status "Tidak Lulus Uji" berdasarkan keputusan Sidang QA tanggal {{ $data_ket[1] }}</p></th>
														</tr>
													@endif
													@if($item->is_cancel)
														<tr>
															<th colspan="3" class="center" scope="col"><p style="color:red">Perangkat ini dibatalkan oleh kastamer dengan alasan {{ $item->reason_cancel }}</p></th>
														</tr>
													@endif
													@if($item->spb_number && $item->payment_status == 0 && $item->spb_date < date('Y-m-d', strtotime('-3 month')))
														<tr>
															<th colspan="3" class="center" scope="col"><p style="color:red">SPB sudah melebihi 3 bulan batas pembayaran.</p></th>
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
																{{ $item->examinationType->name }} ({{ $item->examinationType->description }})
															</td>
														</tr>
														<tr>
															<td>Pemohon:</td>
															<td>
																{{ $item->user->name }}
															</td>
														</tr>
														<tr>
															<td>Perusahaan:</td>
															<td>
																{{ $item->company->name }}
															</td>
														</tr>
														<tr>
															<td>Tanggal Pengajuan:</td>
															<td>
																{{ $item->created_at }}
															</td>
														</tr>
														<tr>
															<td>Referensi Uji:</td>
															<td>
																{{ $item->device->test_reference }}
															</td>
														</tr>
														<tr>
															<td>Perangkat:</td>
															<td>
																{{ $item->device->name }}
															</td>
														</tr>	
														<tr>
															<td>Merek:</td>
															<td>
																{{ $item->device->mark }}
															</td>
														</tr>	
														<tr>
															<td>Model / Tipe:</td>
															<td>
																{{ $item->device->model }}
															</td>
														</tr>	
														<tr>
															<td>Kapasitas:</td>
															<td>
																{{ $item->device->capacity }}
															</td>
														</tr>	
														<tr>
															<td>Serial Number:</td>
															<td>
																{{ $item->device->serial_number }}
															</td>
														</tr>	
														<tr>
															<td>Nomor Registrasi:</td>
															<td>
																{{ $item->function_test_NO }}
															</td>
														</tr>
														<tr>
															<td>Nama Lab:</td>
															<td>
																@if($item->examinationLab)
																	{{ $item->examinationLab->name }}
																@endif
															</td>
														</tr>
														<tr>
															<td>Tanggal Uji Fungsi:</td>
															<td>
																@if($item->function_test_date_approval)
																	@if($item->function_date != null)
																		{{ $item->function_date }}
																	@else
																		{{ $item->deal_test_date }}
																	@endif
																@endif
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class=" pull-left">
												<a class="btn btn-wide btn-primary pull-left" style="margin-left:10px" href="{{URL::to('cetakPengujian/'.$item->id)}}" target="_blank"><em class="ti-download"></em> Form Uji</a>
												
												@foreach($item->media as $item_SPB)
													@if($item_SPB->name == 'SPB')
														<a class="btn btn-wide btn-primary pull-left" style="margin-left:10px" href="{{URL::to('/admin/examination/media/download/'.$item->id.'/SPB')}}"><em class="ti-download"></em> SPB</a>
													@endif
												@endforeach
												
					                        	@if($item->examination_type_id !='2' && $item->examination_type_id !='3' && $item->examination_type_id !='4')
					                        		@if($item->device->certificate != '')
						                        		<a class="btn btn-wide btn-primary pull-left" style="margin-left:10px" href="{{URL::to('/admin/examination/media/download/'.$item->device_id.'/certificate')}}"><em class="ti-download"></em> Sertifikat</a>
						                        	@endif
					                        	@endif
					                        </div>
					                        <div class=" pull-right">
					                        	<a class="btn btn-wide btn-primary btn-margin" href="{{URL::to('admin/examination/'.$item->id.'/edit')}}">Change Status</a>
												@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
													<a class="btn btn-wide btn-primary pull-right" style="margin-left:10px" data-toggle="modal" data-target="#myModal_delete" onclick="document.getElementById('hide_exam_id').value = '{{ $item->id }}'">Delete</a>
												@endif
					                        	<a class="btn btn-wide btn-primary pull-right" style="margin-left:10px" href="{{URL::to('admin/examination/'.$item->id)}}">Detail</a>
					                        </div>
										</div>
									</div>
	  							</div>
							</div>
						@endforeach
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								@php
									echo $data->appends(array(
										'search' => $search,
										'type' => $filterType,
										'status' => $status,
										'before_date' => $before_date,
										'after_date' => $after_date,
										'before_date_exam' => $before_date_exam,
										'after_date_exam' => $after_date_exam,
										'sort_by' => $sort_by,
										'sort_from' => $sort_from,
										'selected_exam_lab' => $selected_exam_lab
									))->links();
								@endphp
							</div>
						</div>
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
	.no-padding{
		padding-left: 0px;
		padding-right: 0px;
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
	var typeHTML = '<select id="type" name="type" class="cs-select cs-skin-elastic" required>'+
					'<option value="" disabled selected>Select...</option>'+
					'<option value="all">All</option>'+
			@foreach($type as $item)
					'<option value="{{ $item->id }}">{{ $item->name }}</option>'+
			@endforeach
					'</select>'
	var tahapHTML = '<select id="status" name="status" class="cs-select cs-skin-elastic" required>'+
					'<option value="" disabled selected>Select...</option>'+
					'<option value="all">All</option>'+
					'<option value="1">Registrasi</option>'+
					'<option value="2">Uji Fungsi</option>'+
					'<option value="3">Tinjauan Kontrak</option>'+
					'<option value="4">SPB</option>'+
					'<option value="5">Pembayaran</option>'+
					'<option value="6">Pembuatan SPK</option>'+
					'<option value="7">Pelaksanaan Uji</option>'+
					'<option value="8">Laporan Uji</option>'+
					'<option value="9">Sidang QA</option>'+
					'<option value="10">Penerbitan Sertifikat</option>'+'</select>'
	var labHTML = '<select id="exam_lab" name="exam_lab" class="cs-select cs-skin-elastic" required>'+
					'<option value="" disabled selected>Select...</option>'+
					'<option value="all">All</option>'+
				@foreach($examinationLab as $lab)
						'<option value="{{ $lab->id }}">{{ $lab->name }}</option>'+
				@endforeach
					'</select>'
	var sortFromHTML = '<select id="sort_from" name="sort_from" class="cs-select cs-skin-elastic" required>'+
							'<option value="updated_at" selected>Update Terakhir</option>'+
							'<option value="created_at">Tanggal Registrasi</option>'+
							'<option value="device_name">Nama Perangkat</option>'+
						'</select>'
	var sortByHTML = '<select id="sort_by" name="sort_by" class="cs-select cs-skin-elastic" required>'+
						'<option value="asc">ASC</option>'+
						'<option value="desc" selected>DESC</option>'+
					'</select>'
	jQuery(document).ready(function() {
		FormElements.init();

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
<script type="text/javascript">
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_exam_autocomplete/'+request.term,
					dataType: "json",
					cache: false,
					success: function (data) {
						console.log(data);
						response($.map(data, function (item) {
							return {
								label:item.autosuggest
							};
						}));
					},
				});
			},


			// focus: function( event, ui ) {
				// $( "#search_value" ).val( ui.item.label );
				// return false;
			// },

			select: function( event, ui ) {
				$( "#search_value" ).val( ui.item.label );
				return false;
			}
		})

		.autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<div>" + item.label + "</div>" )
			.appendTo( ul );
		};
	});
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				params = getParam();
				document.location.href = baseUrl+'/admin/examination?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
			params = getParam();
			document.location.href = baseUrl+'/admin/examination?'+jQuery.param(params);
	    };

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
			params = getParam();
			document.location.href = baseUrl+'/examination/excel?'+jQuery.param(params);
	    };

		
        document.getElementById("reset-filter").onclick = function() {
			$('.cs-select').remove();
            $('.typeHTML').append(typeHTML);
            $('.labHTML').append(labHTML);
			$('.tahapHTML').append(tahapHTML);
			$('.sortHTML').append(sortFromHTML).append(sortByHTML);
			$('#after_date').val(null);
			$('#before_date').val(null);
			$('#after_date_exam').val(null);
			$('#before_date_exam').val(null);
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        };
	});

	function getParam() {
		var params = {};
		var search_value = document.getElementById("search_value").value;
		var beforeValue = document.getElementById("before_date").value;
		var afterValue = document.getElementById("after_date").value;
		var beforeDateExam = document.getElementById("before_date_exam").value;
		var afterDateExam = document.getElementById("after_date_exam").value;
		var sortFrom = document.getElementById("sort_from").value;
		var sortBy = document.getElementById("sort_by").value;
		var examLab = document.getElementById("exam_lab");
		var status = document.getElementById("status");
		var type = document.getElementById("type");
		var selectedExamLab = examLab.options[examLab.selectedIndex].value;
		var statusValue = status.options[status.selectedIndex].value;
		var typeValue = type.options[type.selectedIndex].value;
		
		if (search_value != ''){
			params['search'] = search_value;
		}
		if (beforeValue != ''){
			params['before_date'] = beforeValue;
		}
		if (afterValue != ''){
			params['after_date'] = afterValue;
		}
		if (beforeDateExam != ''){
			params['before_date_exam'] = beforeDateExam;
		}
		if (afterDateExam != ''){
			params['after_date_exam'] = afterDateExam;
		}
		if (sortFrom != ''){
			params['sort_from'] = sortFrom;
		}
		if (sortBy != ''){
			params['sort_by'] = sortBy;
		}
		if (selectedExamLab != ''){
			params['selected_exam_lab'] = selectedExamLab;
		}
		if (statusValue != ''){
			params['status'] = statusValue;
		}
		if (typeValue != ''){
			params['type'] = typeValue;
		}
		return params;
	}
</script>
@endsection