@extends('layouts.app')

@section('content')

<style type="text/css">
	ul.tabs{
		margin: 0px;
		padding: 0px;
		list-style: none;
		margin-bottom: 10px;
	}
	ul.tabs li{
		background: none;
		color: #222;
		display: inline-block;
		padding: 10px 15px;
		cursor: pointer;
	}

	ul.tabs li.current{
		background: #FF3E41;
		color: #ffffff;
	}

	.tab-content{
		display: none;
		/*background: #FF3E41;
		padding: 15px;*/
	}

	.tab-content.current{
		display: inherit;
	}
</style>

<?php
	$currentUser = Auth::user();
	$is_admin_mail = $currentUser['email'];
	$is_super = $currentUser['id'];
?>
<input type="hide" id="hide_device_id" name="hide_device_id">
<div class="modal fade" id="myModal_move_data" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em>Perangkat Akan Dipindahkan Menjadi Layak Uji Ulang, Mohon Berikan Keterangan!</h4>
			</div>
			
			<div class="modal-body">
				<table ><caption> </caption>
					<tr>
						<th scope="col">
							<div class="form-group">
								<label for="keterangan">Keterangan:</label>
								<textarea class="form-control" rows="5" name="keterangan" id="keterangan"></textarea>
							</div>
						</th>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table><caption> </caption>
					<tr>
						<th scope="col">
							<button type="button" id="btn-modal-move_data" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Perangkat Tidak Lulus Uji</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Perangkat Tidak Lulus Uji</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <ul class="tabs">
				<li class="btn tab-1" data-tab="tab-1">Belum Layak Uji Ulang</li>
				<li class="btn tab-2" data-tab="tab-2">Layak Uji Ulang</li>
			</ul>

			<input type="hidden" name="hidden_tab" id="hidden_tab" value="{{ $tab }}">

			<div id="tab-1" class="row tab-content">
			    <div class="col-md-6">
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
		    
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer"><caption> </caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nama Perusahaan</th>
									<th class="center" scope="col">Nama Perangkat</th>
									<th class="center" scope="col">Merk/Pabrik</th>
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas/Kecepatan</th>
									<th class="center" scope="col">Referensi Uji</th>
									<th class="center" scope="col">Buatan</th>
									<th class="center" scope="col">Tanggal Sidang</th>
									@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
										<th class="center" scope="col">Action</th>
									@endif
								</tr>
							</thead>
							<?php $no=1; ?>
							@foreach($data as $item)
								<tr>
									<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
									<td class="center">{{ $item->namaPerusahaan }}</td>
									<td class="center">{{ $item->namaPerangkat }}</td>
									<td class="center">{{ $item->merk }}</td>
									<td class="center">{{ $item->tipe }}</td>
									<td class="center">{{ $item->kapasitas }}</td>
									<td class="center">{{ $item->standarisasi }}</td>
									<td class="center">{{ $item->manufactured_by }}</td>
									<td class="center">{{ $item->qa_date }}</td>
									@if($is_super == '1' || $is_admin_mail == 'admin@mail.com')
										<td class="center">
											<div>
												<a class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Destroy" data-toggle="modal" data-target="#myModal_move_data" onclick="document.getElementById('hide_device_id').value = '{{ $item->device_id }}'"><em class="fa fa-repeat"></em></a>
											</div>
										</td>
										@endif
								</tr>
							<?php $no++ ?>
							@endforeach
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								<?php echo $data->appends(array('search' => $search, 'tab' => 'tab-1'))->links(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="tab-2" class="row tab-content">
			    <div class="col-md-6">
	    			<button id="excel2" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value2" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search2 }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
		    
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption></caption><thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Nama Perusahaan</th>
									<th class="center" scope="col">Nama Perangkat</th>
									<th class="center" scope="col">Merk/Pabrik</th>
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas/Kecepatan</th>
									<th class="center" scope="col">Referensi Uji</th>
									<th class="center" scope="col">Buatan</th>
									<th class="center" scope="col">Tanggal Sidang</th>
								</tr>
							</thead>
							<?php $no=1; ?>
							@foreach($dataAfter as $item)
								<tr>
									<td class="center">{{$no+(($dataAfter->currentPage()-1)*$dataAfter->perPage())}}</td>
									<td class="center">{{ $item->namaPerusahaan }}</td>
									<td class="center">{{ $item->namaPerangkat }}</td>
									<td class="center">{{ $item->merk }}</td>
									<td class="center">{{ $item->tipe }}</td>
									<td class="center">{{ $item->kapasitas }}</td>
									<td class="center">{{ $item->standarisasi }}</td>
									<td class="center">{{ $item->manufactured_by }}</td>
									<td class="center">{{ $item->qa_date }}</td>
								</tr>
							<?php $no++ ?>
							@endforeach
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								<?php echo $dataAfter->appends(array('search2' => $search2, 'tab' => 'tab-2'))->links(); ?>
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
	if($("#hidden_tab").val()){
		var tab_id = $("#hidden_tab").val();

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$("."+tab_id).addClass('current');
		$("#"+tab_id).addClass('current');
	}else{
		$(".tab-1").addClass('current');
		$("#tab-1").addClass('current');
	}

	jQuery(document).ready(function() {
		FormElements.init();

		$('ul.tabs li').click(function(){
			var tab_id = $(this).attr('data-tab');

			$('ul.tabs li').removeClass('current');
			$('.tab-content').removeClass('current');

			$(this).addClass('current');
			$("#"+tab_id).addClass('current');
		})

		$('#myModal_move_data').on('shown.bs.modal', function () {
		    $('#keterangan').focus();
		});

		$('#btn-modal-move_data').click(function () {
		 	var baseUrl = "{{URL::to('/')}}";
			var keterangan = document.getElementById('keterangan').value;
			var device_id = document.getElementById('hide_device_id').value;
			if(keterangan == ''){
				$('#myModal_move_data').modal('show');
				return false;
			}else{
				$('#myModal_move_data').modal('hide');
				if (confirm('Are you sure want to move this data?')) {
				    document.getElementById("overlay").style.display="inherit";	
				 	document.location.href = baseUrl+'/admin/devicenc/'+device_id+'/'+encodeURIComponent(encodeURIComponent(keterangan))+'/moveData';
				}
			}
		});

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
	$( function() {
		$( "#search_value2" ).autocomplete({
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
					search2:document.getElementById("search_value2").value,
					tab:$('.tabs .current').attr('data-tab')
				};
				document.location.href = baseUrl+'/admin/devicenc?'+jQuery.param(params);
	        }
	    });

	    $('#search_value2').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
					search2:document.getElementById("search_value2").value,
					tab:$('.tabs .current').attr('data-tab')
				};
				document.location.href = baseUrl+'/admin/devicenc?'+jQuery.param(params);
	        }
	    });

	    document.getElementById("excel").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var tab = $('.tabs .current').attr('data-tab');
			var search_value = document.getElementById("search_value").value;
			
			params['search'] = search_value;
			params['tab'] = tab;
			
			document.location.href = baseUrl+'/devicenc/excel?'+jQuery.param(params);
	    };

	    document.getElementById("excel2").onclick = function() {
            var baseUrl = "{{URL::to('/')}}";
            var params = {};
			var tab = $('.tabs .current').attr('data-tab');
			var search_value = document.getElementById("search_value2").value;
			
			params['search'] = search_value;
			params['tab'] = tab;
			
			document.location.href = baseUrl+'/devicenc/excel?'+jQuery.param(params);
	    };
	});
</script>>
@endsection