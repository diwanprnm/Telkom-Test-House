<!DOCTYPE html>
<html lang="en">
<head>
	<script type="text/javascript">
		try {
			if (top.location.hostname != self.location.hostname) throw 1;
		} 
		catch (e) {
			top.location.href = self.location.href;
		}
	</script>
    <!-- META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TELKOM DIGITAL SERVICE</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <link href={{ asset("vendor/bootstrap/css/bootstrap.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/fontawesome/css/font-awesome.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/themify-icons/themify-icons.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/animate.css/animate.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/switchery/switchery.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("assets/css/styles.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/bootstrap-colorpicker.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/plugins.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/themes/theme-1.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/chosen.min.css") }} rel="stylesheet" type="text/css">
	
    <link href={{ asset("assets/css/jquery-ui-1_12_1.css") }} rel="stylesheet" type="text/css">
	<script src={{ asset("assets/js/jquery-1.12.4.js") }}></script>
	<script src={{ asset("assets/js/jquery-ui-1_12_1.js") }}></script>
</head>

<body>
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
											Tanggal
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" name="equip_date" class="form-control" value="<?php echo date('Y-m-d');?>">
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
												</button>
											</span>
										</p>
									</div>
								</div>
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
										<a style=" color:white !important;" onclick="closeWindow()">
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
	
	<!-- start: MAIN JAVASCRIPTS -->
    <!-- <script src={{ asset("vendor/jquery/jquery.min.js") }}></script> -->
    <script src={{ asset("vendor/bootstrap/js/bootstrap.min.js") }}></script>
    <script src={{ asset("vendor/modernizr/modernizr.js") }}></script>
    <script src={{ asset("vendor/jquery-cookie/jquery.cookie.js") }}></script>
    <script src={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.js") }}></script>
    <script src={{ asset("vendor/switchery/switchery.min.js") }}></script>
    <script src={{ asset("assets/js/main.js") }}></script>
    <script src={{ asset("assets/js/chosen.jquery.min.js") }}></script>
	<script src={{ asset("assets/js/accounting.min.js") }}></script>
	<script src={{ asset("assets/js/jquery.price_format.min.js") }}></script>
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
	
	function closeWindow() {
	   window.close();
   }
</script>

</body>
</html>