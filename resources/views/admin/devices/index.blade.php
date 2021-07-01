@extends('layouts.app')

@section('content')

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Perangkat Lulus Uji</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Perangkat Lulus Uji</span>
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
					<a class="btn btn-info pull-left" href="{{URL::to('device/excel')}}"> Export to Excel</a>
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
											Masa Berlaku Perangkat
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="{{ $before_date }}" name="before_date" id="before_date" class="form-control"/>
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
											<input type="text" placeholder="Sampai Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group categoryHTML">
										<label>
											Kategori
										</label>
										<select id="category" name="category" class="cs-select cs-skin-elastic" required>
											@if($category == '')
												<option value="" disabled selected>Select...</option>
											@endif
											@if($category == 'aktif')
                                                <option value="aktif" selected>Aktif</option>
											@else
                                                <option value="aktif">Aktif</option>
                                            @endif
											@if($category == 'aktif1')
                                                <option value="aktif1" selected>Aktif + 1</option>
											@else
                                                <option value="aktif1">Aktif + 1</option>
                                            @endif
                                            @if($category == 'all')
                                                <option value="all" selected>Aktif DAN Aktif + 1</option>
											@else
                                                <option value="all">Aktif DAN Aktif + 1</option>
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

	        <div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption> </caption>
							<thead>
								<tr>
									<th class="center" id="no">No</th>
									<th class="center" id="Nama Perusahaan">Nama Perusahaan</th>
									<th class="center" id="Nama Perangkat">Nama Perangkat</th>
									<th class="center" id="Merk Pabrik">Merk/Pabrik</th>
									<th class="center" id="Negara Pembuat">Negara Pembuat</th>
									<th class="center" id="Tipe">Tipe</th>
									<th class="center" id="Kapasitas">Kapasitas/Kecepatan</th>
									<th class="center" id="Referensi Uji">Referensi Uji</th>
									<th class="center" id="No Sertifikat">No Sertifikat</th>
									<th class="center" id="Berlaku Dari">Berlaku Dari</th>
									<th class="center" id="Berlaku Sampai">Berlaku Sampai</th>
									<th class="center" id="Kategori">Kategori</th>
									<th class="center" id="Aksi">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@php $no=1; @endphp
								@foreach($data as $item)
									<tr>
										<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
										<td class="center">{{ $item->namaPerusahaan }}</td>
										<td class="center">{{ $item->namaPerangkat }}</td>
										<td class="center">{{ $item->merk }}</td>
										<td class="center">{{ $item->manufactured_by }}</td>
										<td class="center">{{ $item->tipe }}</td>
										<td class="center">{{ $item->kapasitas }}</td>
										<td class="center">{{ $item->standarisasi }}</td>
										<td class="center">{{ $item->cert_number }}</td>
										<td class="center">{{ $item->valid_from }}</td>
										<td class="center">{{ $item->valid_thru }}</td>
										@if($item->valid_thru >= date('Y-m-d'))
											<td class="center"><span class="label label-sm label-success">Aktif</span></td>
										@else
											<td class="center"><span class="label label-sm label-warning">Aktif + 1</span></td>
										@endif
										<td class="center">
											<div>
												<a href="{{URL::to('admin/device/'.$item->deviceId.'/edit')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
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
								@php echo $data->appends(array('search' => $search,'before_date' => $before_date,'after_date' => $after_date,'category' => $category))->links(); @endphp
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
	var categoryHTML = document.getElementById('category').outerHTML
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_dev_autocomplete/'+request.term,
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
					after_date:document.getElementById("after_date").value,
					before_date:document.getElementById("before_date").value,
					category:document.getElementById("category").value
				};
				document.location.href = baseUrl+'/admin/device?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("filter").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var search_value = document.getElementById("search_value").value;
            var before = document.getElementById("before_date");
            var after = document.getElementById("after_date");
            var category = document.getElementById("category");
			var beforeValue = before.value;
			var afterValue = after.value;
			var categoryValue = category.value;
			
			if (beforeValue != ''){
				params['before_date'] = beforeValue;
			}
			if (afterValue != ''){
				params['after_date'] = afterValue;
			}
			if (categoryValue != ''){
				params['category'] = categoryValue;
			}
				params['search'] = search_value;
			document.location.href = baseUrl+'/admin/device?'+jQuery.param(params);
	    };

		document.getElementById("reset-filter").onclick = function() {
            $('.cs-select').remove();
            $('.categoryHTML').append(categoryHTML);
			$('#after_date').val(null);
			$('#before_date').val(null);
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        };
	});
</script>>
@endsection