@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Rekap Pengujian Perangkat</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Rekap Pengujian Perangkat</span>
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
					<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
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
											Tanggal SPB Dikeluarkan
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
											Lab
										</label>
										<select id="lab" name="lab" class="cs-select cs-skin-elastic" required>
											@if($filterLab == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($filterLab == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
											@foreach($lab as $item)
												@if($item->id == $filterLab)
													<option value="{{ $item->id }}" selected>{{ $item->name }}</option>
												@else
													<option value="{{ $item->id }}">{{ $item->name }}</option>
												@endif
											@endforeach
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

			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<thead>
								<tr>
									<th class="center">No</th>
									<th class="center">Sumber Pendapatan</th>
									<th class="center">Nama Pemohon Pengujian</th>
									<th class="center">Tanggal</th>
									<th class="center">No. Referensi</th>
									<th class="center">Nilai</th>
									<th class="center">Nomor SPK</th>
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										@if($item->inc_type == 1)
											<td class="center">Pengujian Perangkat {{ $item->examination->device->name }}</td>
										@else
											<td class="center">Pembelian STEL</td>
										@endif
										<td class="center">{{ $item->examination->user->name }} ({{ $item->company->name }})</td>
										<td class="center">{{ $item->tgl }}</td>
										<td class="center">{{ $item->reference_number }}</td>
										<td class="center"><?php echo number_format($item->price, 0, '.', ','); ?></td>
										<td class="center">{{ $item->examination->spk_code }}</td>
									</tr>
								<?php $no++ ?>
								@endforeach
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								<?php echo $data->appends(array('search' => $search,'type' => $filterType,'status' => $status,'lab' => $filterLab,'before_date' => $before_date,'after_date' => $after_date))->links(); ?>
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
					url: 'adm_inc_autocomplete/'+request.term,
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
					lab:document.getElementById("lab").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value
				};
				document.location.href = baseUrl+'/admin/income?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
            var status = document.getElementById("status");
			var statusValue = status.options[status.selectedIndex].value;
            var lab = document.getElementById("lab");
			var labValue = lab.options[lab.selectedIndex].value;
            var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
			

			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (statusValue != ''){
				params['status'] = statusValue;
			}
			if (labValue != ''){
				params['lab'] = labValue;
			}
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
				params['search'] = search_value;
			document.location.href = baseUrl+'/admin/income?'+jQuery.param(params);
	    };

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;
            var status = document.getElementById("status");
			var statusValue = status.options[status.selectedIndex].value;
            var lab = document.getElementById("lab");
			var labValue = lab.options[lab.selectedIndex].value;
            var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
			

			if (typeValue != ''){
				params['type'] = typeValue;
			}
			if (statusValue != ''){
				params['status'] = statusValue;
			}
			if (labValue != ''){
				params['lab'] = labValue;
			}
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
				params['search'] = search_value;
			document.location.href = baseUrl+'/income/excel?'+jQuery.param(params);
	    };
	});
</script>>
@endsection