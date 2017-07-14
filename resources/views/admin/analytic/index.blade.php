@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">General Summary Report</h1>
					<h2 class="mainTitle">Report Generated On: {{ $data['now'] }}</h2>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Analytics</span>
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