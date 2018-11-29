@extends('layouts.app')

@section('content')

<?php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
?>

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Pengujian</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Pengujian</span>
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
	    			<!--<a class="btn btn-info pull-left" id="excel" href="{{URL::to('examination/excel')}}"> Export to Excel</a> -->
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
											Tipe Pengujian
										</label>
										<select id="type" name="type" class="cs-select cs-skin-elastic" required>
											@if($filterType == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($filterType == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@foreach($type as $item)
												@if($item->id == $filterType)
													<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->id }}">{{ $item->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tahap Pengujian
										</label>
										<select id="status" name="status" class="cs-select cs-skin-elastic" required>
											@if ($status == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($status == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@if ($status == 1)
												<option value="1" selected>Registrasi</option>
											@else
												<option value="1">Registrasi</option>
											@endif
											@if ($status == 2)
												<option value="2" selected>Uji Fungsi</option>
											@else
												<option value="2">Uji Fungsi</option>
											@endif
											@if ($status == 3)
												<option value="3" selected>Tinjauan Kontrak</option>
											@else
												<option value="3">Tinjauan Kontrak</option>
											@endif
											@if ($status == 4)
												<option value="4" selected>SPB</option>
											@else
												<option value="4">SPB</option>
											@endif
											@if ($status == 5)
												<option value="5" selected>Pembayaran</option>
											@else
												<option value="5">Pembayaran</option>
											@endif
											@if ($status == 6)
												<option value="6" selected>Pembuatan SPK</option>
											@else
												<option value="6">Pembuatan SPK</option>
											@endif
											@if ($status == 7)
												<option value="7" selected>Pelaksanaan Uji</option>
											@else
												<option value="7">Pelaksanaan Uji</option>
											@endif
											@if ($status == 8)
												<option value="8" selected>Laporan Uji</option>
											@else
												<option value="8">Laporan Uji</option>
											@endif
											@if ($status == 9)
												<option value="9" selected>Sidang QA</option>
											@else
												<option value="9">Sidang QA</option>
											@endif
											@if ($status == 10)
												<option value="10" selected>Penerbitan Sertifikat</option>
											@else
												<option value="10">Penerbitan Sertifikat</option>
											@endif
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal SPK Dikeluarkan
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
						@foreach($data as $item)
							<div class="panel panel-default" style="border:solid; border-width:1px">
	  							<div class="panel-body">
	  								<div id="wizard" class="swMain">
										<!-- start: WIZARD SEPS -->
										<ul>
											<li>
												@if($item->registration_status == '1')
													<a href="#step-1" class="done">
												@else
													<a href="#step-1" class="done wait">
												@endif
													<div class="stepNumber">
														1
													</div>
													<span class="stepDesc"><small> Registrasi </small></span>
												</a>
											</li>
											<li>
												@if($item->registration_status == '1' && $item->function_status != '1')
													<a href="#step-2" class="done wait">
												@elseif($item->registration_status == '1' && 
												$item->function_status == '1')
													<a href="#step-2" class="done">
												@else
													<a href="#step-2">
												@endif
													<div class="stepNumber">
														2
													</div>
													<span class="stepDesc"><small> Uji Fungsi </small></span>
												</a>
											</li>
											<li>
												@if($item->registration_status == '1' && 
												$item->function_status == '1' && $item->contract_status != '1')
													<a href="#step-3" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' &&
												$item->contract_status == '1')
													<a href="#step-3" class="done">
												@else
													<a href="#step-3">
												@endif
													<div class="stepNumber">
														3
													</div>
													<span class="stepDesc"><small> Tinjauan Kontrak </small></span>
												</a>
											</li>
											<li>
												@if($item->registration_status == '1' && $item->function_status == '1' && 
												$item->contract_status == '1' && $item->spb_status != '1')
													<a href="#step-4" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && 
												$item->spb_status == '1')
													<a href="#step-4" class="done">
												@else
													<a href="#step-4">
												@endif
													<div class="stepNumber">
														4
													</div>
													<span class="stepDesc"><small> SPB </small></span>
												</a>
											</li>
											<li>
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && 
												$item->spb_status == '1' && $item->payment_status != '1')
													<a href="#step-5" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && 
												$item->payment_status == '1')
													<a href="#step-5" class="done">
												@else
													<a href="#step-5">
												@endif
													<div class="stepNumber">
														5
													</div>
													<span class="stepDesc"><small> Pembayaran </small></span>
												</a>
											</li>
											<li>
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && 
												$item->payment_status == '1' && $item->spk_status != '1')
													<a href="#step-6" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && 
												$item->spk_status == '1')
													<a href="#step-6" class="done">
												@else
													<a href="#step-6">
												@endif
													<div class="stepNumber">
														6
													</div>
													<span class="stepDesc"><small> Pembuatan SPK </small></span>
												</a>
											</li>
											<li>
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && 
												$item->spk_status == '1' && $item->examination_status != '1')
													<a href="#step-7" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && 
												$item->examination_status == '1')
													<a href="#step-7" class="done">
												@else
													<a href="#step-7">
												@endif
													<div class="stepNumber">
														7
													</div>
													<span class="stepDesc"><small> Pelaksanaan Uji </small></span>
												</a>
											</li>
											<li>
												@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && 
												$item->examination_status == '1' && $item->resume_status != '1')
													<a href="#step-8" class="done wait">
												@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && 
												$item->resume_status == '1')
													<a href="#step-8" class="done">
												@else
													<a href="#step-8">
												@endif
													<div class="stepNumber">
														8
													</div>
													<span class="stepDesc"><small> Laporan Uji </small></span>
												</a>
											</li>
											@if($item->examination_type_id !='2' && $item->examination_type_id !='3' && $item->examination_type_id !='4')
												<li>
													@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && 
													$item->resume_status == '1' && $item->qa_status != '1')
														<a href="#step-9" class="done wait">
													@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && 
													$item->qa_status == '1')
														<a href="#step-9" class="done">
													@else
														<a href="#step-9">
													@endif
														<div class="stepNumber">
															9
														</div>
														<span class="stepDesc"><small> Sidang QA </small></span>
													</a>
												</li>
											
												<li>
													@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && 
													$item->qa_status == '1' && $item->certificate_status != '1')
														<a href="#step-10" class="done wait">
													@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && $item->qa_status == '1' &&
													$item->certificate_status == '1')
														<a href="#step-10" class="done">
													@else
														<a href="#step-10">
													@endif
														<div class="stepNumber">
															10
														</div>
														<span class="stepDesc"><small> Penerbitan Sertifikat </small></span>
													</a>
												</li>
											@endif
										</ul>
										
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
															<td>Tipe Pengujian:</td>
															<td>
																{{ $item->examinationType->name }} ({{ $item->examinationType->description }})
															</td>
														</tr>
														<tr>
															<td>Pemohon:</td>
															<td>
																{{ $item->user->name }}
															</td>
														</tr>
														<tr>
															<td>Perusahaan:</td>
															<td>
																{{ $item->company->name }}
															</td>
														</tr>
														<tr>
															<td>Tanggal Pengajuan:</td>
															<td>
																{{ $item->created_at }}
															</td>
														</tr>
														<tr>
															<td>Perangkat:</td>
															<td>
																{{ $item->device->name }}
															</td>
														</tr>	
														<tr>
															<td>Merek:</td>
															<td>
																{{ $item->device->mark }}
															</td>
														</tr>	
														<tr>
															<td>Model / Tipe:</td>
															<td>
																{{ $item->device->model }}
															</td>
														</tr>	
														<tr>
															<td>Kapasitas:</td>
															<td>
																{{ $item->device->capacity }}
															</td>
														</tr>	
														<tr>
															<td>Serial Number:</td>
															<td>
																{{ $item->device->serial_number }}
															</td>
														</tr>	
														<tr>
															<td>Nomor Registrasi:</td>
															<td>
																{{ $item->function_test_NO }}
															</td>
														</tr>
														<tr>
															<td>Nama Lab:</td>
															<td>
																@if($item->examinationLab)
																	{{ $item->examinationLab->name }}
																@endif
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class=" pull-left">
					                        	@if($item->attachment != '')
					                        		<a class="btn btn-wide btn-primary pull-left" style="margin-left:10px" href="{{URL::to('/admin/examination/download/'.$item->id)}}"><i class="ti-download"></i> Form Uji</a>
												@endif
												
												@foreach($item->media as $item_SPB)
													@if($item_SPB->name == 'SPB')
														<a class="btn btn-wide btn-primary pull-left" style="margin-left:10px" href="{{URL::to('/admin/examination/media/download/'.$item->id.'/SPB')}}"><i class="ti-download"></i> SPB</a>
													@endif
												@endforeach
												
					                        	@if($item->examination_type_id !='2' && $item->examination_type_id !='3' && $item->examination_type_id !='4')
					                        		@if($item->device->certificate != '')
						                        		<a class="btn btn-wide btn-primary pull-left" style="margin-left:10px" href="{{URL::to('/admin/examination/media/download/'.$item->device_id.'/certificate')}}"><i class="ti-download"></i> Sertifikat</a>
						                        	@endif
					                        	@endif
					                        </div>
					                        <div class=" pull-right">
					                        	<a class="btn btn-wide btn-primary btn-margin" href="{{URL::to('admin/examination/'.$item->id.'/edit')}}">Change Status</a>
												@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
													<a class="btn btn-wide btn-primary pull-right" style="margin-left:10px" href="{{URL::to('admin/examination/harddelete/'.$item->id)}}" onclick="return confirm('Are you sure want to delete ? SPK Data in OTR will be deleted too.')">Delete</a>
												@endif
					                        	<a class="btn btn-wide btn-primary pull-right" style="margin-left:10px" href="{{URL::to('admin/examination/'.$item->id)}}">Detail</a>
					                        </div>
										</div>
									</div>
	  							</div>
							</div>
						@endforeach
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								<?php echo $data->appends(array('search' => $search,'type' => $filterType,'status' => $status,'before_date' => $before_date,'after_date' => $after_date))->links(); ?>
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
					url: 'adm_exam_autocomplete/'+request.term,
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
					type:document.getElementById("type").value,
					status:document.getElementById("status").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value
				};
				document.location.href = baseUrl+'/admin/examination?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
            var status = document.getElementById("status");
            // var comp_stat = document.getElementById("comp_stat");
            var type = document.getElementById("type");
			var statusValue = status.options[status.selectedIndex].value;
			var typeValue = type.options[type.selectedIndex].value;
			// var comp_statValue = comp_stat.options[comp_stat.selectedIndex].value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			
			if (statusValue != ''){
				params['status'] = statusValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			/*if (comp_statValue != ''){
				params['comp_stat'] = comp_statValue;
			}*/
				params['search'] = search_value;
			document.location.href = baseUrl+'/admin/examination?'+jQuery.param(params);
	    };

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
            var status = document.getElementById("status");
            // var comp_stat = document.getElementById("comp_stat");
            var type = document.getElementById("type");
			var statusValue = status.options[status.selectedIndex].value;
			var typeValue = type.options[type.selectedIndex].value;
			// var comp_statValue = comp_stat.options[comp_stat.selectedIndex].value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			
			if (statusValue != ''){
				params['status'] = statusValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
			/*if (comp_statValue != ''){
				params['comp_stat'] = comp_statValue;
			}*/
				params['search'] = search_value;
			document.location.href = baseUrl+'/examination/excel?'+jQuery.param(params);
	    };
	});
</script>
@endsection