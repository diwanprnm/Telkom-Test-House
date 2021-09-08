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
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Buat Draft Sidang QA</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Pengujian</span>
					</li>
					<li>
						<span>Sidang QA</span>
					</li>
					<li class="active">
						<span>Buat Draft Sidang QA</span>
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
				<li class="btn tab-perangkat" data-tab="tab-perangkat">Perangkat Belum Sidang QA</li>
				<li class="btn tab-pending" data-tab="tab-pending">Perangkat Tertunda</li>
				<li class="btn tab-draft" data-tab="tab-draft">Pratinjau Draft Sidang QA (0)</li>
			</ul>

			<input type="hidden" name="hidden_tab" id="hidden_tab" value="{{ $tab }}">
			
			<div id="tab-perangkat" class="row tab-content">
		        <div class="col-md-6 pull-right">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value2" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
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
									<th class="center" scope="col">Status</th> 
									<th class="center" scope="col">Action</th>  
									<th class="center" scope="col"><input type="checkbox" onchange="checkAllPerangkat(this)"></th>  
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
											<td class="center">{{ $item->company->qs_certificate_date > date('Y-m-d') ? 'SM Eligible' : 'SM Not Eligible' }}</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang_edit_perangkat/'.$item->device->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											<td class="center"><input type="checkbox" name="chk-perangkat[]" id="chk-perangkat-{{$item->id}}" class="chk-perangkat"></td>
										</tr>
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
				
				<div class="col-md-12">
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-right" id="btn-submit-perangkat">
						Tambahkan ke draft (0)
					</button>
					<a style=" color:white !important;" href="{{URL::to('/admin/sidang')}}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Batal
						</button>
					</a>
				</div>
			</div>

			<div id="tab-pending" class="row tab-content">
		        <div class="col-md-6 pull-right">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value3" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
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
									<th class="center" scope="col">Status</th> 
									<th class="center" scope="col">Action</th>  
									<th class="center" scope="col"><input type="checkbox" onchange="checkAllPending(this)"></th>  
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data_pending)>0)
									@foreach($data_pending as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_pending->currentPage()-1)*$data_pending->perPage()) }}</td>
											<td class="center">{{ $item->examination->company->name }}</td>
											<td class="center">{{ $item->examination->device->name }}</td>
											<td class="center">{{ $item->examination->device->mark }}</td>
											<td class="center">{{ $item->examination->device->model }}</td>
											<td class="center">{{ $item->examination->device->capacity }}</td>
											<td class="center">{{ $item->examination->device->manufactured_by }}</td>
											<td class="center">{{ $item->status }}</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang_edit_perangkat/'.$item->examination->device->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											<td class="center"><input type="checkbox" name="chk-pending[]" id="chk-pending-{{$item->examination->id}}" class="chk-pending"></td>
										</tr>
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

				<div class="col-md-12">
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-right" id="btn-submit-pending">
						Tambahkan ke draft (0)
					</button>
					<a style=" color:white !important;" href="{{URL::to('/admin/sidang')}}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Batal
						</button>
					</a>
				</div>
			</div>

			<div id="tab-draft" class="row tab-content">
		        <div class="col-md-6 pull-right">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control search_value" value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
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
									<th class="center" scope="col">Status</th> 
									<th class="center" scope="col">Action</th>  
									<th class="center" scope="col"><input type="checkbox" onchange="checkAllDraft(this)"></th>  
								</tr>
							</thead>
							<tbody> 
								@php $no = 1; @endphp
								@if(count($data_draft)>0)
									@foreach($data_draft as $keys => $item)
										<tr>
											<td class="center">{{ $no+(($data_draft->currentPage()-1)*$data_draft->perPage()) }}</td>
											<td class="center">{{ $item->examination->company->name }}</td>
											<td class="center">{{ $item->examination->device->name }}</td>
											<td class="center">{{ $item->examination->device->mark }}</td>
											<td class="center">{{ $item->examination->device->model }}</td>
											<td class="center">{{ $item->examination->device->capacity }}</td>
											<td class="center">{{ $item->examination->device->manufactured_by }}</td>
											<td class="center">{{ $item->status }}</td>
											<td class="center">
												<div>
													<a href="{{URL::to('admin/sidang_edit_perangkat/'.$item->examination->device->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><em class="fa fa-pencil"></em></a>
												</div>
											</td>
											<td class="center"><input type="checkbox" name="chk-draft[]" id="chk-draft-{{$item->examination->id}}" class="chk-draft"></td>
										</tr>
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
								{{ $data_draft->appends(array('search2' => $search,'search3' => $search, 'tab' => 'tab-draft'))->links() }}
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<button type="submit" class="btn btn-wide btn-green btn-squared pull-right" id="btn-submit-draft">
						Buat draft
					</button>
					<a style=" color:white !important;" href="{{URL::to('/admin/sidang')}}">
						<button type="button" class="btn btn-wide btn-red btn-squared pull-right" style="margin-right:10px;">
						Batal
						</button>
					</a>
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
		$(".tab-perangkat").addClass('current');
		$("#tab-perangkat").addClass('current');
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
				document.location.href = baseUrl+'/admin/sidang/create?'+jQuery.param(params);
	        }
	    });

		$('.chk-perangkat').change(function() {
			count_chk_perangkat = 0;
			let checkboxes = document.getElementsByClassName('chk-perangkat');
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-perangkat[]' && checkboxes[i].checked) {
					count_chk_perangkat++;
				}
			}
			$("#btn-submit-perangkat").html("Tambahkan ke draft ("+count_chk_perangkat+")");
		});

		$('.chk-pending').change(function() {
			count_chk_pending = 0;
			let checkboxes = document.getElementsByClassName('chk-pending');
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-pending[]' && checkboxes[i].checked) {
					count_chk_pending++;
				}
			}
			$("#btn-submit-pending").html("Tambahkan ke draft ("+count_chk_pending+")");
		});

		$('.chk-draft').change(function() {
			count_chk_draft = 0;
			let checkboxes = document.getElementsByClassName('chk-draft');
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-draft[]' && checkboxes[i].checked) {
					count_chk_draft++;
				}
			}
			$(".tab-draft").html("Pratinjau Draft Sidang QA ("+count_chk_draft+")");
		});

	});

	function checkAllPerangkat(box) 
	{
		count_chk_perangkat = 0;
		let checkboxes = document.getElementsByClassName('chk-perangkat');

		if (box.checked) {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-perangkat[]') {
					checkboxes[i].checked = true;
					count_chk_perangkat++;
				}
			}
		} else {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-perangkat[]') {
					checkboxes[i].checked = false;
				}
			}
		}
		$("#btn-submit-perangkat").html("Tambahkan ke draft ("+count_chk_perangkat+")");
	}

	
	function checkAllPending(box) 
	{
		count_chk_pending = 0;
		let checkboxes = document.getElementsByClassName('chk-pending');

		if (box.checked) {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-pending[]') {
					checkboxes[i].checked = true;
					count_chk_pending++;
				}
			}
		} else {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].name == 'chk-pending[]') {
					checkboxes[i].checked = false;
				}
			}
		}
		$("#btn-submit-pending").html("Tambahkan ke draft ("+count_chk_pending+")");
	}
</script>>
@endsection