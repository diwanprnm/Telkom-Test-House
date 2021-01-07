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
					<li>
						<span>Tarif Pengujian Baru</span>
					</li>
					<li class="active">
						<span>Detail</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/newcharge/'.$charge->id, 'method' => 'PUT')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Tarif Baru
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nama *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama" value="{{ $charge->name }}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Penerapan *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="valid_from" class="form-control" value="{{ $charge->valid_from }}" required="">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Deskripsi
									</label>
									<textarea type="text" name="description" class="form-control" placeholder="Deskripsi ...">{{ $charge->description }}</textarea>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_implement" class="cs-select cs-skin-elastic" required>
										@if($charge->is_implement == '1')
											<option value="0">Update</option>
											<option value="1" selected>Implement</option>
											<option value="-1">Cancel</option>
										@elseif($charge->is_implement == '0')
											<option value="" disabled>Not Process</option>
											<option value="0">Update</option>
											<option value="1">Implement</option>
											<option value="-1">Cancel</option>
										@elseif($charge->is_implement == '-1')
											<option value="0">Update</option>
											<option value="1">Implement</option>
											<option value="-1" selected>Cancel</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
	                            @if($charge->is_implement == '0')
		                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left" name="submit" value="submit">
		                                Submit
		                            </button>
		                        @endif
								<a style=" color:white !important;" href="{{URL::to('/admin/newcharge')}}">
									<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
									Cancel
									</button>
								</a>
	                        </div>
						</div>
						
					</fieldset>
				{!! Form::close() !!}
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
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
											@foreach ($labs as $lab)
												<option value="{{$lab->description}}" @if ($category == $lab->description) selected @endif >{{$lab->description}}</option>
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
				@if($charge->is_implement == 0)
				<div class="col-md-6 pull-right" style="margin-bottom:10px">
					<a style=" color:white !important;" href="{{URL::to('/admin/newcharge/'.$charge->id.'/createDetail')}}">
						<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >Tambah Tarif Pengujian Baru</button>
					</a>
		        </div>
		        @endif
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Examination charge table list</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nama Perangkat</th>
									<th class="center" scope="col">Referensi Uji</th>
									<th class="center" scope="col">Kategori</th>
									<th class="center" scope="col">Durasi (Hari)</th>
									<th class="center" scope="col">Biaya QA (Rp.)</th>
									<th class="center" scope="col">Biaya VT (Rp.)</th>
									<th class="center" scope="col">Biaya TA (Rp.)</th>
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
									<td class="center">{{ $item->device_name }}</td>
									<td class="center">{{ $item->stel }}</td>
									<td class="center">{{ $item->category }}</td>
									<td class="center">{{ number_format($item->duration, 0, '.', ',') }}</td>
									<td class="center">{{ number_format($item->new_price, 0, '.', ',') }}</td>
									<td class="center">{{ number_format($item->new_vt_price, 0, '.', ',') }}</td>
									<td class="center">{{ number_format($item->new_ta_price, 0, '.', ',') }}</td>
									<td class="center">
										<div>
											<a href="{{URL::to('admin/newcharge/'.$charge->id.'/editDetail/'.$item->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
											@if($charge->is_implement == 0)
											{!! Form::open(array('url' => 'admin/newcharge/'.$charge->id.'/deleteDetail/'.$item->id, 'method' => 'POST')) !!}
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
									<td class="center" colspan="9">{{ $dataNotFound }}</td>
								</tr>
						@endif
								
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{$data->appends(array('search' => $search,'category' => $category))->links()}}
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
					url: 'adm_newcharge_autocomplete/'+request.term,
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
					category:document.getElementById("category").value
				};
				document.location.href = baseUrl+'/admin/newcharge/{{$charge->id}}?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
            var category = document.getElementById("category");
			var catValue = category.options[category.selectedIndex].value;
			if (catValue != ''){
				params['category'] = catValue;
			}
				params['search'] = search_value;
			document.location.href = baseUrl+'/admin/newcharge/{{$charge->id}}?'+jQuery.param(params);
	    };
	});
</script>
@endsection