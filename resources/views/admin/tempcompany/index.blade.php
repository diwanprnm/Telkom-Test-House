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
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><i class="ti-filter"></i> Filter</a>
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
											Status
										</label>
										<select id="is_commited" name="is_commited" class="cs-select cs-skin-elastic" required>
											@if ($status == '')
												<option value="" disabled selected>Select...</option>
											@endif
											
											@if ($status == 'all')
												<option value="all" selected>All</option>
											@else
												<option value="all">All</option>
											@endif
											
											@if ($status == 1)
												<option value="1" selected>Approve</option>
											@else
												<option value="1">Approve</option>
											@endif
											
											@if ($status == -1)
												<option value="-1" selected>Decline</option>
											@else
												<option value="-1">Decline</option>
											@endif
											
											@if ($status == 0)
												<option value="0" selected>Not Process</option>
											@else
												<option value="0">Not Process</option>
											@endif
										</select>
									</div>
								</div>
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
									<th class="center">Identitas Pengaju</th>
									<th class="center">Nama Perusahaan</th>
									<th class="center">Edit Data Perusahaan yang diminta</th>
                                    <th class="center">Status</th>
                                    <th class="center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->user->name }} ({{ $item->user->email }})</td>
										<td class="center">{{ $item->company->name }}</td>
										<td class="center">
											@if($item->name != NULL)Nama Perusahaan, @endif($item->name != NULL)
											@if($item->address != NULL)Alamat, @endif($item->address != NULL)
											@if($item->city != NULL)Kota, @endif($item->city != NULL)
											@if($item->email != NULL)Email, @endif($item->email != NULL)
											@if($item->postal_code != NULL)Kode POS, @endif($item->postal_code != NULL)
											@if($item->phone_number != NULL)No. Telp, @endif($item->phone_number != NULL)
											@if($item->fax != NULL)Faksimile, @endif($item->fax != NULL)
											@if($item->npwp_number != NULL)No. NPWP, @endif($item->npwp_number != NULL)
											@if($item->npwp_file != NULL)File NPWP, @endif($item->npwp_file != NULL)
											@if($item->siup_number != NULL)No. SIUPP, @endif($item->siup_number != NULL)
											@if($item->siup_file != NULL)File SIUPP, @endif($item->siup_file != NULL)
											@if($item->siup_date != NULL)Masa berlaku SIUPP, @endif($item->siup_date != NULL)
											@if($item->qs_certificate_number != NULL)No. Sertifikat Uji Mutu, @endif($item->qs_certificate_number != NULL)
											@if($item->qs_certificate_file != NULL)File Sertifikat Uji Mutu, @endif($item->qs_certificate_file != NULL)
											@if($item->qs_certificate_date != NULL)Masa berlaku Sertifikat Uji Mutu. @endif($item->qs_certificate_date != NULL)
										</td>
	                                    @if($item->is_commited == 1)
	                                    	<td class="center"><span class="label label-sm label-success">Approve</span></td>
	                                    @elseif($item->is_commited == -1)
	                                    	<td class="center"><span class="label label-sm label-danger">Decline</span></td>
										@else
	                                    	<td class="center"><span class="label label-sm label-warning">Not Process</span></td>
	                                    @endif
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/tempcompany/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
												{!! Form::open(array('url' => 'admin/tempcompany/'.$item->id, 'method' => 'DELETE')) !!}
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
								<?php echo $data->appends(array('search' => $search,'is_commited' => $status))->links(); ?>
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
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_temp_company_autocomplete/'+request.term,
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
					is_commited:document.getElementById("is_commited").value
				};
				document.location.href = baseUrl+'/admin/tempcompany?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
            var status = document.getElementById("is_commited");
			var statusValue = status.options[status.selectedIndex].value;
			
			if (statusValue != ''){
				params['is_commited'] = statusValue;
				params['search'] = search_value;
			}
			document.location.href = baseUrl+'/admin/tempcompany?'+jQuery.param(params);
	    };
	});
</script>>
@endsection