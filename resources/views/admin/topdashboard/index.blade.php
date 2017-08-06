@extends('layouts.app')

@section('content')

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Top Dashboard</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">			
			<div class="row">
				<div class="col-sm-3">
	            	<div class="panel panel-white no-radius text-center">
	            		<div class="panel-body">
	            			<span class="fa-stack fa-2x">
	            				<i class="fa fa-square fa-stack-2x text-primary"></i>	
	            				<i class="fa fa-building-o fa-stack-1x fa-inverse"></i>
	            			</span>
	            			<h2 class="StepTitle">
	            				<?php echo number_format($data['jml_perusahaan'], 0, ',', '.'); ?> Perusahaan
	            			</h2>
	            			<p class="text-small">Jumlah mitra perusahaan yang terdaftar</p>
	            		</div>
	              	</div>
	            </div><!-- ./col -->

	            <div class="col-sm-3">
	            	<div class="panel panel-white no-radius text-center">
	            		<div class="panel-body">
	            			<span class="fa-stack fa-2x">
	            				<i class="fa fa-square fa-stack-2x text-primary"></i>	
	            				<i class="fa fa-user fa-stack-1x fa-inverse"></i>
	            			</span>
	            			<h2 class="StepTitle">
	            				<?php echo number_format($data['jml_pemohon'], 0, ',', '.'); ?> Pemohon
	            			</h2>
	            			<p class="text-small">Jumlah pemohon pengujian yang terdaftar</p>
	            		</div>
	              	</div>
	            </div><!-- ./col -->

	            <div class="col-sm-3">
	            	<div class="panel panel-white no-radius text-center">
	            		<div class="panel-body">
	            			<span class="fa-stack fa-2x">
	            				<i class="fa fa-square fa-stack-2x text-primary"></i>	
	            				<i class="fa fa-check-circle-o fa-stack-1x fa-inverse"></i>
	            			</span>
	            			<h2 class="StepTitle">
	            				<?php echo number_format($data['jml_perangkatlulus'], 0, ',', '.'); ?> Perangkat
	            			</h2>
	            			<p class="text-small">Jumlah perangkat lulus uji yang terdaftar</p>
	            		</div>
	              	</div>
	            </div><!-- ./col -->

	            <div class="col-sm-3">
	            	<div class="panel panel-white no-radius text-center">
	            		<div class="panel-body">
	            			<span class="fa-stack fa-2x">
	            				<i class="fa fa-square fa-stack-2x text-primary"></i>	
	            				<i class="fa fa-times-circle-o fa-stack-1x fa-inverse"></i>
	            			</span>
	            			<h2 class="StepTitle">
	            				<?php echo number_format($data['count_dev_notComp'], 0, ',', '.'); ?> Perangkat
	            			</h2>
	            			<p class="text-small">Jumlah perangkat tidak lulus uji yang terdaftar</p>
	            		</div>
	              	</div>
	            </div><!-- ./col -->
          	</div><!-- /.row -->

			<div class="table-responsive">
				<div class="panel panel-default">
					<fieldset>
						<legend>
							Rekap Status Pengujian (Ongoing)
						</legend>
						<div class="panel-body">
							<div id="wizard" class="swMain">
								<!-- start: WIZARD SEPS -->
								<ul>
									<li>
										<a href="#step-1">
											<div class="stepNumber">
												{{ $data['count_reg'] }}
											</div>
											<span class="stepDesc"><small> Registrasi </small></span>
										</a>
									</li>
									<li>
										<a href="#step-2">
											<div class="stepNumber">
												{{ $data['count_func'] }}
											</div>
											<span class="stepDesc"><small> Uji Fungsi </small></span>
										</a>
									</li>
									<li>
										<a href="#step-3">
											<div class="stepNumber">
												{{ $data['count_cont'] }}
											</div>
											<span class="stepDesc"><small> Tinjauan Kontrak </small></span>
										</a>
									</li>
									<li>
										<a href="#step-4">
											<div class="stepNumber">
												{{ $data['count_spb'] }}
											</div>
											<span class="stepDesc"><small> SPB </small></span>
										</a>
									</li>
									<li>
										<a href="#step-5">
											<div class="stepNumber">
												{{ $data['count_pay'] }}
											</div>
											<span class="stepDesc"><small> Pembayaran </small></span>
										</a>
									</li>
									<li>
										<a href="#step-6">
											<div class="stepNumber">
												{{ $data['count_pay'] }}
											</div>
											<span class="stepDesc"><small> Pembuatan SPK </small></span>
										</a>
									</li>
									<li>
										<a href="#step-7">
											<div class="stepNumber">
												{{ $data['count_exam'] }}
											</div>
											<span class="stepDesc"><small> Pelaksanaan Uji </small></span>
										</a>
									</li>
									<li>
										<a href="#step-8">
											<div class="stepNumber">
												{{ $data['count_resu'] }}
											</div>
											<span class="stepDesc"><small> Laporan Uji </small></span>
										</a>
									</li>
									
									<li>
										<a href="#step-9">
											<div class="stepNumber">
												{{ $data['count_qa'] }}
											</div>
											<span class="stepDesc"><small> Sidang QA </small></span>
										</a>
									</li>
									
									<li>
										<a href="#step-10">
											<div class="stepNumber">
												{{ $data['count_cert'] }}
											</div>
											<span class="stepDesc"><small> Penerbitan Sertifikat </small></span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<div class="table-responsive">
						<span>Cari Berdasarkan Tahun :
						<input type="number" id="txt-keyword" value="<?php echo date('Y')?>" style="padding:3px 4px 4px 4px;border:1px solid #ccc" placeholder="Input Tahun ...">
						<button class="btn btn-default btn-flat" id="sendEmail" data-toggle="modal" onclick="doSearch()">Search <i class="fa fa-search"></i></button>
						<div id="chart" style="z-index:-10;"></div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="table-responsive">
						<span>Cari Berdasarkan Tahun :
						<input type="number" id="txt-keyword-2" value="<?php echo date('Y')?>" style="padding:3px 4px 4px 4px;border:1px solid #ccc" placeholder="Input Tahun ...">
						<button class="btn btn-default btn-flat" id="sendEmail" data-toggle="modal" onclick="doSearch2()">Search <i class="fa fa-search"></i></button>
						<div id="chart2" style="z-index:-10;"></div>
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
<script src={{ asset("assets/css/highcharts/highcharts.js") }}></script>
<script src={{ asset("assets/css/highcharts/modules/exporting.js") }}></script>
<script src={{ asset("assets/css/highcharts/themes/skies.js") }}></script>
<script type="text/javascript">
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

