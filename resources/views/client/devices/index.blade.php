@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.devic_test_passed') }} - Telkom Test House</title>
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.devic_test_passed') }}</h1> 

				<ol class="breadcrumb">
					<li><a href="{{url('/')}}">{{ trans('translate.home') }}</a></li>
					<li class="active">{{ trans('translate.devic_test_passed') }}</li>
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
							  
							<div class="col-md-6 col-xs-12 form-group">
								<span class="input-icon input-icon-right search-table"> 
									<input id="search_device" type="text" placeholder="{{ trans('translate.search_dev') }}" id="form-field-17" class="form-control " value="{{ $search }}">
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
									<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1" style="font-size: smaller;">
										<caption></caption>
										<thead>
											<tr>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_no') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_name') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_device') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_mark') }}</th>
												<th class="center" scope="col">{{ trans('translate.service_device_manufactured_by') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_type') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_capacity') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_standar') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_cert_numb') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_valid_form') }}</th>
												<th class="center" scope="col">{{ trans('translate.devic_test_passed_valid_thru') }}</th>
											</tr>
										</thead>
										<tbody>
											@php $no=1; if(count($data)>0){ @endphp
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

			</div>

		</section><!-- #content end -->
		

@endsection
 
