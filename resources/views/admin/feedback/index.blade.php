@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Questions and Answers (QnA)</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Questions and Answers (QnA)</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
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
											Tanggal
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
											Status
										</label>
										<select id="is_active" name="is_active" class="cs-select cs-skin-elastic" required>
											@if ($status == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($status == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@if ($status == 1)
												<option value="1" selected>Replied</option>
											@else
												<option value="1">Replied</option>
											@endif
											
											@if ($status == 0)
												<option value="0" selected>Not Replied</option>
											@else
												<option value="0">Not Replied</option>
											@endif
										</select>
									</div>
								</div>
				<!-- sorting -->
								<!-- <div class="col-md-6">
									<div class="form-group">
										<label>
											Sort by :
										</label>
										<select id="sort_by" name="sort_by" class="cs-select cs-skin-elastic" required>
											@if($sort_by == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($sort_by == 'tanggal')
		                                        <option value="tanggal" selected>Tanggal</option>
											@else
		                                        <option value="tanggal">Tanggal</option>
		                                    @endif
											@if($sort_by == 'status')
		                                        <option value="status" selected>Status</option>
											@else
		                                        <option value="status">Status</option>
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
				<!-- end sorting -->
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
									<th class="center">Category</th>
									<th class="center">Email</th>
									<th class="center">Subject</th>
									<th class="center">Pesan</th>
									<th class="center">Status</th>
									<th class="center">Tanggal</th>
                                    <th class="center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->category }}</td>
										<td class="center">{{ $item->email }}</td>
										<td class="center">{{ $item->subject }}</td>
										<td class="center">{{ $item->message }}</td>
										@if($item->status)
											<td class="center"><span class="label label-sm label-success">Replied by {{ $item->user->name }}</span></td>
										@else
											<td class="center"><span class="label label-sm label-warning">Not Replied</span></td>
										@endif
										<td class="center">{{ $item->created_at }}</td>
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/feedback/'.$item->id.'/reply')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-mail-reply"></i></a>
												{!! Form::open(array('url' => 'admin/feedback/'.$item->id.'/destroy')) !!}
													{!! csrf_field() !!}
													<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><i class="fa fa-times fa fa-white"></i></button>
												{!! Form::close() !!}
											</div>
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
								<?php echo $data->appends(array('search' => $search,'status' => $status,'before_date' => $before_date,'after_date' => $after_date,'sort_by' => $sort_by,'sort_type' => $sort_type))->links(); ?>
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
</script>
<script type="text/javascript">
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_feedback_autocomplete/'+request.term,
					dataType: "json",
					cache: false,
					success: function (data) {
						console.log(data);
						response($.map(data, function (item) {
							return {
								label:item.autosuggest
							};
						}));
					},
				});
			},


			// focus: function( event, ui ) {
				// $( "#search_value" ).val( ui.item.label );
				// return false;
			// },

			select: function( event, ui ) {
				$( "#search_value" ).val( ui.item.label );
				return false;
			}
		})

		.autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<div>" + item.label + "</div>" )
			.appendTo( ul );
		};
	});
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
					status:document.getElementById("is_active").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
/*sorting*/
					/*sort_by:document.getElementById("sort_by").value,
					sort_type:document.getElementById("sort_type").value*/
/*end sorting*/
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
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
/*sorting*/
			/*var sort_by = document.getElementById("sort_by");
			var sort_byValue = sort_by.options[sort_by.selectedIndex].value;
			var sort_type = document.getElementById("sort_type");
			var sort_typeValue = sort_type.options[sort_type.selectedIndex].value;*/
/*endsorting*/
			
			if (statusValue != ''){
				params['status'] = statusValue;
				params['search'] = search_value;
			}
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
/*sorting*/
			/*if (sort_byValue != ''){
				params['sort_by'] = sort_byValue;
			}
			if (sort_typeValue != ''){
				params['sort_type'] = sort_typeValue;
			}*/
/*end sorting*/
			document.location.href = baseUrl+'/admin/feedback?'+jQuery.param(params);
	    };
	});
</script>
@endsection