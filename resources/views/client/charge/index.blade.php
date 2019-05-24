@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.charge') }} - Telkom DDS</title>
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.charge') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
					<li class="active">{{ trans('translate.charge') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap"> 
				<div class="container clearfix">

					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-3 form-group">
								<select onchange="filter()" class="form-control" id="cmb-category">
									<option value="">{{ trans('translate.charge_choose_category') }}</option>
									@if ($category == 'all')
										<option value="all" selected>{{ trans('translate.charge_all_category') }}</option>
									@else
										<option value="all">{{ trans('translate.charge_all_category') }}</option>
									@endif
									@if ($category == 'Lab CPE')
										<option value="Lab CPE" selected>Lab CPE</option>
									@else
										<option value="Lab CPE">Lab CPE</option>
									@endif
									@if ($category == 'Lab Device')
										<option value="Lab Device" selected>Lab Device</option>
									@else
										<option value="Lab Device">Lab Device</option>
									@endif
									@if ($category == 'Lab Energi')
										<option value="Lab Energi" selected>Lab Energi</option>
									@else
										<option value="Lab Energi">Lab Energi</option>
									@endif
									@if ($category == 'Lab Kabel')
										<option value="Lab Kabel" selected>Lab Kabel</option>
									@else
										<option value="Lab Kabel">Lab Kabel</option>
									@endif
									@if ($category == 'Lab Transmisi')
										<option value="Lab Transmisi" selected>Lab Transmisi</option>
									@else
										<option value="Lab Transmisi">Lab Transmisi</option>
									@endif
									@if ($category == 'Lab EMC')
										<option value="Lab EMC" selected>Lab EMC</option>
									@else
										<option value="Lab EMC">Lab EMC</option>
									@endif
								</select>
							</div>
							
							<div class="col-md-3">
							</div>
							  
							<div class="col-md-6 col-xs-12">
								<span class="input-icon input-icon-right search-table"> 
									<input id="search_charge" type="text" placeholder="{{ trans('translate.search_charge') }}" id="form-field-17" class="form-control " value="{{ $search }}">
									<i class="ti-search"></i>
								</span>
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
						<div id="html-filter">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive font-table">
										<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
											<thead>
												<tr>
													<th class="center">{{ trans('translate.charge_no') }}</th>
													<th class="center">{{ trans('translate.charge_name') }}</th>
													<th class="center">{{ trans('translate.charge_stel') }}</th>
													<th class="center">{{ trans('translate.charge_category') }}</th>
													<th class="center">{{ trans('translate.charge_duration') }}</th>
													<th class="center">{{ trans('translate.charge_cost') }}</th>
													<th class="center">{{ trans('translate.charge_vt_cost') }}</th>
													<th class="center">{{ trans('translate.charge_ta_cost') }}</th>
												</tr>
											</thead>
											<tbody>
												<?php $no=1; if(count($data)>0){ ?>
												@foreach($data as $item)
												<tr>
													<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
													<td class="left">{{ $item->device_name }}</td>
													<td class="left">{{ $item->stel }}</td>
													<td class="center">{{ $item->category }}</td>
													<td class="center">{{ $item->duration }}</td>
													<td class="center"><?php echo number_format($item->price, 0, '.', ','); ?></td>
													<td class="center"><?php echo number_format($item->vt_price, 0, '.', ','); ?></td>
													<td class="center"><?php echo number_format($item->ta_price, 0, '.', ','); ?></td>
												</tr>
												<?php $no++ ?>
												@endforeach
												<?php }else{?>
												<div class="table-responsive font-table">
													<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
														<thead>
															<tr align="center">
																<th colspan="3" style="text-align: center;">{{ trans('translate.data_not_found') }}</th>
															</tr>
														</thead>
													</table>
												</div>
												<?php }?>
											</tbody>
										</table>
									</div>
									<div class="row">
										<div class="col-md-12 col-sm-12">
											<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
												<?php echo $data->appends(array('search' => $search, 'category' => $category))->links(); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- #content end -->
		

@endsection
 
@section('content_js')
	<script type="text/javascript" src="{{url('assets/js/search/charge.js')}}"></script>
@endsection