function doSearch(){
	var keyword = document.getElementById("txt-keyword");
	if(keyword.value=='')
	{
		alert("Periksa kembali input pencarian!");
		keyword.focus();
		return false;
	}
	else 
	{
		var options = {
			chart: {
				renderTo: 'chart',
				type: 'column',
			},
			title: {
				text: 'Pembelian STEL '+keyword.value,
				x: -20
			},
			subtitle: {
				text: 'Total pendapatan dalam tiap bulan',
				x: -20
			},
			xAxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
						'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
			},
			yAxis: {
				title: {
					text: 'Pendapatan (Rupiah)'
				}
			},
			series: []
		};
		$.ajax({
			type: "POST",
			url:"topdashboard/searchGrafik",
			data:"keyword="+keyword.value+"&type=1",
			beforeSend: function(){
				$("#chart").hide();
				// document.getElementById("overlay").style.display="inherit";
			},
			success: function(msg){
				$("#chart").show();
				// document.getElementById("overlay").style.display="none";
				var datas = msg.split("||");
				a = jQuery.parseJSON(datas[0]);
				b = jQuery.parseJSON(datas[1]);
				options.title = {
					text: 'Pembelian STEL '+keyword.value,
					x: -20
				}
				options.series = [
					{
						name: 'Total '+keyword.value+' Rp. '+b+',-',
						data:a
					}
				]
				var chart = new Highcharts.Chart(options);
			}
		});
	}
}
function doSearch2(){
	var keyword = document.getElementById("txt-keyword-2");
	if(keyword.value=='')
	{
		alert("Periksa kembali input pencarian!");
		keyword.focus();
		return false;
	}
	else 
	{
		var options = {
			chart: {
				renderTo: 'chart2',
				type: 'column',
			},
			title: {
				text: 'Pengujian Perangkat '+keyword.value,
				x: -20
			},
			subtitle: {
				text: 'Total pendapatan dalam tiap bulan',
				x: -20
			},
			xAxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
						'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
			},
			yAxis: {
				title: {
					text: 'Pendapatan (Rupiah)'
				}
			},
			series: []
		};
		$.ajax({
			type: "POST",
			url:"topdashboard/searchGrafik",
			data:"keyword="+keyword.value+"&type=2",
			beforeSend: function(){
				$("#chart2").hide();
				// document.getElementById("overlay").style.display="inherit";
			},
			success: function(msg){
				$("#chart2").show();
				// document.getElementById("overlay").style.display="none";
				var datas = msg.split("||");
				a = jQuery.parseJSON(datas[0]);
				b = jQuery.parseJSON(datas[1]);
				options.title = {
					text: 'Pengujian Perangkat '+keyword.value,
					x: -20
				}
				options.series = [
					{
						name: 'Total '+keyword.value+' Rp. '+b+',-',
						data:a
					}
				]
				var chart = new Highcharts.Chart(options);
			}
		});
	}
}

