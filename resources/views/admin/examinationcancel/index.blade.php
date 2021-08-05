@extends('layouts.app')

@section('content')

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Customer Request</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li class="active">
						<span>Customer Request</span>
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
	                    <em class="ti-search"></em>
	                </span>
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
									<th class="center" id="Alasan Pembatalan">Alasan Pembatalan</th>
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
										<td class="center">{{ $item->reason_cancel }}</td>
									</tr>
								@php $no++ @endphp
								@endforeach
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								@php echo $data->appends(array('search' => $search))->links(); @endphp
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
					search:document.getElementById("search_value").value
				};
				document.location.href = baseUrl+'/admin/examinationcancel?'+jQuery.param(params);
	        }
	    });
	});
</script>>
@endsection