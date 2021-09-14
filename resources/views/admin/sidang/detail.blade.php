@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Detail Sidang QA</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li>
						<span>Sidang QA</span>
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
				<div class="col-md-3">
					<div class="form-group">
						<label>Tanggal Sidang</label>
						<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
							<input type="text" name="date" class="form-control" value="{{ $data[0]->sidang->date }}" disabled/>
						</p>
					</div>
				</div>
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="Audience">Audience :</label>
						<textarea class="form-control" name="audience" id="audience" required disabled>{{ $data[0]->sidang->audience }}</textarea>
					</div>
				</div>
		        <div class="col-md-12">
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
								<th class="center" scope="col">Keputusan</th> 
								<th class="center" scope="col">Masa Berlaku</th>  
								<th class="center" scope="col">Aksi</th> 
							</tr>
						</thead>
						<tbody> 
							@php $no = 1; @endphp
							@if(count($data)>0)
								@foreach($data as $keys => $item)
									<input type="hidden" value="{{ $item->id }}" name="id[]">
									<tr>
										<td class="center">{{ $no }}</td>
										<td class="center">{{ $item->examination->company->name }}</td>
										<td class="center">{{ $item->examination->device->name }}</td>
										<td class="center">{{ $item->examination->device->mark }}</td>
										<td class="center">{{ $item->examination->device->model }}</td>
										<td class="center">{{ $item->examination->device->capacity }}</td>
										<td class="center">{{ $item->examination->device->manufactured_by }}</td>
										<td class="center"> fromOTR </td>
										<td class="center">
											<select class="cs-select cs-skin-elastic" name="result[]" disabled>
												<option value="0" @if ($item->result == 0) selected @endif>Choose</option>
												<option value="1" @if ($item->result == 1) selected @endif>Lulus</option>
												<option value="2" @if ($item->result == 2) selected @endif>Pending</option>
												<option value="-1" @if ($item->result == -1) selected @endif>Tidak Lulus</option>
											</select>
										</td>
										<td class="center">
											<select class="cs-select cs-skin-elastic" name="valid_range[]" disabled>
												<option value="0" @if ($item->valid_range == 0) selected @endif>Choose</option>
												<option value="36" @if ($item->valid_range == 36) selected @endif>3 Tahun</option>
												<option value="12" @if ($item->valid_range == 12) selected @endif>1 Tahun</option>
												<option value="9" @if ($item->valid_range == 9) selected @endif>9 Bulan</option>
												<option value="6" @if ($item->valid_range == 6) selected @endif>6 Bulan</option>
											</select>
										</td>
										<td class="center"><a href="javascript:void(0)" class="collapsible">Detail</a></td>
									</tr>
									<tr class="content" style="display: none;">
										<td colspan="11" class="center">
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
														<td class="center">fromOTR</td>
														<td class="center">fromOTR</td>
														<td class="center">{{ $item->examination->examinationLab->name }}</td>
														<td class="center">fromOTR</td>														
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
				<div class="col-md-6">
					<div class="form-group">
						<label for="Catatan">Catatan :</label>
						<textarea class="form-control" name="catatan" id="catatan" rows=5 disabled>{{ $data[0]->sidang->catatan }}</textarea>
					</div>
				</div>

				<div class="col-md-12">
					<a style=" color:white !important;" href="{{ URL::to('/admin/sidang') }}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Kembali
						</button>
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
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
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
</script>
@endsection