jQuery(function(){
$("#txt-keyword").keydown(function(event){
if(event.keyCode == 13){
		doSearch();
	}
});
$("#txt-keyword-2").keydown(function(event){
if(event.keyCode == 13){
		doSearch2();
	}
});

 new Highcharts.Chart({
  chart: {
   renderTo: 'chart',
   type: 'line',
  },
  title: {
   text: 'Pembelian STEL <?php echo $tahun ?>',
   x: -20
  },
  subtitle: {
   text: 'Total pendapatan dalam tiap bulan',
   x: -20
  },
  xAxis: {
   categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']
  },
  yAxis: {
   title: {
    text: 'Pendapatan (Rupiah)'
   },
   min:0
  },
  series: [{
   name: 'Semua',
   data: <?php echo json_encode($stel) ?>
  },{
   name: 'Lab Kabel',
   data: <?php echo json_encode($stel_kab) ?>
  },{
   name: 'Lab Transmisi',
   data: <?php echo json_encode($stel_tra) ?>
  },{
   name: 'Lab CPE',
   data: <?php echo json_encode($stel_cpe) ?>
  },{
   name: 'Lab Energi',
   data: <?php echo json_encode($stel_ene) ?>
  },{
   name: 'Lab Kalibrasi',
   data: <?php echo json_encode($stel) ?>
  }]
 });
 
 new Highcharts.Chart({
  chart: {
   renderTo: 'chart2',
   type: 'line',
  },
  title: {
   text: 'Pengujian Perangkat <?php echo $tahun ?>',
   x: -20
  },
  subtitle: {
   text: 'Total pendapatan dalam tiap bulan',
   x: -20
  },
  xAxis: {
   categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']
  },
  yAxis: {
   title: {
    text: 'Pendapatan (Rupiah)'
   }
  },
  series: [{
   name: 'Semua',
   data: <?php echo json_encode($device) ?>
  },{
   name: 'QA',
   data: <?php echo json_encode($device_qa) ?>
  },{
   name: 'TA',
   data: <?php echo json_encode($device_ta) ?>
  },{
   name: 'VT',
   data: <?php echo json_encode($device_vt) ?>
  },{
   name: 'CAL',
   data: <?php echo json_encode($device_cal) ?>
  }]
 });
 
 
}); 
</script>
<script type="text/javascript">
	/* jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
					status:document.getElementById("is_active").value
				};
				document.location.href = baseUrl+'/admin/feedback?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
	        var params = {};
			var search_value = document.getElementById("search_value").value;
	        var status = document.getElementById("is_active");
			var statusValue = status.options[status.selectedIndex].value;
			
			if (statusValue != ''){
				params['status'] = statusValue;
				params['search'] = search_value;
			}
			document.location.href = baseUrl+'/admin/feedback?'+jQuery.param(params);
	    };
	}); */
</script>
@endsection