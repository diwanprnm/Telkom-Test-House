@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Penerimaan Barang</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Penerimaan Barang</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
				<div class="col-md-6">
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <i class="ti-search"></i>
	                </span>
	            </div>
	        </div>

	        @if (Session::get('error'))
				<script>
					window.close();
				    window.opener.location.reload();
				</script>
				<div class="alert alert-error alert-danger">
					{{ Session::get('error') }}
				</div>
			@endif
			
			@if (Session::get('message'))
				<script>
					window.close();
					window.opener.location.reload();
				</script>
				<div class="alert alert-info">
					{{ Session::get('message') }}
				</div>
			@endif
			
			<div class="row">
				<!-- <div class="col-md-6 pull-right" style="margin-bottom:10px">
		            <a style=" color:white !important;" href="{{URL::to('/admin/equipment/create')}}">
						<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
							Add Equipment
						</button>
					</a>
		        </div> -->
				<div class="col-md-12">
					<div class="table-responsive">
						@foreach($data as $item)
							<div class="panel panel-default" style="border:solid; border-width:1px">
	  							<div class="panel-body">
	  								<div id="wizard" class="swMain">
										<div id="step-1">
											<div class="form-group">
												<table class="table table-condensed">
													<thead>
														<tr>
															<th colspan="3">Detail Informasi</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Perangkat :</td>
															<td>
																{{ $item->examination->device->name }}
															</td>
														</tr>
														<tr>
															<td>Merek/Pabrik :</td>
															<td>
																{{ $item->examination->device->mark }}
															</td>
														</tr>
														<tr>
															<td>Model / Tipe :</td>
															<td>
																{{ $item->examination->device->model }}
															</td>
														</tr>
														<tr>
															<td>Kapasitas / Kecepatan :</td>
															<td>
																{{ $item->examination->device->capacity }}
															</td>
														</tr>
														<tr>
															<td>Negara Pembuat :</td>
															<td>
																{{ $item->examination->device->manufactured_by }}
															</td>
														</tr>
													</tbody>
												</table>
											</div>
												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
														<thead>
															<tr>
																<th class="center">Jumlah</th>
																<th class="center">Unit</th>
																<th class="center">Deskripsi</th>
																<th class="center">Lokasi</th>
																<th class="center">PIC</th>
																<th class="center">Keterangan</th>
															</tr>
														</thead>
														<tbody>
															@foreach($equipments as $equip)
																@if($equip->examination_id == $item->examination_id)
																	<tr>
																		<td class="center">{{ $equip->qty }}</td>
																		<td class="center">{{ $equip->unit }}</td>
																		<td class="center">{{ $equip->description }}</td>
																		@if($equip->location == 1)
																			<td class="center">Customer (Applicant)</td>
																		@elseif($equip->location == 2)
																			<td class="center">URel (Store)</td>
																		@else
																			<td class="center">Lab (Laboratory)</td>
																		@endif
																		<td class="center">{{ $equip->pic }}</td>
																		<td class="center">{{ $equip->remarks }}</td>
																		<!-- <td class="center">
																			<div>
																				<a href="{{URL::to('admin/equipment/'.$equip->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
																			</div>
																		</td> -->
																	</tr>
																@endif
															@endforeach
														</tbody>
													</table>
												</div>
											<div class=" pull-right">
					                        	<a class="btn btn-wide btn-primary pull-right" style="margin-left:10px" href="{{URL::to('admin/equipment/'.$item->examination_id)}}">Detail</a>
					                        </div>
					                        <div class=" pull-right">
					                        	<a class="btn btn-wide btn-primary pull-right" style="margin-left:10px" href="{{URL::to('admin/equipment/'.$item->examination_id.'/edit')}}">Update</a>
					                        </div>
										</div>
									</div>
	  							</div>
							</div>
						@endforeach
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
							<?php echo $data->appends(array('search' => $search))->links(); ?>
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
					url: 'adm_equipment_autocomplete/'+request.term,
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
					role:document.getElementById("role").value,
					is_active:document.getElementById("is_active").value
				};
				document.location.href = baseUrl+'/admin/equipment?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
            
			var search_value = document.getElementById("search_value").value;
            var company = document.getElementById("company");
            var role = document.getElementById("role");
            var status = document.getElementById("is_active");

            var companyValue = company.options[company.selectedIndex].value;
            var roleValue = role.options[role.selectedIndex].value;
			var statusValue = status.options[status.selectedIndex].value;
			
			if (companyValue != ''){
				params['company'] = companyValue;
			}
			if (roleValue != ''){
				params['role'] = roleValue;
			}
			if (statusValue != ''){
				params['is_active'] = statusValue;
			}
				params['search'] = search_value;
			document.location.href = baseUrl+'/admin/equipment?'+jQuery.param(params);
	    };
	});
</script>
@endsection