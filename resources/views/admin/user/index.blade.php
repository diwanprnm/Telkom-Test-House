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
					<h1 class="mainTitle">User Eksternal</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>User Eksternal</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
	        	<div class="col-md-6">
	    			<a id="btn-filter" class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1"><em class="ti-filter"></em> Filter</a>
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
								<div class="col-md-4">
									<div class="form-group">
										<label>
											Perusahaan
										</label>
										<select class="form-control" id="company" name="company" class="chosen-company">
												@if ($filterCompany == '')
													<option value="" disabled selected> - Pilih Perusahaan - </option>
												@endif
												@if ($filterCompany == 'all')
													<option value="all" selected>All</option>
												@else
													<option value="all">All</option>
												@endif
											@foreach($company as $item)
												@if($item->name == $filterCompany)
													<option value="{{ $item->name }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->name }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group statusHTML">
										<label>
											Status
										</label>
										<select id="is_active" name="is_active" class="cs-select cs-skin-elastic" required>
											@if ($status == -1)
												<option value="-1" disabled selected>Select...</option>
											@endif
											@if ($status == -2)
												<option value="-2" selected>All</option>
											@else
												<option value="-2">All</option>
											@endif
											@if ($status == 1)
												<option value="1" selected>Active</option>
											@else
												<option value="1">Active</option>
											@endif
											
											@if ($status == 0)
												<option value="0" selected>Not Active</option>
											@else
												<option value="0">Not Active</option>
											@endif
										</select>
									</div>
								</div>
								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right">
		                                Filter
		                            </button>
									<button id="reset-filter" class="btn btn-wide btn-white btn-squared pull-right" style="margin-right: 10px;">
                                        Reset
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
				<div class="col-md-6 pull-right" style="margin-bottom:10px">
		            <a style=" color:white !important;" href="{{URL::to('/admin/usereks/create')}}">
						<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
							Add User
						</button>
					</a>
		        </div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption></caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nama</th>
									<th class="center" scope="col">Perusahaan</th>
									<th class="center" scope="col">Email</th>
									<th class="center" scope="col">Role</th>
                                    <th class="center" scope="col">Status</th>
                                    <th class="center" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@php $no=1; @endphp
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->name }}</td>
										@if($item->company)
											<td class="center">{{ $item->company->name }}</td>
										@else
											<td class="center">-</td>
										@endif
										<td class="center">{{ $item->email }}</td>
										<td class="center">{{ $item->role->name }}</td>
	                                    @if($item->is_active)
	                                    	<td class="center"><span class="label label-sm label-success">Active</span></td>
	                                    @else
	                                    	<td class="center"><span class="label label-sm label-warning">Not Active</span></td>
	                                    @endif
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/usereks/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												{!! Form::open(array('url' => 'admin/usereks/'.$item->id.'/softDelete')) !!}
													{!! csrf_field() !!}
													<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><em class="fa fa-times fa fa-white"></em></button>
												{!! Form::close() !!}
											</div>
										</td>
									</tr>
								@php $no++ @endphp
								@endforeach
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								@php echo $data->appends(array('search' => $search,'is_active' => $status))->links(); @endphp
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
<script type="text/javascript">
	$('#company').chosen();
	$('#company').trigger("chosen:updated");
</script>
<script type="text/javascript">
	var statusHTML = document.getElementById("is_active").outerHTML;
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_user_autocomplete/'+request.term,
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
					company:document.getElementById("company").value,
					is_active:document.getElementById("is_active").value
				};
				document.location.href = baseUrl+'/admin/usereks?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
            
			var search_value = document.getElementById("search_value").value;
			var company = document.getElementById("company");
            var status = document.getElementById("is_active");

			var companyValue = company.options[company.selectedIndex].value;
			var statusValue = status.options[status.selectedIndex].value;
			
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (statusValue != ''){
				params['is_active'] = statusValue;
			}
				params['search'] = search_value;
			document.location.href = baseUrl+'/admin/usereks?'+jQuery.param(params);
	    };

		document.getElementById("reset-filter").onclick = function() {
			$('.cs-select').remove();
            $('.statusHTML').append(statusHTML);
			$('#company').chosen().val('').trigger('chosen:updated');
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
		};
	});
</script>
@endsection