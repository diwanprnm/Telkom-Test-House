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
						<h1 class="mainTitle">Tambah Kuitansi Baru</h1>
					</div>
					<ol class="breadcrumb">
						<li>
							<span>Beranda</span>
						</li>
						<li>
							<span>Kuitansi</span>
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
					{!! Form::open(array('url' => 'admin/kuitansi', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
						{!! csrf_field() !!}
						
							<fieldset>
								<legend>
									Tambah Kuitansi Baru
								</legend>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Nomor *
											</label>
											<input type="text" id="number" name="number" class="form-control" value="{{ $number }}" placeholder="Nomor" required>
										</div>
									</div> 
			                        <div class="col-md-12">
										<div class="form-group">
											<label>
												Sudah diterima dari *
											</label>
											<input type="text" name="from" class="form-control" value="{{ $from }}" placeholder="Sudah diterima dari" required>
										</div>
									</div> 
			                        <div class="col-md-12">
										<div class="form-group">
											<label>
												Banyak Uang *
											</label>
											<input type="number" name="price" class="form-control" value="{{ $price }}" placeholder="Banyak Uang" required>
										</div>
									</div> 
			                        <div class="col-md-12">
										<div class="form-group">
											<label>
												Untuk Pembayaran *
											</label>
											<input type="text" name="for" class="form-control" value="{{ $for }}" placeholder="Untuk Pembayaran" required>
										</div>
									</div> 
									<div class="col-md-12">
										<div class="form-group">
											<label>
												Tanggal *
											</label>
											<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
												<input type="text" name="kuitansi_date" class="form-control" value="{{ date('Y-m-d') }}" required readonly>
												<span class="input-group-btn">
													<button type="button" class="btn btn-default">
														<em class="glyphicon glyphicon-calendar"></em>
													</button>
												</span>
											</p>
										</div>
									</div>
								</div>
								
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
							</fieldset>

					{!! Form::close() !!}
				</div>
			</div>
			<!-- end: RESPONSIVE TABLE -->
		</div>
	</div>

	<!-- start: MAIN JAVASCRIPTS -->
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
	jQuery(document).ready(function() {
		FormElements.init();
	});

	function generateKuitansi(){
		$.ajax({
			type: "POST",
			url : "generateKuitansi",
			data: {'_token':"{{ csrf_token() }}"},
			beforeSend: function(){
				document.getElementById("number").disabled = true;
			},
			success: function(response){
				document.getElementById("number").disabled = false;
				document.getElementById("number").value = response;
				$('#number').val(response);
			},
			error:function(){
				alert("Gagal mengambil data");
				document.getElementById("number").disabled = false;
			}
		});
	}
	
	function closeWindow() {
	   window.close();
	}
</script>

</body>
</html>