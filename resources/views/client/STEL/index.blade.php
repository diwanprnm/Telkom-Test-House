@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>STEL - Telkom Test House</title>
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>STEL</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
					<li><a href="#">{{ trans('translate.menu_ref') }}</a></li>
					<li class="active">STEL</li>
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
								<select onchange="filter()" class="form-control cs-select cs-skin-elastic" id="cmb-category">
									<option value="">{{ trans('translate.stel_choose_category') }}</option>
									@if ($type == 'all')
										<option value="all" selected>{{ trans('translate.stel_all_category') }}</option>
									@else
										<option value="all">{{ trans('translate.stel_all_category') }}</option>
									@endif
									@foreach ($examLab as $dataLab)
										@if ($dataLab->id == $type)
											<option value="{{$dataLab->id}}" selected>{{$dataLab->name}}</option>
										@else
											<option value="{{$dataLab->id}}">{{$dataLab->name}}</option>
										@endif
									@endforeach
								</select>
							</div>
							  
							<div class="col-md-3">
							</div>
							
							<div class="col-md-6 col-xs-12">
								<span class="input-icon input-icon-right search-table"> 
									<input id="search_stel" type="text" placeholder="{{ trans('translate.search_STEL') }}" id="form-field-17" class="form-control " value="{{ $search }}">
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
												<th class="center" scope="col">{{ trans('translate.stel_no') }}</th>
												<th class="center" scope="col">{{ trans('translate.stel_name') }}</th>
												<th class="center" scope="col">{{ trans('translate.stel_code') }}</th>
												<th class="center" scope="col">{{ trans('translate.stel_price') }}</th>
												<th class="center" scope="col">{{ trans('translate.stel_version') }}</th>
												<th class="center" scope="col">{{ trans('translate.stel_category') }}</th>
											</tr>
										</thead>
										<tbody>
											@php $no=1; if(count($data)>0){ @endphp

											@if(!empty($data))
											@foreach($data as $item)
											<tr>
												<td class="center">{{$no+(($data->currentPage()-1)*$data->perPage())}}</td>
												<td class="left">{{ $item->name }}</td>
												<td class="left">{{ $item->code }}</td>
												<td class="center">@php echo number_format((float)$item->price, 0, '.', ','); @endphp</td>
												<td class="center">{{ $item->version }}</td>
												<td class="center">{{ @$item->examinationLab->name }}</td>
											</tr>
											@php $no++ @endphp
											@endforeach
											@endif
											@php }else{@endphp
											<div class="table-responsive font-table">
												<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
													<caption></caption>
													<thead>
														<tr class="center">
															<th colspan="3" style="text-align: center;" scope="col">{{ trans('translate.data_not_found') }}</th>
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
											@php echo $data->appends(array('search' => $search,'type' => $type))->links(); @endphp
										</div>
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
	<script type="text/javascript" src="{{url('assets/js/search/stel.js')}}"></script>
@endsection