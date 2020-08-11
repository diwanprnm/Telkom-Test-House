@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Perusahaan</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Perusahaan</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
	        	<div class="col-md-6">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em> Filter</a>
					<a class="btn btn-info pull-left" href="{{URL::to('company/excel')}}" style="margin-right: 10px;"> Export to Excel</a>
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <em class="ti-search"></em>
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
											Tanggal Daftar
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
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
													<em class="glyphicon glyphicon-calendar"></em>
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
											
											@if ($status == 'all')
												<option value="all" selected>All</option>
											@else
												<option value="all">All</option>
											@endif
											
											@if ($status == '1')
												<option value="1" selected>Active</option>
											@else
												<option value="1">Active</option>
											@endif
											
											@if ($status == '0')
												<option value="0" selected>Not Active</option>
											@else
												<option value="0">Not Active</option>
											@endif
										</select>
									</div>
								</div>
<!-- sorting -->
								
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
				{!! Form::open(array('url' => 'company/importExcel', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
					<div class="col-md-4" style="margin-bottom:10px">
						<label>
							Import from Excel
						</label>
						<input type="file" name="import_file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
					</div>
					<div class="col-md-2" style="margin-top:22px">
						<button type="submit" class="btn btn-wide btn-green btn-squared">
							Submit
						</button>
					</div>
				{!! Form::close() !!}
				<div class="col-md-6 pull-right" style="margin-bottom:10px;margin-top:20px">
					<a style=" color:white !important;" href="{{URL::to('/admin/company/create')}}">
		            <button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Tambah Perusahaan
		            </button>         
					</a>
		        </div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption></caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nama Perusahaan</th>
									<th class="center" scope="col">Kota</th>
									<th class="center" scope="col">Email</th>
									<th class="center" scope="col">Kode Pos</th>
									<th class="center" scope="col">Nomor Telepon</th>
                                    <th class="center" scope="col">Fax</th>
                                    <th class="center" scope="col">Keterangan</th>
                                    <th class="center" scope="col">Status</th>
                                    <th class="center" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->name }}</td>
										<td class="center">{{ $item->city }}</td>
										<td class="center">{{ $item->email }}</td>
										<td class="center">{{ $item->postal_code }}</td>
										<td class="center">{{ $item->phone_number }}</td>
	                                    <td class="center">{{ $item->fax }}</td>
	                                    <td class="center">{{ $item->keterangan }}</td>
	                                    @if($item->is_active)
	                                    	<td class="center"><span class="label label-sm label-success">Active</span></td>
	                                    @else
	                                    	<td class="center"><span class="label label-sm label-warning">Not Active</span></td>
	                                    @endif
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/company/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
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
								<?php echo $data->appends(array('search' => $search,'is_active' => $status,'before_date' => $before_date,'after_date' => $after_date,'sort_by' => $sort_by,'sort_type' => $sort_type))->links(); ?>
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
					url: 'adm_company_autocomplete/'+request.term,
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
					is_active:document.getElementById("is_active").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
/*sorting*/
					/*sort_by:document.getElementById("sort_by").value,
					sort_type:document.getElementById("sort_type").value*/
/*end sorting*/
				};
				document.location.href = baseUrl+'/admin/company?'+jQuery.param(params);
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
/*end sorting*/
			
			if (statusValue != ''){
				params['is_active'] = statusValue;
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
			document.location.href = baseUrl+'/admin/company?'+jQuery.param(params);
	    };
	});
</script>>
@endsection