@extends('layouts.app')

@section('content')
<style type="text/css">
	.chosen-container.chosen-container-single {
		width: 100% !important;
	}   
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Administrator Log</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Tools</span>
					</li>
					<li class="active">
						<span>Administrator Log</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
		        <div class="col-md-6">
		        	<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><i class="ti-filter"></i> Filter</a>
					<!-- <a class="btn btn-info pull-left" href="{{URL::to('log/excel')}}"> Export to Excel</a> -->
					<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
		        </div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <i class="ti-search"></i>
	                </span>
	            </div>
	            <div class="col-md-12 panel panel-info">
			    	<div id="collapse1" class="panel-collapse collapse">
			     		<fieldset>
							<legend>
								Filter
							</legend>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Periode
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
												</button>
											</span>
										</p>
									</div>
		                        </div>
		                        <div class="col-md-6">
									<div class="form-group">
										<label>
											&nbsp;
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date }}" name="before_date" id="before_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<i class="glyphicon glyphicon-calendar"></i>
												</button>
											</span>
										</p>
									</div>
		                        </div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Username
										</label>
										<select class="form-control" id="username" name="username" class="chosen-username">
												@if ($filterUsername == '')
													<option value="" disabled selected> - Pilih Username - </option>
												@endif
												@if ($filterUsername == 'all')
													<option value="all" selected>All</option>
												@else
													<option value="all">All</option>
												@endif
											@foreach($username as $item)
												@if($item->name == $filterUsername)
													<option value="{{ $item->name }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->name }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Action
										</label>
										<select class="form-control" id="action" name="action" class="chosen-action">
												@if ($filterAction == '')
													<option value="" disabled selected> - Pilih Action - </option>
												@endif
												@if ($filterAction == 'all')
													<option value="all" selected>All</option>
												@else
													<option value="all">All</option>
												@endif
											@foreach($action as $item)
												@if($item->action == $filterAction)
													<option value="{{ $item->action }}" selected>{{ $item->action }}</option>
												@else
													<option value="{{ $item->action }}">{{ $item->action }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<!-- Sorting -->
								<!-- <div class="col-md-6">
									<div class="form-group">
										<label>
											Sort by :
										</label>
										<select id="sort_by" name="sort_by" class="cs-select cs-skin-elastic" required>
											@if($sort_by == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($sort_by == 'logs.created_at')
                                                <option value="logs.created_at" selected>Periode</option>
											@else
                                                <option value="logs.created_at">Periode</option>
                                            @endif
											@if($sort_by == 'users.name')
                                                <option value="users.name" selected>Username</option>
											@else
                                                <option value="users.name">Username</option>
                                            @endif
											@if($sort_by == 'logs.action')
                                                <option value="logs.action" selected>Action</option>
											@else
                                                <option value="logs.action">Action</option>
                                            @endif
										</select>
										<select id="sort_type" name="sort_type" class="cs-select cs-skin-elastic" required>
											@if($sort_type == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($sort_type == 'asc')
                                                <option value="asc" selected>ASC</option>
											@else
                                                <option value="asc">ASC</option>
                                            @endif
											@if($sort_type == 'desc')
                                                <option value="desc" selected>DESC</option>
											@else
                                                <option value="desc">DESC</option>
                                            @endif
										</select>
									</div>
								</div> -->
								<!-- End Sorting -->
								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right">
		                                Filter
		                            </button>
		                        </div>
		                    </div>
						</fieldset>
			    	</div>
			    </div>
	        </div>

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
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center">No</th> 
									<th class="center">Username</th> 
									<th class="center">Action</th>
									<th class="center">Page</th>
									<th class="center">Note</th>
									<th class="center">Date</th> 
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->name }}</td> 
										<td class="center">{{ $item->action }}</td>
										<td class="center">{{ $item->page }}</td> 
										<td class="center">{{ $item->reason }}</td> 
										<td class="center">{{ $item->search_date }}</td> 
									</tr>
								<?php $no++ ?>
								@endforeach
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								<?php echo $data->appends(array('search' => $search,'before_date' => $before_date,'after_date' => $after_date,'username' => $filterUsername,'action' => $filterAction,'sort_by' => $sort_by,'sort_type' => $sort_type))->links(); ?>
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
	jQuery(document).ready(function() {
		FormElements.init();
	});
	$('#username').chosen();
	$('#username').trigger("chosen:updated");
	$('#action').chosen();
	$('#action').trigger("chosen:updated");
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
					username:document.getElementById("username").value,
					action:document.getElementById("action").value,
/*sorting*/
					/*sort_by:document.getElementById("sort_by").value,
					sort_type:document.getElementById("sort_type").value*/
/*end sorting*/
				};
				document.location.href = baseUrl+'/admin/log_administrator?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
			var username = document.getElementById("username");
			var usernameValue = username.options[username.selectedIndex].value;
			var action = document.getElementById("action");
			var actionValue = action.options[action.selectedIndex].value;
/*sorting*/
			/*var sort_by = document.getElementById("sort_by");
			var sort_byValue = sort_by.options[sort_by.selectedIndex].value;
			var sort_type = document.getElementById("sort_type");
			var sort_typeValue = sort_type.options[sort_type.selectedIndex].value;*/
/*end sorting*/

			params['search'] = search_value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (usernameValue != ''){
				params['username'] = usernameValue;
			}
			if (actionValue != ''){
				params['action'] = actionValue;
			}
/*sorting*/			
			/*if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}*/
/*end sorting*/
			document.location.href = baseUrl+'/admin/log_administrator?'+jQuery.param(params);
	    };

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
            var username = document.getElementById("username");
			var usernameValue = username.options[username.selectedIndex].value;
			var action = document.getElementById("action");
			var actionValue = action.options[action.selectedIndex].value;
/*sorting*/
			/*var sort_by = document.getElementById("sort_by");
			var sort_byValue = sort_by.options[sort_by.selectedIndex].value;
			var sort_type = document.getElementById("sort_type");
			var sort_typeValue = sort_type.options[sort_type.selectedIndex].value;*/
/*end sorting*/
			params['search'] = search_value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (usernameValue != ''){
				params['username'] = usernameValue;
			}
			if (actionValue != ''){
				params['action'] = actionValue;
			}
/*sorting*/			
			/*if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}*/
/*end sorting*/
			document.location.href = baseUrl+'/log_administrator/excel?'+jQuery.param(params);
	    };
	});
</script>>
@endsection