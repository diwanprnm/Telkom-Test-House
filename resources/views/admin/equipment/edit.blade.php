@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Barang</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Barang</span>
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
								<div id="step-1">
									<div class="form-group">
										<table class="table table-condensed">
											<thead>
												<tr>
													<th colspan="3">Detail Informasi</th>
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
											<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
												<thead>
													<tr>
														<th class="center">Jumlah</th>
														<th class="center">Unit</th>
														<th class="center">Deskripsi</th>
														<th class="center">Lokasi</th>
														<th class="center">PIC</th>
														<th class="center">Keterangan</th>
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
								</div>
							</div>
							</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/equipment/'.$item->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Lokasi Barang
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Lokasi Barang *
									</label>
									<input type="hidden" name="location_id" value="{{ $location->location }}">
									<select class="cs-select cs-skin-elastic" name="location" required>
										@if($location->location == 1)
											<option value="1" selected>Customer (Applicant)</option>
											<option value="2">URel (Store)</option>
											<option value="3">Lab (Laboratory)</option>
										@elseif($location->location == 2)
											<option value="1">Customer (Applicant)</option>
											<option value="2" selected>URel (Store)</option>
											<option value="3">Lab (Laboratory)</option>
										@else
											<option value="1">Customer (Applicant)</option>
											<option value="2">URel (Store)</option>
											<option value="3" selected>Lab (Laboratory)</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										PIC *
									</label>
									<input type="text" name="pic" class="form-control" placeholder="Nama penanggung jawab ..." value="{{ $location->pic }}" required>
								</div>
							</div>
							<div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                                <a style=" color:white !important;" href="{{URL::to('/admin/equipment')}}">
	                            		<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
	                                		Cancel
	                            		</button>
	                                </a>
	                        </div>
						</div>
					</fieldset>
				{!! Form::close() !!}
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
	$('#examination_id').chosen();
	// $('#examination_id').val(0);
	$('#examination_id').trigger("chosen:updated");
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
@endsection