@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Data Uji Fungsi</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Data Uji Fungsi</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="row">
			<!-- excel -->
				<div class="col-md-6" style="margin-bottom: 10px;">
	    			<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
				</div>
			<!-- end excel -->
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center">No</th>
									<th class="center">Tanggal FIX</th>
									<th class="center">Nama Perusahaan</th>
									<th class="center">Nama Perangkat</th>
									<th class="center">Merk</th>
									<th class="center">Tipe</th>
									<th class="center">Kapasitas/Kecepatan</th>
									<th class="center">Hasil Uji Fungsi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">
											@if($item->function_test_date_approval == 1)
												@if($item->function_date != null)
													<?php echo $item->function_date; ?>
												@else
													<?php echo $item->deal_test_date; ?>
												@endif
											@else
												-
											@endif
										</td>
										<td class="center">{{ $item->company->name }}</td>
										<td class="center">{{ $item->device->name }}</td>
										<td class="center">{{ $item->device->mark }}</td>
										<td class="center">{{ $item->device->model }}</td>
										<td class="center">{{ $item->device->capacity }}</td>
										<td class="center">
											@if($item->function_test_TE == 1)
												Memenuhi
											@elseif($item->function_test_TE == 2)
												Tidak Memenuhi
											@elseif($item->function_test_TE == 3)
												dll
											@else
												Tidak Ada
											@endif
										</td>
									</tr>
								<?php $no++ ?>
								@endforeach
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								<?php echo $data->appends(array())->links(); ?>
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
	document.getElementById("excel").onclick = function() {
        var baseUrl = "{{URL::to('/')}}";
        var params = {};
		document.location.href = baseUrl+'/functiontest/excel?'+jQuery.param(params);
    };
</script>
@endsection