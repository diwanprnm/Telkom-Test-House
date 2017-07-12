@extends('layouts.app')

@section('content')

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<!-- <h1 class="mainTitle">General Summary Report</h1> -->
					<h2 class="mainTitle">Report Generated On: {{ $data['now'] }}</h2>
				</div>
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
			<!--
	        <div class="row">
		        <div class="col-md-6">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1"><i class="ti-filter"></i> Filter</a>
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <i class="ti-search"></i>
	                </span>
	            </div>
	        </div>
			-->

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
            
			<div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-blue">
                <div class="inner">
                  <h3><?php echo number_format($data['jml_perusahaan'], 0, ',', '.'); ?></h3>
                  <p>Jumlah Perusahaan</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo number_format($data['jml_pemohon'], 0, ',', '.'); ?></h3>
                  <p>Jumlah Pemohon</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo number_format($data['jml_perangkatlulus'], 0, ',', '.'); ?></h3>
                  <p>Jumlah Perangkat Lulus Uji</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
			<div style="margin-bottom:5%"></div>
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
			<div style="margin-bottom:5%"></div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<div class="center"> <strong>Summary </strong></div>
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center">Time Period</th>
									<th class="center">Page Views</th>
									<th class="center">New Visitors</th>
									<th class="center">Return Visitors</th>
									<th class="center">Total Visitors</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="left">Today</td>
									<td class="right">{{ $data['log_now'] }}</td>
									<td class="right">{{ $data['sess_now'] }}</td>
									<td class="right">{{ $data['sess_now_old'] }}</td>
									<td class="right">{{ $data['sess_now_total'] }}</td>
								</tr>
								<tr>
									<td class="left">Yesterday</td>
									<td class="right">{{ $data['log_yesterday'] }}</td>
									<td class="right">{{ $data['sess_yesterday'] }}</td>
									<td class="right">{{ $data['sess_yesterday_old'] }}</td>
									<td class="right">{{ $data['sess_yesterday_total'] }}</td>
								</tr>
								<tr>
									<td class="left">Last Seven Days</td>
									<td class="right">{{ $data['log_lastweek'] }}</td>
									<td class="right">{{ $data['sess_lastweek'] }}</td>
									<td class="right">{{ $data['sess_lastweek_old'] }}</td>
									<td class="right">{{ $data['sess_lastweek_total'] }}</td>
								</tr>
								<tr>
									<td class="left">This Month's Daily Avgs</td>
									<td class="right">{{ $data['log_thismonthavg'] }}</td>
									<td class="right">{{ $data['sess_thismonthavg'] }}</td>
									<td class="right">{{ $data['sess_thismonth_old'] }}</td>
									<td class="right">{{ $data['sess_thismonthavg_total'] }}</td>
								</tr>
								<tr>
									<td class="left">This Month's Totals</td>
									<td class="right">{{ $data['log_thismonth'] }}</td>
									<td class="right">{{ $data['sess_thismonth'] }}</td>
									<td class="right">{{ $data['sess_thismonth_old'] }}</td>
									<td class="right">{{ $data['sess_thismonth_total'] }}</td>
								</tr>
								<tr>
									<td class="left">Last Month's Totals</td>
									<td class="right">{{ $data['log_lastmonth'] }}</td>
									<td class="right">{{ $data['sess_lastmonth'] }}</td>
									<td class="right">{{ $data['sess_lastmonth_old'] }}</td>
									<td class="right">{{ $data['sess_lastmonth_total'] }}</td>
								</tr>
                            </tbody>
						</table>
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center" style="width:20%"></th>
									<th class="center" colspan="4">Site History</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="left">First Page View</td>
									<td class="center" colspan="4">{{ $data['first_log'] }}</td>
								</tr>
								<tr>
									<td class="left">Last Page View</td>
									<td class="center" colspan="4">{{ $data['last_log'] }}</td>
								</tr>
								<tr>
									<td class="left">Total Page Views To Date</td>
									<td class="center" colspan="4">{{ $data['log_count'] }}</td>
								</tr>
								<tr>
									<td class="left">Total Visitors To Date</td>
									<td class="center" colspan="4">{{ $data['sess_count'] }}</td>
								</tr>
								<tr>
									<td class="left">Date of Highest Page Views</td>
									<td class="center" colspan="4">{{ $data['log_max_count'] }} ({{ $data['log_max_date'] }})</td>
								</tr>
                            </tbody>
						</table>
					</div>
				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<div class="center"> <strong>Summary </strong></div>
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center" colspan="2">Rekap Perangkat Pengujian (Ongoing)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="center">Registrasi</td>
									<td class="center">{{ $data['count_reg'] }}</td>
								</tr>
								<tr>
									<td class="center">Uji Fungsi</td>
									<td class="center">{{ $data['count_func'] }}</td>
								</tr>
								<tr>
									<td class="center">Tinjauan Kontrak</td>
									<td class="center">{{ $data['count_func'] }}</td>
								</tr>
								<tr>
									<td class="center">SPB</td>
									<td class="center">{{ $data['count_spb'] }}</td>
								</tr>
								<tr>
									<td class="center">Pembayaran</td>
									<td class="center">{{ $data['count_pay'] }}</td>
								</tr>
								<tr>
									<td class="center">Pembuatan SPK</td>
									<td class="center">{{ $data['count_spk'] }}</td>
								</tr>
								<tr>
									<td class="center">Pengujian</td>
									<td class="center">{{ $data['count_exam'] }}</td>
								</tr>
								<tr>
									<td class="center">Laporan Uji</td>
									<td class="center">{{ $data['count_resu'] }}</td>
								</tr>
								<tr>
									<td class="center">Sidang QA</td>
									<td class="center">{{ $data['count_qa'] }}</td>
								</tr>
								<tr>
									<td class="center">Pembuatan Sertifikat</td>
									<td class="center">{{ $data['count_cert'] }}</td>
								</tr>
                            </tbody>
						</table>
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
   type: 'column',
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
   }
  },
  series: [{
   name: 'Total <?php echo $tahun ?> <?php echo "Rp. ".number_format($count_stel, 0, ',', '.').",-"; ?>',
   data: <?php echo json_encode($stel) ?>
  }]
 });
 
 new Highcharts.Chart({
  chart: {
   renderTo: 'chart2',
   type: 'column',
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
   name: 'Total <?php echo $tahun ?> <?php echo "Rp. ".number_format($count_device, 0, ',', '.').",-"; ?>',
   data: <?php echo json_encode($device) ?>
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