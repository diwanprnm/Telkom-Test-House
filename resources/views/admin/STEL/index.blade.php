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
					<h1 class="mainTitle">STEL/STD</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>STEL/STD</span>
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
									<div class="form-group categoryHTML">
										<label>
											Kategori
										</label>
										<select id="category" name="category" class="cs-select cs-skin-elastic" required>
											@if ($category == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($category == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
                                            @foreach ($examLab as $dataLab)
                                            	@if ($category == $dataLab->id)
													<option value="{{$dataLab->id}}" selected>{{$dataLab->name}}</option>
												@else
													<option value="{{$dataLab->id}}">{{$dataLab->name}}</option>
												@endif
											@endforeach
									</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tahun
										</label>
										<select class="form-control" id="year" name="year" class="chosen-year">
											@if ($year == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($year == 'all')
                                                <option value="all" selected>All</option>
											@else
                                                <option value="all">All</option>
                                            @endif
                                            @foreach ($tahun as $dataTahun)
                                            	@if ($year == $dataTahun->year)
													<option value="{{$dataTahun->year}}" selected>{{$dataTahun->year}}</option>
												@else
													<option value="{{$dataTahun->year}}">{{$dataTahun->year}}</option>
												@endif
											@endforeach
									</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group isActiveHTML">
										<label>
											Status
										</label>
										<select id="is_active" name="is_active" class="cs-select cs-skin-elastic" required>
											@if ($status == '-1')
												<option value="-1" disabled selected>Select...</option>
											@endif
											@if($status == '-2')
                                                <option value="-2" selected>All</option>
											@else
                                                <option value="-2">All</option>
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
					<a style=" color:white !important;" href="{{URL::to('/admin/stel/create')}}">
						<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Tambah STEL
						</button>         
					</a>
		        </div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption></caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Id</th>
									<th class="center" scope="col">Kode</th>
									<th class="center" scope="col">Nama</th>
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Versi</th>
									<th class="center" scope="col">Tahun</th>
                                    <th class="center" scope="col">Harga</th>
                                    <th class="center" scope="col">Total</th>
                                    <th class="center" scope="col">Status</th>
                                    <th class="center" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@php $no=1; @endphp
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->id }}</td>
										<td class="center">{{ $item->code }}</td>
										<td class="center">{{ $item->name }}</td>
										<td class="center">{{ @$item->examinationLab->name }}</td>
										<td class="center">{{ $item->version }}</td>
										<td class="center">{{ $item->year }}</td>
	                                    <td class="center">@php echo number_format((float)$item->price, 0, '.', ','); @endphp</td>
	                                    <td class="center">@php echo number_format((float)$item->total, 0, '.', ','); @endphp</td>
	                                    @if($item->is_active)
	                                    	<td class="center"><span class="label label-sm label-success">Active</span></td>
	                                    @else
	                                    	<td class="center"><span class="label label-sm label-warning">Not Active</span></td>
	                                    @endif
	                                    <td class="center">
											<div>
												<a href="{{URL::to('admin/stel/'.$item->id.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												{!! Form::open(array('url' => 'admin/stel/'.$item->id, 'method' => 'DELETE')) !!}
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
								@php echo $data->appends(array('search' => $search,'category' => $category,'year' => $year,'is_active' => $status))->links(); @endphp
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
	$('#year').chosen();
	$('#year').trigger("chosen:updated");
</script>
<script type="text/javascript">
	var categoryHTML = document.getElementById('category').outerHTML;
	var isActiveHTML = document.getElementById('is_active').outerHTML;
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_stel_autocomplete/'+request.term,
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
					category:document.getElementById("category").value,
					is_active:document.getElementById("is_active").value
				};
				document.location.href = baseUrl+'/admin/stel?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
            var category = document.getElementById("category");
            var year = document.getElementById("year");
            var status = document.getElementById("is_active");
			var catValue = category.options[category.selectedIndex].value;
			var yearValue = year.options[year.selectedIndex].value;
			var statusValue = status.options[status.selectedIndex].value;
			if (catValue != ''){
				params['category'] = catValue;
			}
			if (yearValue != ''){
				params['year'] = yearValue;
			}
			if (statusValue != ''){
				params['is_active'] = statusValue;
			}
				params['search'] = search_value;
			document.location.href = baseUrl+'/admin/stel?'+jQuery.param(params);
	    };

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
			var category = document.getElementById("category");
			var year = document.getElementById("year");
            var status = document.getElementById("is_active");
			var catValue = category.options[category.selectedIndex].value;
			var yearValue = year.options[year.selectedIndex].value;
			var statusValue = status.options[status.selectedIndex].value;

			params['search'] = search_value;
			
			if (catValue != ''){
				params['category'] = catValue;
			}
			if (yearValue != ''){
				params['year'] = yearValue;
			}
			if (statusValue != ''){
				params['is_active'] = statusValue;
			}
			document.location.href = baseUrl+'/stel/excel?'+jQuery.param(params);
	    };

		document.getElementById("reset-filter").onclick = function() {
            $('.cs-select').remove();
            $('.isActiveHTML').append(isActiveHTML);
			$('.categoryHTML').append(categoryHTML);
			$('#year').chosen().val('').trigger('chosen:updated');
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        };
	});
</script>
@endsection