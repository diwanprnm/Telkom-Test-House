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
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />

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
						<h1 class="mainTitle">Tambah Barang Baru</h1>
					</div>
					<ol class="breadcrumb">
						<li>
							<span>Beranda</span>
						</li>
						<li>
							<span>Barang</span>
						</li>
						<li class="active">
							<span>Tambah</span>
						</li>
					</ol>
				</div>
			</section>
			<!-- end: PAGE TITLE -->
			<!-- start: RESPONSIVE TABLE -->
			<div class="container-fluid container-fullw bg-white">
				<div class="col-md-12">
					{!! Form::open(array('url' => 'admin/equipment', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
						{!! csrf_field() !!}
						
						<div class="row">
							<div class="col-md-12" style="margin-bottom:10px">
								<div class="form-group">
									<label>
										Tanggal
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="equip_date" class="form-control">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-12" style="margin-bottom:10px">
								<div class="form-group">
									<label>
										Perangkat Pengujian *
									</label>
									<select class="form-control" id="examination_id" name="examination_id" required>
										<option value="" disabled selected>Select...</option>
										@foreach($examination as $item)
											@if($item->id == $exam_id)
												<option value="{{$item->id}}" selected>{{$item->name}}, model/type {{$item->model}}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							
							<fieldset>
								<legend>
									Tambah Unit
								</legend>

								<div id="equip_fields">
								</div>

								<div class="row">
									<div class="col-md-1">
										<div class="form-group">
											<label>
												Jumlah *
											</label>
											<input type="number" name="qty[]" class="form-control" placeholder="Jumlah" required>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>
												Unit/Satuan *
											</label>
											<input type="text" name="unit[]" class="form-control" placeholder="mis: meter, dll ..." required>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>
												PIC *
											</label>
											<input type="text" name="pic[]" class="form-control" placeholder="Nama penanggung jawab ..." required>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>
												Deskripsi
											</label>
											<textarea type="text" name="description[]" class="form-control" placeholder="Deskripsi"></textarea>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label>
												Keterangan
											</label>
											<textarea type="text" name="remarks[]" class="form-control" placeholder="Keterangan"></textarea>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label>
												Aksi
											</label>
											<button class="btn btn-success" type="button"  onclick="equip_fields();"> <span class="glyphicon glyphicon-plus" style="float:right"></span></button>
										</div>
									</div>
								</div>

							</fieldset>

							<div class="col-md-12">
								<button type="submit" class="btn btn-wide btn-green btn-squared pull-left generate-button">
									Submit
								</button>
									<a style=" color:white !important;" onclick="closeWindow()">
										<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
											Cancel
										</button>
									</a>
							</div>
						</div>
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

	var equip = 1;
	function equip_fields() {
	 
	    equip++;
	    var objTo = document.getElementById('equip_fields')
	    var divtest = document.createElement("div");
		divtest.setAttribute("class", "form-group removeclass"+equip);
		var rdiv = 'removeclass'+equip;
	    divtest.innerHTML = '<div class="row"><div class="col-md-1"><div class="form-group"><label>Jumlah *</label><input type="number" name="qty[]" class="form-control" placeholder="Jumlah" required></div></div><div class="col-md-2"><div class="form-group"><label>Unit/Satuan *</label><input type="text" name="unit[]" class="form-control" placeholder="mis: meter, dll ..." required></div></div><div class="col-md-2"><div class="form-group"><label>PIC *</label><input type="text" name="pic[]" class="form-control" placeholder="Nama penanggung jawab ..." required></div></div><div class="col-md-3"><div class="form-group"><label>Deskripsi</label><textarea type="text" name="description[]" class="form-control" placeholder="Deskripsi"></textarea></div></div><div class="col-md-3"><div class="form-group"><label>Keterangan</label><textarea type="text" name="remarks[]" class="form-control" placeholder="Keterangan"></textarea></div></div><div class="col-md-1"><div class="form-group"><label>Aksi</label><button class="btn btn-danger" type="button"  onclick="remove_equip_fields('+ equip +');"> <span class="glyphicon glyphicon-minus" style="float:right"></span></button></div></div></div>';
	    
	    objTo.appendChild(divtest)
	}
	   function remove_equip_fields(rid) {
		   $('.removeclass'+rid).remove();
	   }
	   
	   function closeWindow() {
		   window.close();
	   }
	   
	// $('.generate-button').click(function () {
		// window.close();
		// location.reload();
	// });
</script>

</body>
</html>