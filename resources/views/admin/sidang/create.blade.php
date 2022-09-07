@extends('layouts.app')

@section('content')

<style type="text/css">
	tbody { cursor: grab; }
	ul.tabs{
		margin: 0px;
		padding: 0px;
		list-style: none;
		margin-bottom: 10px;
	}
	ul.tabs li{
		background: none;
		color: #222;
		display: inline-block;
		padding: 10px 15px;
		cursor: pointer;
	}

	ul.tabs li.current{
		background: #FF3E41;
		color: #ffffff;
	}

	.tab-content{
		display: none;
		/*background: #FF3E41;
		padding: 15px;*/
	}

	.tab-content.current{
		display: inherit;
	}
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Buat Draft Sidang QA</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li>
						<span>Sidang QA</span>
					</li>
					<li class="active">
						<span>Buat Draft Sidang QA</span>
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

			<ul class="tabs">
				<li class="btn tab-perangkat" data-tab="tab-perangkat">Perangkat Belum Sidang QA</li>
				<li class="btn tab-pending" data-tab="tab-pending">Perangkat Tertunda</li>
				<li class="btn tab-draft" data-tab="tab-draft">Pratinjau Draft Sidang QA ({{ count($data_draft) }})</li>
			</ul>

			{!! Form::open(array('url' => 'admin/sidang', 'method' => 'POST')) !!}
			<input type="hidden" name="sidang_id" id="sidang_id" value="{{ $sidang_id }}">
			<input type="hidden" name="hidden_tab" id="hidden_tab" value="{{ $tab }}">
			{!! csrf_field() !!}

			<div id="tab-perangkat" class="row tab-content">
		        <div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Device Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Perusahaan</th> 
									<th class="center" scope="col">Perangkat</th> 
									<th class="center" scope="col">Merek</th>  
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas</th>
									<th class="center" scope="col">Negara Pembuat</th> 
									<th class="center" scope="col">Hasil Uji</th> 
									<th class="center" scope="col">Status</th>
									<th class="center" scope="col"><input type="checkbox" onchange="checkAllPerangkat(this)"></th>  
									<th class="center" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data_perangkat)>0)
									@foreach($data_perangkat as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_perangkat->currentPage()-1)*$data_perangkat->perPage()) }}</td>
											<td class="center">{{ $item->company->name }}</td>
											<td class="center">{{ $item->device->name }}</td>
											<td class="center">{{ $item->device->mark }}</td>
											<td class="center">{{ $item->device->model }}</td>
											<td class="center">{{ $item->device->capacity }}</td>
											<td class="center">{{ $item->device->manufactured_by }}</td>
											<td class="center">{{ $item->finalResult ? $item->finalResult : '-' }}</td>
											<td class="center">{{ $item->company->qs_certificate_date > date('Y-m-d') ? 'SM Eligible' : 'SM Not Eligible' }}</td>
											<td class="center"><input type="checkbox" name="chk-perangkat[]" id="chk-perangkat-{{$item->id}}" class="chk-perangkat" value="{{ $item->id }}"></td>
											<td class="center"><a href="javascript:void(0)" class="collapsible"><em class="fa fa-eye"></em></a></td>
										</tr>
										<tr class="content" style="display: none;">
											<td colspan="11" class="center">
												<table class="table table-bordered table-hover table-full-width dataTable no-footer" style="width: 100%;">
													<caption>Examination Table</caption>
													<thead>
														<tr>
															<th class="center" scope="col">No. SPK</th>
															<th class="center" scope="col">No. Laporan</th>
															<th class="center" scope="col">No. Seri</th>
															<th class="center" scope="col">Referensi Uji</th>
															<th class="center" scope="col">Tanggal Penerimaan</th>  
															<th class="center" scope="col">Tanggal Mulai Uji</th>  
															<th class="center" scope="col">Tanggal Selesai Uji</th>  
															<th class="center" scope="col">Diuji Oleh</th>  
															<th class="center" scope="col">Target Penyelesaian</th>  
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="center">{{ $item->spk_code }}</td>
															@php $no_lap = '-'; @endphp
															@foreach($item->media as $file)
																@if($file->name == 'Laporan Uji')
																	@php $no_lap = $file->no; @endphp
																@endif
															@endforeach
															<td class="center">{{ $no_lap }}</td>
															<td class="center">{{ $item->device->serial_number }}</td>
															<td class="center">{{ $item->device->test_reference }}</td>
															@php $tgl_barang = '-'; @endphp
															@foreach($item->equipmentHistory as $barang)
																@if($barang->location == 2)
																	@php $tgl_barang = $barang->action_date; @endphp
																@endif
															@endforeach
															<td class="center">{{ $tgl_barang }}</td>
															<td class="center">{{ $item->startDate ? $item->startDate : '-' }}</td>
															<td class="center">{{ $item->endDate ? $item->endDate : '-' }}</td>
															<td class="center">{{ $item->examinationLab->name }}</td>
															<td class="center">{{ $item->targetDate ? $item->targetDate : '-' }}</td>
														</tr> 
													</tbody>
												</table>
											</td>
										</tr>
										<tr class="content" style="display: none;"><td colspan="11"></td></tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=11 class="center">
											Data Not Found
										</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{ $data_perangkat->appends(array('tab' => 'tab-perangkat'))->links() }}
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-right" id="btn-submit-perangkat">
						Tambahkan ke draft (0)
					</button>
					@php
						$url = URL::to('/admin/sidang');
						if($sidang_id){
							if($data_draft[0]->sidang->status == 'PRATINJAU'){
								$url = URL::to('/admin/sidang/delete/'.$sidang_id.'/PRATINJAU');
							}
						}
					@endphp
					<a style=" color:white !important;" href="{{ $url }}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Batal
						</button>
					</a>
				</div>
			</div>

			<div id="tab-pending" class="row tab-content">
		        <div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Device Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Perusahaan</th> 
									<th class="center" scope="col">Perangkat</th> 
									<th class="center" scope="col">Merek</th>  
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas</th>
									<th class="center" scope="col">Negara Pembuat</th> 
									<th class="center" scope="col">Hasil Uji</th> 
									<th class="center" scope="col">Status</th> 
									<th class="center" scope="col"><input type="checkbox" onchange="checkAllPending(this)"></th>  
									<th class="center" scope="col">Aksi</th> 
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data_pending)>0)
									@foreach($data_pending as $keys => $item)
										<tr>
										<td class="center">{{ $no+(($data_pending->currentPage()-1)*$data_pending->perPage()) }}</td>
											<td class="center">{{ $item->company->name }}</td>
											<td class="center">{{ $item->device->name }}</td>
											<td class="center">{{ $item->device->mark }}</td>
											<td class="center">{{ $item->device->model }}</td>
											<td class="center">{{ $item->device->capacity }}</td>
											<td class="center">{{ $item->device->manufactured_by }}</td>
											<td class="center">{{ $item->finalResult ? $item->finalResult : '-' }}</td>
											<td class="center">{{ $item->company->qs_certificate_date > date('Y-m-d') ? 'SM Eligible' : 'SM Not Eligible' }}</td>
											<td class="center"><input type="checkbox" name="chk-pending[]" id="chk-pending-{{$item->id}}" class="chk-pending" value="{{ $item->id }}"></td>
											<td class="center"><a href="javascript:void(0)" class="collapsible"><em class="fa fa-eye"></em></a></td>
										</tr>
										<tr class="content" style="display: none;">
											<td colspan="11" class="center">
												<table class="table table-bordered table-hover table-full-width dataTable no-footer" style="width: 100%;">
													<caption>Examination Table</caption>
													<thead>
														<tr>
															<th class="center" scope="col">No. SPK</th>
															<th class="center" scope="col">No. Laporan</th>
															<th class="center" scope="col">No. Seri</th>
															<th class="center" scope="col">Referensi Uji</th>
															<th class="center" scope="col">Tanggal Penerimaan</th>  
															<th class="center" scope="col">Tanggal Mulai Uji</th>  
															<th class="center" scope="col">Tanggal Selesai Uji</th>  
															<th class="center" scope="col">Diuji Oleh</th>  
															<th class="center" scope="col">Target Penyelesaian</th>  
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="center">{{ $item->spk_code }}</td>
															@php $no_lap = '-'; @endphp
															@foreach($item->media as $file)
																@if($file->name == 'Laporan Uji')
																	@php $no_lap = $file->no; @endphp
																@endif
															@endforeach
															<td class="center">{{ $no_lap }}</td>
															<td class="center">{{ $item->device->serial_number }}</td>
															<td class="center">{{ $item->device->test_reference }}</td>
															@php $tgl_barang = '-'; @endphp
															@foreach($item->equipmentHistory as $barang)
																@if($barang->location == 2)
																	@php $tgl_barang = $barang->action_date; @endphp
																@endif
															@endforeach
															<td class="center">{{ $tgl_barang }}</td>
															<td class="center">{{ $item->startDate ? $item->startDate : '-' }}</td>
															<td class="center">{{ $item->endDate ? $item->endDate : '-' }}</td>
															<td class="center">{{ $item->examinationLab->name }}</td>
															<td class="center">{{ $item->targetDate ? $item->targetDate : '-' }}</td>
														</tr> 
													</tbody>
												</table>
											</td>
										</tr>
										<tr class="content" style="display: none;"><td colspan="11"></td></tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=11 class="center">
											Data Not Found
										</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{ $data_pending->appends(array('tab' => 'tab-pending'))->links() }}
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-right" id="btn-submit-pending">
						Tambahkan ke draft (0)
					</button>
					@php
						$url = URL::to('/admin/sidang');
						if($sidang_id){
							if($data_draft[0]->sidang->status == 'PRATINJAU'){
								$url = URL::to('/admin/sidang/delete/'.$sidang_id.'/PRATINJAU');
							}
						}
					@endphp
					<a style=" color:white !important;" href="{{ $url }}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Batal
						</button>
					</a>
				</div>
			</div>
			
			<div id="tab-draft" class="row tab-content">
				<div class="col-md-3">
					<div class="form-group">
						<label>Tanggal Sidang</label>
						<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
							<input type="text" name="date" class="form-control" value="{{ date('Y-m-d') }}" required/>
							<span class="input-group-btn">
								<button type="button" class="btn btn-default">
									<em class="glyphicon glyphicon-calendar"></em>
								</button>
							</span>
						</p>
					</div>
					<div class="form-group">
						<a href="{{ URL::to('/admin/sidang/'.$sidang_id.'/excel') }}" target="_blank">
							<button type="button" class="btn btn-info">
								Export to Excel
							</button>
						</a>
					</div>
				</div>
		        <div class="col-md-12">
					<div class="table-responsive">
						<table id="sortable" class="table table-bordered table-hover table-full-width dataTable no-footer">	
							<caption>Device Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Perusahaan</th> 
									<th class="center" scope="col">Perangkat</th> 
									<th class="center" scope="col">Merek</th>  
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas</th>
									<th class="center" scope="col">Negara Pembuat</th> 
									<th class="center" scope="col">Hasil Uji</th> 
									<th class="center" scope="col">Status</th> 
									<th class="center" scope="col"><input type="checkbox" onchange="checkAllDraft(this)" {{ $sidang_id ? 'checked' : '' }}></th>  
									<th class="center" scope="col">Aksi</th> 
								</tr>
							</thead>
							
							@php $no = 1; @endphp
							@if(count($data_draft)>0)
								@foreach($data_draft as $keys => $item)
								<tbody> 
									<tr>
										<td class="center">{{ $no+(($data_draft->currentPage()-1)*$data_draft->perPage()) }}</td>
										<td class="center">{{ $item->examination->company->name }}</td>
										<td class="center">{{ $item->examination->device->name }}</td>
										<td class="center">{{ $item->examination->device->mark }}</td>
										<td class="center">{{ $item->examination->device->model }}</td>
										<td class="center">{{ $item->examination->device->capacity }}</td>
										<td class="center">{{ $item->examination->device->manufactured_by }}</td>
										<td class="center">{{ $item->finalResult ? $item->finalResult : '-' }}</td>
										<td class="center">{{ $item->examination->company->qs_certificate_date > date('Y-m-d') ? 'SM Eligible' : 'SM Not Eligible' }}</td>
										<td class="center"><input type="checkbox" name="chk-draft[]" id="chk-draft-{{$item->examination->id}}" class="chk-draft" value="{{ $item->examination->id }}" checked></td>
										<td class="center"><a href="javascript:void(0)" class="collapsible"><em class="fa fa-eye"></em></a></td>
									</tr>
									<tr class="content" style="display: none;">
										<td colspan="11" class="center">
											<table class="table table-bordered table-hover table-full-width dataTable no-footer" style="width: 100%;">
												<caption>Examination Table</caption>
												<thead>
													<tr>
														<th class="center" scope="col">No. SPK</th>
														<th class="center" scope="col">No. Laporan</th>
														<th class="center" scope="col">No. Seri</th>
														<th class="center" scope="col">Referensi Uji</th>
														<th class="center" scope="col">Tanggal Penerimaan</th>  
														<th class="center" scope="col">Tanggal Mulai Uji</th>  
														<th class="center" scope="col">Tanggal Selesai Uji</th>  
														<th class="center" scope="col">Diuji Oleh</th>  
														<th class="center" scope="col">Target Penyelesaian</th>  
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="center">{{ $item->examination->spk_code }}</td>
														@php $no_lap = '-'; @endphp
														@foreach($item->examination->media as $file)
															@if($file->name == 'Laporan Uji')
																@php $no_lap = $file->no; @endphp
															@endif
														@endforeach
														<td class="center">{{ $no_lap }}</td>
														<td class="center">{{ $item->examination->device->serial_number }}</td>
														<td class="center">{{ $item->examination->device->test_reference }}</td>
														@php $tgl_barang = '-'; @endphp
														@foreach($item->examination->equipmentHistory as $barang)
															@if($barang->location == 2)
																@php $tgl_barang = $barang->action_date; @endphp
															@endif
														@endforeach
														<td class="center">{{ $tgl_barang }}</td>
														<td class="center">{{ $item->startDate ? $item->startDate : '-' }}</td>
														<td class="center">{{ $item->endDate ? $item->endDate : '-' }}</td>
														<td class="center">{{ $item->examination->examinationLab->name }}</td>
														<td class="center">{{ $item->targetDate ? $item->targetDate : '-' }}</td>
													</tr> 
												</tbody>
											</table>
										</td>
									</tr>
									<tr class="content" style="display: none;"><td colspan="11"></td></tr>
								@php
									$no++
								@endphp
								</tbody>
								@endforeach
							@else
								<tbody>
									<tr>
										<td colspan=11 class="center">
											Data Not Found
										</td>
									</tr>
								</tbody>
							@endif
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{ $data_draft->appends(array('tab' => 'tab-draft'))->links() }}
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-right" id="btn-submit-draft">
					@php
						$btn_label = 'Buat draft';
						if($sidang_id){
							if($data_draft[0]->sidang->status == 'DRAFT'){
								$btn_label = 'Simpan';
							}
						}
					@endphp
					{{ $btn_label }}
					</button>
					@php
						$url = URL::to('/admin/sidang');
						if($sidang_id){
							if($data_draft[0]->sidang->status == 'PRATINJAU'){
								$url = URL::to('/admin/sidang/delete/'.$sidang_id.'/PRATINJAU');
							}
						}
					@endphp
					<a style=" color:white !important;" href="{{ $url }}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Batal
						</button>
					</a>
				</div>
			</div>
			{!! Form::close() !!}
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
	if($("#hidden_tab").val()){
		var tab_id = $("#hidden_tab").val();

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$("."+tab_id).addClass('current');
		$("#"+tab_id).addClass('current');
	}else{
		$(".tab-perangkat").addClass('current');
		$("#tab-perangkat").addClass('current');
	}

	$( "#sortable" ).sortable({delay: 150});
	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
	  coll[i].addEventListener("click", function() {
	    this.classList.toggle("active");
	    var content = $(this).parents().parents().next()[0];
	    var content2 = $(this).parents().parents().next().next()[0];
	    if (content.style.display == "") {
	      content.style.display = "none";
	      content2.style.display = "none";
	    } else {
	      content.style.display = "";
	      content2.style.display = "";
	    }
	  });
	}

	jQuery(document).ready(function() {
		FormElements.init();

		$('ul.tabs li').click(function(){
			var tab_id = $(this).attr('data-tab');

			$('ul.tabs li').removeClass('current');
			$('.tab-content').removeClass('current');

			$(this).addClass('current');
			$("#"+tab_id).addClass('current');

			$("#hidden_tab").val(tab_id);
		});
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('.chk-perangkat').change(function() {
			count_chk_perangkat = 0;
			let checkboxes = document.getElementsByClassName('chk-perangkat');
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-perangkat[]' && checkboxes[i].checked) {
					count_chk_perangkat++;
				}
			}
			$("#btn-submit-perangkat").html("Tambahkan ke draft ("+count_chk_perangkat+")");
		});

		$('.chk-pending').change(function() {
			count_chk_pending = 0;
			let checkboxes = document.getElementsByClassName('chk-pending');
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-pending[]' && checkboxes[i].checked) {
					count_chk_pending++;
				}
			}
			$("#btn-submit-pending").html("Tambahkan ke draft ("+count_chk_pending+")");
		});

		$('.chk-draft').change(function() {
			count_chk_draft = 0;
			let checkboxes = document.getElementsByClassName('chk-draft');
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-draft[]' && checkboxes[i].checked) {
					count_chk_draft++;
				}
			}
			$(".tab-draft").html("Pratinjau Draft Sidang QA ("+count_chk_draft+")");
		});

		$('#btn-submit-perangkat').click(function() {
			if($('input[name="chk-perangkat[]"]:checked').length == 0) { return false; }
		});

		$('#btn-submit-pending').click(function() {
			if($('input[name="chk-pending[]"]:checked').length == 0) { return false; }
		});

		$('#btn-submit-draft').click(function() {
			if($('input[name="chk-draft[]"]:checked').length == 0) { return false; }
		});
	});

	function checkAllPerangkat(box) 
	{
		count_chk_perangkat = 0;
		let checkboxes = document.getElementsByClassName('chk-perangkat');

		if (box.checked) {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-perangkat[]') {
					checkboxes[i].checked = true;
					count_chk_perangkat++;
				}
			}
		} else {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-perangkat[]') {
					checkboxes[i].checked = false;
				}
			}
		}
		$("#btn-submit-perangkat").html("Tambahkan ke draft ("+count_chk_perangkat+")");
	}

	function checkAllPending(box) 
	{
		count_chk_pending = 0;
		let checkboxes = document.getElementsByClassName('chk-pending');

		if (box.checked) {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-pending[]') {
					checkboxes[i].checked = true;
					count_chk_pending++;
				}
			}
		} else {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-pending[]') {
					checkboxes[i].checked = false;
				}
			}
		}
		$("#btn-submit-pending").html("Tambahkan ke draft ("+count_chk_pending+")");
	}

	function checkAllDraft(box) 
	{
		count_chk_draft = 0;
		let checkboxes = document.getElementsByClassName('chk-draft');

		if (box.checked) {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-draft[]') {
					checkboxes[i].checked = true;
					count_chk_draft++;
				}
			}
		} else {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-draft[]') {
					checkboxes[i].checked = false;
				}
			}
		}
		$(".tab-draft").html("Pratinjau Draft Sidang QA ("+count_chk_draft+")");
	}
</script>>
@endsection