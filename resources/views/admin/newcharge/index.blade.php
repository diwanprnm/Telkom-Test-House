@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tarif Pengujian Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Data Master</span>
					</li>
					<li class="active">
						<span>Tarif Pengujian Baru</span>
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
											Tanggal Penerapan
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
									<div class="form-group statusHTML">
										<label>
											Status
										</label>
										<select id="is_implement" name="is_implement" class="cs-select cs-skin-elastic" required>
											@if ($status == '')
												<option value="" disabled selected>Select...</option>
											@endif
											
											@if ($status == 'all')
												<option value="all" selected>All</option>
											@else
												<option value="all">All</option>
											@endif
											
											@if ($status == '1')
												<option value="1" selected>Done</option>
											@else
												<option value="1">Done</option>
											@endif
											
											@if ($status == '0')
												<option value="0" selected>Not Process</option>
											@else
												<option value="0">Not Process</option>
											@endif

											@if ($status == '-1')
												<option value="-1" selected>Cancel</option>
											@else
												<option value="-1">Cancel</option>
											@endif
										</select>
									</div>
								</div>
								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right" name="submit" value="submit">
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
				@if(empty($new_charge[0]))
				<div class="col-md-6 pull-right" style="margin-bottom:10px">
					<a style=" color:white !important;" href="{{URL::to('/admin/newcharge/create')}}">
						<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >Tambah Tarif Pengujian Baru</button>
					</a>
		        </div>
		        @endif
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Examimantion Charge Status Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nama</th>
									<th class="center" scope="col">Deskripsi</th>
                                    <th class="center" scope="col">Tanggal Penerapan</th>
                                    <th class="center" scope="col">Status</th>
                                    <th class="center" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody>
						@if (count($data))
							@php
								$no=1;
							@endphp
							@foreach($data as $item)
								<tr>
									<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
									<td class="center">{{ $item->name }}</td>
									<td class="center">{{ $item->description }}</td>
									<td class="center">{{ $item->valid_from }}</td>
								@if($item->is_implement == '1')
									<td class="center"><span class="label label-sm label-success">Done</span></td>
								@elseif($item->is_implement == '0')
									<td class="center"><span class="label label-sm label-warning">Not Process</span></td>
								@elseif($item->is_implement == '-1')
									<td class="center"><span class="label label-sm label-danger">Cancel</span></td>
								@endif
									<td class="center">
										<div>
											<a href="{{URL::to('admin/newcharge/'.$item->id.'')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
										@if($item->is_implement == '0')
											{!! Form::open(array('url' => 'admin/newcharge/'.$item->id, 'method' => 'DELETE')) !!}
												{!! csrf_field() !!}
												<button class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Remove" onclick="return confirm('Are you sure want to delete ?')"><em class="fa fa-times fa fa-white"></em></button>
											{!! Form::close() !!}
										@endif
										</div>
									</td>
								</tr>
							@php
								$no++
							@endphp
							@endforeach
						@else
							<tr>
								<td class="center" colspan="6">{{ $dataNotFound }}</td>
							</tr>
						@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{$data->appends(array('search' => $search,'is_implement' => $status,'before_date' => $before_date,'after_date' => $after_date))->links()}}
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
	var statusHTML = document.getElementById('is_implement').outerHTML;
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
					is_implement:document.getElementById("is_implement").value,
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
				};
				document.location.href = baseUrl+'/admin/newcharge?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
            var status = document.getElementById("is_implement");
			var statusValue = status.options[status.selectedIndex].value;
			var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
			var beforeValue = before.value;
			var afterValue = after.value;
			
			if (statusValue != ''){
				params['is_implement'] = statusValue;
				params['search'] = search_value;
			}

			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			document.location.href = baseUrl+'/admin/newcharge?'+jQuery.param(params);
	    };

		document.getElementById("reset-filter").onclick = function() {
            $('.cs-select').remove();
            $('.statusHTML').append(statusHTML);
			$('#before_date').val(null);
			$('#after_date').val(null);
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        };
	});
</script>>
@endsection