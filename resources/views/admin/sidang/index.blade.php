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

<div class="modal fade" id="myModal_delete_detail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> Data Sidang QA Akan Dihapus, Mohon Berikan Keterangan!</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;"><caption></caption>
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
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="button" id="btn-modal-delete_detail" class="btn btn-danger" style="width:100%" data-delete-id="" ><em class="fa fa-check-square-o"></em> Submit</button>
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
					<h1 class="mainTitle">Sidang QA</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li class="active">
						<span>Sidang QA</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
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

			<ul class="tabs">
				<li class="btn tab-sidang" data-tab="tab-sidang">Daftar Sidang QA</li>
				<li class="btn tab-perangkat" data-tab="tab-perangkat">Perangkat Belum Sidang QA</li>
				<li class="btn tab-pending" data-tab="tab-pending">Perangkat Tertunda</li>
			</ul>

			<input type="hidden" name="hidden_tab" id="hidden_tab" value="{{ $tab }}">
			
			<div id="tab-sidang" class="row tab-content">
		        <div class="col-md-6">
		        <a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em> Filter</a>
					<!-- <button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button> -->
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
											<input type="text" placeholder="Dari Tanggal" value="{{ $after_date }}" name="after_date" id="after_date" class="form-control after_date"/>
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
											<input type="text" placeholder="Sampai Tanggal" value="{{ $before_date }}" name="before_date" id="before_date" class="form-control before_date"/>
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
								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right filter">
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

				<div class="col-md-6 pull-right" style="margin-bottom:10px;margin-top:20px">
					<a style=" color:white !important;" href="{{URL::to('/admin/sidang/create')}}">
					<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Buat Draft Sidang QA
					</button>         
					</a>
				</div>
	        
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Sidang Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Tanggal Sidang</th> 
									<th class="center" scope="col">Jumlah Perangkat</th> 
									<th class="center" scope="col">Jumlah Lulus</th>  
									<th class="center" scope="col">Jumlah Tidak Lulus</th>
									<th class="center" scope="col">Jumlah Tunda Hasil</th>
									<th class="center" scope="col">Status</th>
									<th class="center" colspan="4" scope="colgroup">Aksi</th>  
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data_sidang)>0)
									@foreach($data_sidang as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_sidang->currentPage()-1)*$data_sidang->perPage()) }}</td>
											<td class="center">{{ $item->date }}</td>
											<td class="center">{{ $item->jml_perangkat }}</td>
											<td class="center">{{ $item->status == 'DRAFT' ? '-' : $item->jml_comply }}</td>
											<td class="center">{{ $item->status == 'DRAFT' ? '-' : $item->jml_not_comply }}</td>
											<td class="center">{{ $item->status == 'DRAFT' ? '-' : $item->jml_pending }}</td>
											<td class="center">
												{{ $item->status }}
											</td>
											@if($item->status == 'DONE')
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang/'.$item->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Detail"><em class="fa fa-eye"></em></a>
												</div>
											</td>
											@endif
											@if($item->status == 'DRAFT')
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang/'.$item->id.'/excel')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Excel" target="_blank" rel="noopener"><em class="fa fa-file-excel-o"></em></a>
												</div>
											</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang/create/'.$item->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											@endif
											@if($item->status == 'DONE')
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang/'.$item->id.'/download')}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Print" target="_blank" rel="noopener"><em class="fa fa-file"></em></a>
												</div>
											</td>
											@endif
											@if($item->status == 'DRAFT' || $item->status == 'DONE')
											<td class="center">
												<div>
													<a class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Destroy" data-sidang-id="{{ $item->id }}"  data-toggle="modal" data-target="#myModal_delete_detail" onclick="document.getElementById('btn-modal-delete_detail').setAttribute('data-delete-id', '{{ $item->id }}')" ><em class="fa fa-trash"></em></a>
												</div>
											</td>
											@endif
											@php $icon = $item->status == 'DRAFT' ? 'fa-play' : 'fa-eject fa-rotate-90'; @endphp
											@if($item->status == 'DRAFT' || $item->status == 'ON GOING')
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang/'.$item->id.'/edit?tag='.$item->status)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Play"><em class="fa {{ $icon }}"></em></a>
												</div>
											</td>
											@endif
										</tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=9 class="center">
											Data Not Found
										</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{ $data_sidang->appends(array('before_date' => $before_date,'after_date' => $after_date, 'tab' => 'tab-sidang'))->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="tab-perangkat" class="row tab-content">
		        <div class="col-md-6 pull-right">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value2" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	            <div class="col-md-12"></div>
				<div class="col-md-6 pull-right" style="margin-bottom:10px;margin-top:20px">
					<a style=" color:white !important;" href="{{URL::to('/admin/sidang/create')}}">
					<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Buat Draft Sidang QA
					</button>         
					</a>
				</div>

				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Device Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Perusahaan</th> 
									<th class="center" scope="col">Perangkat</th> 
									<th class="center" scope="col">Merek</th>  
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas</th>
									<th class="center" scope="col">Negara Pembuat</th> 
									<th class="center" scope="col">Hasil Uji</th> 
									<th class="center" scope="col">Status</th> 
									<th class="center" scope="col">Aksi</th> 
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data_perangkat)>0)
									@foreach($data_perangkat as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_perangkat->currentPage()-1)*$data_perangkat->perPage()) }}</td>
											<td class="center">{{ $item->company->name }}</td>
											<td class="center">{{ $item->device->name }}</td>
											<td class="center">{{ $item->device->mark }}</td>
											<td class="center">{{ $item->device->model }}</td>
											<td class="center">{{ $item->device->capacity }}</td>
											<td class="center">{{ $item->device->manufactured_by }}</td>
											<td class="center">{{ $item->finalResult ? $item->finalResult : '-' }}</td>
											<td class="center">{{ $item->company->qs_certificate_date > date('Y-m-d') ? 'SM Eligible' : 'SM Not Eligible' }}</td>
											<td class="center"><a href="javascript:void(0)" class="collapsible"><em class="fa fa-eye"></em></a></td>
										</tr>
										<tr class="content" style="display: none;">
											<td colspan="10" class="center">
												<table class="table table-bordered table-hover table-full-width dataTable no-footer" style="width: 100%;">
													<caption>Examination Table</caption>
													<thead>
														<tr>
															<th class="center" scope="col">No. SPK</th>
															<th class="center" scope="col">No. Laporan</th>
															<th class="center" scope="col">No. Seri</th>
															<th class="center" scope="col">Referensi Uji</th>
															<th class="center" scope="col">Tanggal Penerimaan</th>  
															<th class="center" scope="col">Tanggal Mulai Uji</th>  
															<th class="center" scope="col">Tanggal Selesai Uji</th>  
															<th class="center" scope="col">Diuji Oleh</th>  
															<th class="center" scope="col">Target Penyelesaian</th>  
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="center">{{ $item->spk_code }}</td>
															@php $no_lap = '-'; @endphp
															@foreach($item->media as $file)
																@if($file->name == 'Laporan Uji')
																	@php $no_lap = $file->no; @endphp
																@endif
															@endforeach
															<td class="center">{{ $no_lap }}</td>
															<td class="center">{{ $item->device->serial_number }}</td>
															<td class="center">{{ $item->device->test_reference }}</td>
															@php $tgl_barang = '-'; @endphp
															@foreach($item->equipmentHistory as $barang)
																@if($barang->location == 2)
																	@php $tgl_barang = $barang->action_date; @endphp
																@endif
															@endforeach
															<td class="center">{{ $tgl_barang }}</td>
															<td class="center">{{ $item->startDate ? $item->startDate : '-' }}</td>
															<td class="center">{{ $item->endDate ? $item->endDate : '-' }}</td>
															<td class="center">{{ $item->examinationLab->name }}</td>
															<td class="center">{{ $item->targetDate ? $item->targetDate : '-' }}</td>
														</tr> 
													</tbody>
												</table>
											</td>
										</tr>
										<tr class="content" style="display: none;"><td colspan="10"></td></tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=10 class="center">
											Data Not Found
										</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{ $data_perangkat->appends(array('search2' => $search,'search3' => $search, 'tab' => 'tab-perangkat'))->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="tab-pending" class="row tab-content">
		        <div class="col-md-6 pull-right">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value3" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
				<div class="col-md-12"></div>
				<div class="col-md-6 pull-right" style="margin-bottom:10px;margin-top:20px">
					<a style=" color:white !important;" href="{{URL::to('/admin/sidang/create')}}">
					<button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Buat Draft Sidang QA
					</button>         
					</a>
				</div>

				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Device Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th> 
									<th class="center" scope="col">Perusahaan</th> 
									<th class="center" scope="col">Perangkat</th> 
									<th class="center" scope="col">Merek</th>  
									<th class="center" scope="col">Tipe</th>
									<th class="center" scope="col">Kapasitas</th>
									<th class="center" scope="col">Negara Pembuat</th> 
									<th class="center" scope="col">Hasil Uji</th> 
									<th class="center" scope="col">Status</th> 
									<th class="center" scope="col">Aksi</th> 
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data_pending)>0)
									@foreach($data_pending as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_pending->currentPage()-1)*$data_pending->perPage()) }}</td>
											<td class="center">{{ $item->company->name }}</td>
											<td class="center">{{ $item->device->name }}</td>
											<td class="center">{{ $item->device->mark }}</td>
											<td class="center">{{ $item->device->model }}</td>
											<td class="center">{{ $item->device->capacity }}</td>
											<td class="center">{{ $item->device->manufactured_by }}</td>
											<td class="center">{{ $item->finalResult ? $item->finalResult : '-' }}</td>
											<td class="center">{{ $item->company->qs_certificate_date > date('Y-m-d') ? 'SM Eligible' : 'SM Not Eligible' }}</td>
											<td class="center"><a href="javascript:void(0)" class="collapsible"><em class="fa fa-eye"></em></a></td>
										</tr>
										<tr class="content" style="display: none;">
											<td colspan="10" class="center">
												<table class="table table-bordered table-hover table-full-width dataTable no-footer" style="width: 100%;">
													<caption>Examination Table</caption>
													<thead>
														<tr>
															<th class="center" scope="col">No. SPK</th>
															<th class="center" scope="col">No. Laporan</th>
															<th class="center" scope="col">No. Seri</th>
															<th class="center" scope="col">Referensi Uji</th>
															<th class="center" scope="col">Tanggal Penerimaan</th>  
															<th class="center" scope="col">Tanggal Mulai Uji</th>  
															<th class="center" scope="col">Tanggal Selesai Uji</th>  
															<th class="center" scope="col">Diuji Oleh</th>  
															<th class="center" scope="col">Target Penyelesaian</th>  
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="center">{{ $item->spk_code }}</td>
															@php $no_lap = '-'; @endphp
															@foreach($item->media as $file)
																@if($file->name == 'Laporan Uji')
																	@php $no_lap = $file->no; @endphp
																@endif
															@endforeach
															<td class="center">{{ $no_lap }}</td>
															<td class="center">{{ $item->device->serial_number }}</td>
															<td class="center">{{ $item->device->test_reference }}</td>
															@php $tgl_barang = '-'; @endphp
															@foreach($item->equipmentHistory as $barang)
																@if($barang->location == 2)
																	@php $tgl_barang = $barang->action_date; @endphp
																@endif
															@endforeach
															<td class="center">{{ $tgl_barang }}</td>
															<td class="center">{{ $item->startDate ? $item->startDate : '-' }}</td>
															<td class="center">{{ $item->endDate ? $item->endDate : '-' }}</td>
															<td class="center">{{ $item->examinationLab->name }}</td>
															<td class="center">{{ $item->targetDate ? $item->targetDate : '-' }}</td>														
														</tr> 
													</tbody>
												</table>
											</td>
										</tr>
										<tr class="content" style="display: none;"><td colspan="10"></td></tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=10 class="center">
											Data Not Found
										</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
								{{ $data_pending->appends(array('search2' => $search,'search3' => $search, 'tab' => 'tab-pending'))->links() }}
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
		$(".tab-sidang").addClass('current');
		$("#tab-sidang").addClass('current');
	}

	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
	  coll[i].addEventListener("click", function() {
	    this.classList.toggle("active");
	    var content = $(this).parents().parents().next()[0];
	    var content2 = $(this).parents().parents().next().next()[0];
	    if (content.style.display == "") {
	      content.style.display = "none";
	      content2.style.display = "none";
	    } else {
	      content.style.display = "";
	      content2.style.display = "";
	    }
	  });
	}

	jQuery(document).ready(function() {
		FormElements.init();

		$('ul.tabs li').click(function(){
			var tab_id = $(this).attr('data-tab');

			$('ul.tabs li').removeClass('current');
			$('.tab-content').removeClass('current');

			$(this).addClass('current');
			$("#"+tab_id).addClass('current');
		});

		$('#myModal_delete_detail').on('shown.bs.modal', function (e) {
			$('#keterangan').focus();
		});

		$('#btn-modal-delete_detail').click(function (e) {
			console.log(e.target.getAttribute('data-delete-id'))
			var baseUrl = "{{URL::to('/')}}";
			var keterangan = document.getElementById('keterangan').value;
			let idTobeDelete = e.target.getAttribute('data-delete-id')
			// var stel_sales_detail_id = document.getElementById('hide_stel_sales_detail_id').value;
			if(keterangan == ''){
				$('#myModal_delete_detail').modal('show');
				return false;
			}else{
				$('#myModal_delete_detail').modal('hide');
				if (confirm('Are you sure want to delete this data?')) {
					document.getElementById("overlay").style.display="inherit";	
					document.location.href = baseUrl+'/admin/sidang/delete/'+idTobeDelete+'/'+encodeURIComponent(encodeURIComponent(keterangan));
				}
			}
		});
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('.search_value').keydown(function(event) {
			if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search2:document.getElementById(this.id).value,
					search3:document.getElementById(this.id).value,
					tab:$('.tabs .current').attr('data-tab')
				};
				document.location.href = baseUrl+'/admin/sidang?'+jQuery.param(params);
	        }
	    });

		$('.filter').click(function(event) {
			var baseUrl = "{{URL::to('/')}}";
			var params = {
				before:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.before_date')[0].id).value,
				after:document.getElementById($(this).parent().parent().parent().parent().parent().parent().find('.after_date')[0].id).value,
				tab:$('.tabs .current').attr('data-tab'),
			};
			
			document.location.href = baseUrl+'/admin/sidang?'+jQuery.param(params);
		});

	});

	// document.getElementById("excel").onclick = function() {
	// 	var baseUrl = "{{URL::to('/')}}";
	// 	var params = {
	// 		search:document.getElementById("search_value").value,
	// 		search2:document.getElementById("search_value2").value,
	// 		search3:document.getElementById("search_value3").value,
	// 		before:document.getElementById("before_date").value,
	// 		before2:document.getElementById("before_date2").value,
	// 		before3:document.getElementById("before_date3").value,
	// 		after:document.getElementById("after_date").value,
	// 		after2:document.getElementById("after_date2").value,
	// 		after3:document.getElementById("after_date3").value,
	// 		tab:$('.tabs .current').attr('data-tab'),
	// 		payment_status:0
	// 	};
	// 	document.location.href = baseUrl+'/sidang/excel?'+jQuery.param(params);
	// };

	// document.getElementById("excel2").onclick = function() {
	// 	var baseUrl = "{{URL::to('/')}}";
	// 	var params = {
	// 		search:document.getElementById("search_value").value,
	// 		search2:document.getElementById("search_value2").value,
	// 		search3:document.getElementById("search_value3").value,
	// 		before:document.getElementById("before_date").value,
	// 		before2:document.getElementById("before_date2").value,
	// 		before3:document.getElementById("before_date3").value,
	// 		after:document.getElementById("after_date").value,
	// 		after2:document.getElementById("after_date2").value,
	// 		after3:document.getElementById("after_date3").value,
	// 		tab:$('.tabs .current').attr('data-tab'),
	// 		payment_status:1
	// 	};
	// 	document.location.href = baseUrl+'/sidang/excel?'+jQuery.param(params);
	// };

	// document.getElementById("excel3").onclick = function() {
	// 	var baseUrl = "{{URL::to('/')}}";
	// 	var params = {
	// 		search:document.getElementById("search_value").value,
	// 		search2:document.getElementById("search_value2").value,
	// 		search3:document.getElementById("search_value3").value,
	// 		before:document.getElementById("before_date").value,
	// 		before2:document.getElementById("before_date2").value,
	// 		before3:document.getElementById("before_date3").value,
	// 		after:document.getElementById("after_date").value,
	// 		after2:document.getElementById("after_date2").value,
	// 		after3:document.getElementById("after_date3").value,
	// 		tab:$('.tabs .current').attr('data-tab'),
	// 		payment_status:3
	// 	};
	// 	document.location.href = baseUrl+'/sidang/excel?'+jQuery.param(params);
	// };

	document.getElementById("reset-filter").onclick = function() {
		$('#after_date').val(null);
		$('#before_date').val(null);
	};

</script>>
@endsection