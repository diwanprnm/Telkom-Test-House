@extends('layouts.app')

@section('content')
<div class="main-content" >
    <section id="page-title">
        <div class="row">
            <div class="col-sm-8">
                <h1 class="mainTitle">Beranda</h1>
            </div>
            <ol class="breadcrumb">
                <li class="active">
                    <span>Beranda</span>
                </li>
            </ol>
        </div>
    </section>
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1"><em class="ti-filter"></em> Filter</a>
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
                                    <div class="form-group typeHTML">
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
									<div class="form-group statusHTML">
										<label>
											Status
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
												<option value="1" selected>Konfirmasi Registrasi oleh Admin</option>
											@else
												<option value="1">Konfirmasi Registrasi oleh Admin</option>
											@endif
											@if ($status == 2)
												<option value="2" selected>Menunggu SPB</option>
											@else
												<option value="2">Menunggu SPB</option>
											@endif
											@if ($status == 3)
												<option value="3" selected>Menunggu Pembayaran oleh User</option>
											@else
												<option value="3">Menunggu Pembayaran oleh User</option>
											@endif
											@if ($status == 4)
												<option value="4" selected>User telah melakukan Pembayaran</option>
											@else
												<option value="4">User telah melakukan Pembayaran</option>
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
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-full-width dataTable no-footer"><caption>Home</caption>
                        <thead>
                            <tr>
                                <th class="center" id="no">No</th>
                                <th class="center" id="perusahaan">Perusahaan</th>
                                <th class="center" id="pemohon">Pemohon</th>
                                <th class="center" id="perangkat">Perangkat</th>
                                <th class="center" id="tipe pengujian">Tipe Pengujian</th>
                                <th class="center" id="tanggal pengajuan">Tanggal Pengajuan</th>
                                <th class="center" id="status">Status</th>
                                <th class="center" id="aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach($data as $item)
                                <tr>
                                    <td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
                                    <td class="center">{{ $item->company->name }}</td>
                                    <td class="center">{{ $item->user->name }}</td>
                                    <td class="center">{{ $item->device->name }}</td>
                                    <td class="center">{{ $item->examinationType->name }} ({{ $item->examinationType->description }})</td>
                                    <td class="center">{{ $item->created_at }}</td>
                                    @if($item->registration_status != 1)
                                        <td class="center"><span class="label label-sm label-warning">Konfirmasi Registrasi oleh Admin</span></td>
                                    @elseif($item->registration_status == 1 && $item->function_status == 1 && $item->contract_status == 1 && $item->spb_status != 1)
                                        <td class="center"><span class="label label-sm label-info">Menunggu SPB</span></td>
                                    @elseif($item->registration_status == 1 && $item->function_status == 1 && $item->contract_status == 1 && $item->spb_status == 1 && $item->payment_status != 1)
                                        @foreach($item->media as $media)
                                            @if($media->name == 'File Pembayaran' && $media->attachment !='')
                                                @php $status_pembayaran = 1;  @endphp
                                            @else
                                                @php $status_pembayaran = 0 @endphp
                                            @endif
                                        @endforeach

                                        @if($status_pembayaran == 1)
                                            <td class="center"><span class="label label-sm label-success">User telah melakukan Pembayaran</span></td>
                                        @else
                                            <td class="center"><span class="label label-sm label-danger">Menunggu Pembayaran oleh User</span></td>
                                        @endif
                                    @else
                                        <td class="center"><span class="label label-sm label-primary">Uji Fungsi atau Tinjauan Kontrak</span></td>
                                    @endif
                                    <td class="center">
                                        <div>
                                            <a class="btn btn-wide btn-primary btn-margin" href="{{URL::to('admin/examination/'.$item->id.'/edit')}}">Update Status</a>
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
                            @php echo $data->appends(array('search' => $search,'type' => $filterType,'status' => $status))->links(); @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    // var typeHTML = document.getElementById("type").outerHTML;
    var typeHTML = '<select id="type" name="type" class="cs-select cs-skin-elastic" required>'+
                        '<option value="" disabled selected>Select...</option>'+
                        '<option value="all">All</option>'+
                        @foreach($type as $item)
                            '<option value="{{ $item->id }}">{{ $item->name }}</option>'+
                        @endforeach
                    '</select>';
    // var statusHTML = document.getElementById("status").outerHTML;
    var statusHTML = '<select id="status" name="status" class="cs-select cs-skin-elastic" required>'+
                        '<option value="" disabled selected>Select...</option>'+
                        '<option value="all">All</option>'+
                        '<option value="1">Konfirmasi Registrasi oleh Admin</option>'+
                        '<option value="2">Menunggu SPB</option>'+
                        '<option value="3">Menunggu Pembayaran oleh User</option>'+
                        '<option value="4">User telah melakukan Pembayaran</option>'+
                    '</select>';
	$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_dashboard_autocomplete/'+request.term,
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
					status:document.getElementById("status").value
                };
                document.location.href = baseUrl+'/admin?'+jQuery.param(params);
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
            
            if (statusValue != ''){
				params['status'] = statusValue;
			}
			if (typeValue != ''){
				params['type'] = typeValue;
			}
                params['search'] = search_value;
            document.location.href = baseUrl+'/admin?'+jQuery.param(params);
        };

        document.getElementById("reset-filter").onclick = function() {
            $('.cs-select').remove();
            $('.typeHTML').append(typeHTML);
            $('.statusHTML').append(statusHTML);
            [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
                new SelectFx(el);
            } );
        };
    });
</script>
@endsection
