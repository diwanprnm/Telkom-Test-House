@extends('layouts.app')

@section('content')
<style type="text/css">
	tbody { cursor: grab; }
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Sidang QA Berjalan</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li>
						<span>Sidang QA</span>
					</li>
					<li class="active">
						<span>Sidang QA Berjalan</span>
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

			{!! Form::open(array('id' => 'form-sidang', 'url' => 'admin/sidang/'.$data[0]->sidang->id, 'method' => 'PUT')) !!}
			<div class="modal fade" id="myModal_editPerangkat" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Edit Data Perangkat</h4>
						</div>
						
						<div class="modal-body">
							<table style="width: 100%;"><caption></caption>
								<input type="hidden" name="device_id" id="device_id"></input>
								<tr>
									<th scope="col">
										<div class="form-group">
											<label for="test_reference">Referensi Uji:</label>
											<input class="form-control" name="test_reference" id="test_reference" placeholder="referensi uji"></input>
										</div>
									</th>
								</tr>
								<tr>
									<th scope="col">
										<div class="form-group">
											<label for="name">Perangkat:</label>
											<input class="form-control" name="name" id="name" placeholder="nama perangkat"></input>
										</div>
									</th>
								</tr>
								<tr>
									<th scope="col">
										<div class="form-group">
											<label for="mark">Merek:</label>
											<input class="form-control" name="mark" id="mark" placeholder="merek perangkat"></input>
										</div>
									</th>
								</tr>
								<tr>
									<th scope="col">
										<div class="form-group">
											<label for="model">Model/Tipe:</label>
											<input class="form-control" name="model" id="model" placeholder="model/tipe"></input>
										</div>
									</th>
								</tr>
								<tr>
									<th scope="col">
										<div class="form-group">
											<label for="capacity">Kapasitas:</label>
											<input class="form-control" name="capacity" id="capacity" placeholder="kapasitas"></input>
										</div>
									</th>
								</tr>
								<tr>
									<th scope="col">
										<div class="form-group">
											<label for="manufactured_by">Negara Pembuat:</label>
											<input class="form-control" name="manufactured_by" id="manufactured_by" placeholder="negara pembuat"></input>
										</div>
									</th>
								</tr>
								<tr>
									<th scope="col">
										<div class="form-group">
											<label for="serial_number">Serial Number:</label>
											<input class="form-control" name="serial_number" id="serial_number" placeholder="serial number"></input>
										</div>
									</th>
								</tr>
							</table>
						</div><!-- /.modal-content -->
						<div class="modal-footer">
							<table style="width: 100%;"><caption></caption>
								<tr>
									<th scope="col">
										<button type="submit" id="btn-modal-editPerangkat" class="btn btn-danger" onclick='$("#status").val("ON GOING");' style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
									</th>
								</tr>
							</table>
						</div>
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</div>

			{!! csrf_field() !!}
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Tanggal Sidang</label>
						<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
							<input type="text" name="date" class="form-control" value="{{ $data[0]->sidang->date }}" required/>
							<span class="input-group-btn">
								<button type="button" class="btn btn-default">
									<em class="glyphicon glyphicon-calendar"></em>
								</button>
							</span>
						</p>
					</div>
				</div>
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="Audience">Audience :</label>
						<textarea class="form-control" name="audience" id="audience" required>{{ $data[0]->sidang->audience }}</textarea>
					</div>
				</div>
		        <div class="col-md-12">
					<table id="sortable" class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
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
								<th class="center" scope="col">Keputusan</th> 
								<th class="center" scope="col">Masa Berlaku</th>  
								<th class="center" colspan="3" scope="colgroup">Aksi</th> 
							</tr>
						</thead>
						
						@php $no = 1; @endphp
						@if(count($data)>0)
							@foreach($data as $keys => $item)
							<tbody> 
								<input type="hidden" value="{{ $item->examination_id }}" name="examination_id[]">
								<tr>
									<td class="center">{{ $no }}</td>
									<td class="center">{{ $item->examination->company->name }}</td>
									<td class="center">{{ $item->examination->device->name }}</td>
									<td class="center">{{ $item->examination->device->mark }}</td>
									<td class="center">{{ $item->examination->device->model }}</td>
									<td class="center">{{ $item->examination->device->capacity }}</td>
									<td class="center">{{ $item->examination->device->manufactured_by }}</td>
									<td class="center">{{ $item->finalResult ? $item->finalResult : '-' }}</td>
									<td class="center">
										<select class="result" name="result[]">
											<option value="0" @if ($item->result == 0) selected @endif>Choose</option>
											<option value="1" @if ($item->result == 1) selected @endif>Lulus</option>
											<option value="2" @if ($item->result == 2) selected @endif>Pending</option>
											<option value="-1" @if ($item->result == -1) selected @endif>Tidak Lulus</option>
										</select>
									</td>
									<td class="center">
										<select class="valid_range" name="valid_range[]">
											<option value="0" @if ($item->valid_range == 0) selected @endif>Choose</option>
											<option value="36" @if ($item->valid_range == 36) selected @endif>3 Tahun</option>
											<option value="12" @if ($item->valid_range == 12) selected @endif>1 Tahun</option>
											<option value="9" @if ($item->valid_range == 9) selected @endif>9 Bulan</option>
											<option value="6" @if ($item->valid_range == 6) selected @endif>6 Bulan</option>
										</select>
									</td>
									<td class="center">
										<div>
											<textarea class="content-catatan" name="catatan[]" style="display: none;" placeholder="Catatan ..." autofocus>{{ $item->catatan }}</textarea>
											<a href="javascript:void(0)" class="collapsible-catatan pull-right"><em class="fa fa-file-o"></em></a>
										</div>
									</td>
									<td class="center">
										<div>
											<a href="javascript:editPerangkat({{ $item->examination->device }})"><em class="fa fa-pencil"></em></a>
										</div>
									</td>
									<td class="center">
										<div>
											<a href="javascript:void(0)" class="collapsible"><em class="fa fa-eye"></em></a>
										</div>
									</td>
								</tr>
								<tr class="content" style="display: none;">
									<td colspan="13" class="center">
										<table class="table table-bordered table-hover table-full-width dataTable no-footer" style="width: 100%;">
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
								<tr class="content" style="display: none;"><td colspan="13"></td></tr>
							@php
								$no++
							@endphp
							</tbody>
							@endforeach
						@else
							<tbody> 
								<tr>
									<td colspan=13 class="center">
										Data Not Found
									</td>
								</tr>
							</tbody> 
						@endif
					</table>
				</div>

				<div class="col-md-12">
					<input type="hidden" id="status" name="status">
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-right" onclick='$("#status").val("DONE");'>
						Selesai Sidang
					</button>
					<a style=" color:white !important;" href="{{ URL::to('/admin/sidang') }}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Batal
						</button>
					</a>
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-left" onclick='$("#status").val("ON GOING");'>
						Simpan
					</button>
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
	$( "#sortable" ).sortable({delay: 150});
	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
	  coll[i].addEventListener("click", function() {
	    this.classList.toggle("active");
	    var content = $(this).parents().parents().next()[0];
	    if (content.style.display == "") {
	      content.style.display = "none";
	    } else {
	      content.style.display = "";
	    }
	  });
	}

	var coll_catatan = document.getElementsByClassName("collapsible-catatan");
	var i;

	for (i = 0; i < coll_catatan.length; i++) {
		coll_catatan[i].addEventListener("click", function() {
	    this.classList.toggle("active");
	    var content = $(this).prev()[0];
		if (content.style.display == "") {
	      content.style.display = "none";
	    } else {
	      content.style.display = "";
	    }
	  });
	}

	function editPerangkat(device){
		$('#myModal_editPerangkat').on('shown.bs.modal', function () {
		    $('#test_reference').focus();
			$('#device_id').val(device.id);
			$('#test_reference').val(device.test_reference);
			$('#name').val(device.name);
			$('#mark').val(device.mark);
			$('#model').val(device.model);
			$('#capacity').val(device.capacity);
			$('#manufactured_by').val(device.manufactured_by);
			$('#serial_number').val(device.serial_number);
		});
		
		$('#myModal_editPerangkat').modal('show');
	}

	$('#form-sidang').submit(function () {
		$('#myModal_editPerangkat').modal('hide');
		document.getElementById("overlay").style.display="inherit";	
	});

	jQuery(document).ready(function() {
		FormElements.init();
		$('.result').change(function() {
			if($(this).val() == '1'){
				$(this).closest('tr').find('.valid_range').val(36);
			}else{
				$(this).closest('tr').find('.valid_range').val(0);
			}
		});
	});
</script>
@endsection