@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.calibration_charge') }} - Telkom Test House</title>
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.calibration_charge') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
					<li><a href="#">{{ trans('translate.charge') }}</a></li>
					<li class="active">{{ trans('translate.calibration_charge') }}</li>
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
							<div class="col-md-6">
							</div>
							<a class="btn btn-default pull-right" style="margin-right: 1.25rem" href="{{URL::to('CalibrationChargeclient')}}">Reset <em class="fa fa-refresh"></em></a>
							<div class="col-md-4 col-xs-12 pull-right">
								<span class="input-icon input-icon-right search-table"> 
									<input id="search_calibration_charge" type="text" placeholder="{{ trans('translate.search_charge') }}" id="form-field-17" class="form-control " value="{{ $search }}">
									<em class="ti-search"></em>
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
											<caption></caption>
											<thead>
												<tr>
													<th class="center" scope="col">{{ trans('translate.charge_no') }}</th>
													<th class="center" scope="col">{{ trans('translate.charge_name') }}</th>
													<th class="center" scope="col">{{ trans('translate.menu_reference') }}</th>
													<th class="center" scope="col">{{ trans('translate.charge_duration') }}</th>
													<th class="center" scope="col">{{ trans('translate.charge_cost') }}</th>
												</tr>
											</thead>
											<tbody>
												@php $no=1; if(count($data)>0){ @endphp
												@foreach($data as $item)
												<tr>
													<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
													<td class="left">{{ $item->device_name }}</td>
													<td class="left">{{ $item->stel }}</td>
													<td class="center">{{ $item->duration }}</td>
													<td class="center">@php echo number_format($item->price, 0, '.', ','); @endphp</td>
												</tr>
												@php $no++ @endphp
												@endforeach
												@php }else{@endphp
												<div class="table-responsive font-table">
													<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
														<caption></caption>
														<thead>
															<tr class="center">
																<th colspan="3" style="text-align: center;" scope="colgroup">{{ trans('translate.data_not_found') }}</th>
															</tr>
														</thead>
													</table>
												</div>
												@php }@endphp
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
					</div>
				</div>
			</div>
		</section><!-- #content end -->
		

@endsection
 
@section('content_js')
	<script type="text/javascript" src="{{url('assets/js/search/calibration_charge.js')}}"></script>
@endsection