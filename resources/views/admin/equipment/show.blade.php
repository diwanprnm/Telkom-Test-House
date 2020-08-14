@extends('layouts.app')

@section('content')

@php
	$currentUser = Auth::user();
	$is_super = $currentUser['id'];
	@endphp

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Detail Barang</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Barang</span>
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
			<div class="col-md-12">
				<div class="table-responsive">
					<div class="panel panel-default" style="border:solid; border-width:1px">
							<div class="panel-body">
								<div id="wizard" class="swMain">
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
													<td>Perangkat :</td>
													<td>
														{{ $item->name }}
													</td>
												</tr>
												<tr>
													<td>Model / Tipe :</td>
													<td>
														{{ $item->model }}
													</td>
												</tr>
											</tbody>
										</table>
									</div>
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption></caption>
												<thead>
													<tr>
														<th class="center" scope="col">Jumlah</th>
														<th class="center" scope="col">Unit</th>
														<th class="center" scope="col">Deskripsi</th>
														<th class="center" scope="col">Lokasi</th>
														<th class="center" scope="col">PIC</th>
														<th class="center" scope="col">Keterangan</th>
													</tr>
												</thead>
												<tbody>
													@foreach($data as $equip)
														@if($equip->examination_id == $item->id)
															<tr>
																<td class="center">{{ $equip->qty }}</td>
																<td class="center">{{ $equip->unit }}</td>
																<td class="center">{{ $equip->description }}</td>
																@if($equip->location == 1)
																	<td class="center">Customer (Applicant)</td>
																@elseif($equip->location == 2)
																	<td class="center">URel (Store)</td>
																@else
																	<td class="center">Lab (Laboratory)</td>
																@endif
																<td class="center">{{ $equip->pic }}</td>
																<td class="center">{{ $equip->remarks }}</td>
															</tr>
														@endif
													@endforeach
												</tbody>
											</table>
										</div>
										<br>
										<h4>History Perpindahan Barang</h4>
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption></caption>
												<thead>
													<tr>
														<th class="center" scope="col">Lokasi</th>
														<th class="center" scope="col">Tanggal</th>
													</tr>
												</thead>
												<tbody>
													@foreach($history as $equip)
														@if($equip->examination_id == $item->id)
															<tr>
																@if($equip->location == 1)
																	<td class="center">Customer (Applicant)</td>
																@elseif($equip->location == 2)
																	<td class="center">URel (Store)</td>
																@else
																	<td class="center">Lab (Laboratory)</td>
																@endif
																<td class="center">{{ $equip->updated_at }}</td>
															</tr>
														@endif
													@endforeach
												</tbody>
											</table>
										</div>
								</div>
								<a href="{{URL::to('/admin/equipment')}}">
			                    	<button type="button" class="btn btn-wide btn-red btn-squared pull-left" style="margin-right: 1%;">Kembali</button>
			                    </a>
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
@endsection