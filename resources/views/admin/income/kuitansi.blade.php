@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Kuitansi</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Kuitansi</span>
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
											Tanggal
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
									<div class="form-group typeHTML">
										<label>
											Jenis Pendapatan
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
											@if($filterType == 'spb')
                                                <option value="spb" selected>Pengujian Perangkat</option>
											@else
                                                <option value="spb">Pengujian Perangkat</option>
                                            @endif
											@if($filterType == 'stel')
                                                <option value="stel" selected>Pembelian STEL</option>
											@else
                                                <option value="stel">Pembelian STEL</option>
                                            @endif
										</select>
									</div>
								</div>
							</div>
							<div class="row">
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
			@if (Session::get('message'))
				<div class="alert alert-info">
					{{ Session::get('message') }}
				</div>
				<input type="hidden" id="id_kuitansi" value="{{ Session::get('id') }}">
				<script type="text/javascript">
					var baseUrl = "{{URL::to('/')}}";
					var id = document.getElementById("id_kuitansi").value;
					window.open(baseUrl+'/cetakKuitansi/'+id);
				</script>
				<script>
					window.close();
				</script>
			@endif
			<div class="row">
				<div class="col-md-6 pull-right" style="margin-bottom:10px">
		        </div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>(Kuitansi) Invoice of Income Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col"></th>
									<th class="center" scope="col">Nomor</th>
									<th class="center" scope="col">Sudah diterima dari</th>
									<th class="center" scope="col">Banyak Uang</th>
									<th class="center" scope="col">Untuk Pembayaran</th>
									<th class="center" scope="col">Tanggal</th>
									<th class="center" scope="col"></th>
								</tr>
							</thead>
							<tbody>
						@if (count($data))
							@php
								$no=1;
							@endphp
							@foreach($data as $item)
								<tr>
									<td class="center">{{ $no+(($data->currentPage()-1)*$data->perPage()) }}</td>
									<td class="center">{{ $item->number }}</td>
									<td class="center">{{ $item->from }}</td>
									<td class="center">{{ number_format($item->price, 0, '.', ',') }}</td>
									<td class="center">{{ $item->for }}</td>
									<td class="center">
										{{ date('j', strtotime($item->kuitansi_date))." ".strftime('%B %Y', strtotime($item->kuitansi_date)) }}
									</td>
									<td class="center">
										<div>
											<a href="{{URL::to('cetakKuitansi/'.$item->id.'')}}" target="_blank" rel="noopener" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
										</div>
									</td>
								</tr>
							@php
								$no++
							@endphp
							@endforeach
						@else
							<tr>
								<td class="center" colspan="7">{{ $dataNotFound }}</td>
							</tr>
						@endif
							
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{$data->appends(array('search' => $search,'before_date' => $before_date,'after_date' => $after_date,'type' => $filterType,'sort_by' => $sort_by,'sort_type' => $sort_type))->links()}}
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
	var typeHTML = 	'<select id="type" name="type" class="cs-select cs-skin-elastic" required>'+
						'<option value="" disabled selected>Select...</option>'+
						'<option value="all">All</option>'+
						'<option value="spb">Pengujian Perangkat</option>'+
						'<option value="stel">Pembelian STEL</option>'+
					'</select>'
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
					type:document.getElementById("type").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,

				};
				document.location.href = baseUrl+'/admin/kuitansi?'+jQuery.param(params);
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
            var type = document.getElementById("type");
			var typeValue = type.options[type.selectedIndex].value;

			params['search'] = search_value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}

			document.location.href = baseUrl+'/admin/kuitansi?'+jQuery.param(params);
	    };

		document.getElementById("reset-filter").onclick = function() {
            $('.cs-select').remove();
            $('.typeHTML').append(typeHTML);
			$('#after_date').val(null);
			$('#before_date').val(null);
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        };

	});
</script>
@endsection