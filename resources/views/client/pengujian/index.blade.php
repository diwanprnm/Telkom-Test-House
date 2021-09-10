@php
	$currentUser = Auth::user();
@endphp
@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.examination') }} - Telkom DDBZ</title>
@php $date_STRING = date('d-m-Y'); @endphp
@section('content')
<style type="text/css">
	.radio-toolbar input[type="radio"] {
	  opacity: 0;
	  position: fixed;
	  width: 0;
	}
	.radio-toolbar label {
	    display: inline-block;
	    background-color: #ddd;
	    padding: 2%;
	    font-family: sans-serif, Arial;
	}
	.radio-toolbar input[type="radio"]:checked + label {
	    background-color:#bfb;
	    border-color: #4c4;
	}
	.radio-toolbar input[type="radio"]:focus + label {
	    border: 2px dashed #444;
	}
	.radio-toolbar label:hover {
	  background-color: #dfd;
	}
</style>
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.examination') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
					<li class="active">{{ trans('translate.examination') }}</li>
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
						<select onchange="filter()" class="form-control" id="cmb-jns-pengujian">
							<option value="">{{ trans('translate.examination_choose_type') }}</option>
							<option value="all">{{ trans('translate.examination_all_type') }}</option>
							@foreach($data_exam_type as $item)
								<option value="{{ $item->id }}">{{ $item->name }} ({{ $item->description }})</option>
							@endforeach
						</select>
					</div>
					
					<div class="col-md-3 form-group">
						<select onchange="filter()" class="form-control" id="cmb-jns-status">
							<option value="">{{ trans('translate.examination_choose_status') }}</option>
							<option value="all">{{ trans('translate.examination_all_status') }}</option>
							<option value="1">{{ trans('translate.examination_reg') }}</option>
							<option value="2">{{ trans('translate.examination_function') }}</option>
							<option value="3">{{ trans('translate.examination_contract') }}</option>
							<option value="4">{{ trans('translate.examination_spb') }}</option>
							<option value="5">{{ trans('translate.examination_payment') }}</option>
							<option value="6">{{ trans('translate.examination_spk') }}</option>
							<option value="7">{{ trans('translate.examination_exam') }}</option>
							<option value="8">{{ trans('translate.examination_report') }}</option>
							<option value="9">{{ trans('translate.examination_qa') }}</option>
							<option value="10">{{ trans('translate.examination_certificate') }}</option>
						</select>
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
						@php if(count($data)>0){ @endphp
						@foreach($data as $item)
						<div class="col-md-12 list-border-progress">
							<div class="step list-progress">
								<div class="garis garis-progress" style="{{($item->examination_type_id == '1')?'width:83%':'width:70%'}}"></div>
								<ul class="number" style="width:100%;">
									<li>
									@if($item->registration_status == '1')
										<button class="step-fill done">1</button>
									@else
										<button class="step-fill active">1</button>
									@endif
										<p>{{ trans('translate.examination_reg')}}</p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status != '1')
										<button class="step-fill active">2</button>
									@elseif($item->registration_status == '1' && 
									$item->function_status == '1')
										<button class="step-fill done">2</button>
									@else
										<button class="step-fill">2</button>
									@endif
										<p> {{ trans('translate.examination_function') }}</p>
									</li>
									<li>
									@if($item->registration_status == '1' && 
									$item->function_status == '1' && $item->contract_status != '1')
										<button class="step-fill active">3</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' &&
									$item->contract_status == '1')
										<button class="step-fill done">3</button>
									@else
										<button class="step-fill">3</button>
									@endif
										<p> {{ trans('translate.examination_contract') }}</p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status == '1' && 
									$item->contract_status == '1' && $item->spb_status != '1')
										<button class="step-fill active">4</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && 
									$item->spb_status == '1')
										<button class="step-fill done">4</button>
									@else
										<button class="step-fill">4</button>
									@endif
										<p>{{ trans('translate.examination_spb') }}</p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && 
									$item->spb_status == '1' && $item->payment_status != '1')
										<button class="step-fill active">5</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && 
									$item->payment_status == '1')
										<button class="step-fill done">5</button>
									@else
										<button class="step-fill">5</button>
									@endif
										<p>{{ trans('translate.examination_payment') }} </p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && 
									$item->payment_status == '1' && $item->spk_status != '1')
										<button class="step-fill active">6</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && 
									$item->spk_status == '1')
										<button class="step-fill done">6</button>
									@else
										<button class="step-fill">6</button>
									@endif
										<p> {{ trans('translate.examination_spk') }}</p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && 
									$item->spk_status == '1' && $item->examination_status != '1')
										<button class="step-fill active">7</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && 
									$item->examination_status == '1')
										<button class="step-fill done">7</button>
									@else
										<button class="step-fill">7</button>
									@endif
										<p>{{ trans('translate.examination_exam') }} </p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && 
									$item->examination_status == '1' && $item->resume_status != '1')
										<button class="step-fill active">8</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && 
									$item->resume_status == '1')
										<button class="step-fill done">8</button>
									@else
										<button class="step-fill">8</button>
									@endif
										<p>{{ trans('translate.examination_report') }} </p>
									</li>
									@if($item->examination_type_id == '1')
									<li>
									@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && 
									$item->resume_status == '1' && $item->qa_status != '1')
										<button class="step-fill active">9</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && 
									$item->qa_status == '1')
										<button class="step-fill done">9</button>
									@else
										<button class="step-fill">9</button>
									@endif
										<p>{{ trans('translate.examination_qa') }} </p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && 
									$item->qa_status == '1' && $item->certificate_status != '1')
										<button class="step-fill active">10</button>
									@elseif($item->registration_status == '1' && $item->function_status == '1' && $item->contract_status == '1' && $item->spb_status == '1' && $item->payment_status == '1' && $item->spk_status == '1' && $item->examination_status == '1' && $item->resume_status == '1' && $item->qa_status == '1' &&
									$item->certificate_status == '1')
										<button class="step-fill done">10</button>
									@else
										<button class="step-fill">10</button>
									@endif
										<p>{{ trans('translate.examination_certificate') }} </p>
									</li> 
									@endif
								</ul>
							</div>
							<div class="data-status">
								<table class="table table-striped">
									<caption></caption>
									<tr>
										<th colspan="3" scope="colgroup">{{ trans('translate.examination_status') }}</th>
									</tr>
									@if($item->registration_status != '0' && $item->function_status != '1')
									<tr>
										<td>{{ trans('translate.examination_function_test_date') }}</td>
										<td colspan="2"> :
											@if($item->function_date != null)
												{{ $item->function_date }} (FIX {{ trans('translate.by') }} {{ $item->function_test_PIC}}) {{ $item->function_test_reason }}
											@elseif($item->function_date == null && $item->urel_test_date != null)
												{{ $item->urel_test_date }} ({{ trans('translate.from_customer') }}) {{ $item->function_test_reason }}
											@elseif($item->urel_test_date == null && $item->deal_test_date != null && $item->function_test_date_approval == 1)
												{{ $item->deal_test_date }} (FIX {{ trans('translate.by') }} {{ $item->function_test_PIC}}) {{ $item->function_test_reason }}
											@elseif($item->urel_test_date == null && $item->deal_test_date != null && $item->function_test_date_approval == 0)
												{{ $item->deal_test_date }} ({{ trans('translate.from_te') }}) {{ $item->function_test_reason }}
											@else
												{{ $item->cust_test_date }} {{ trans('translate.from_customer') }}
											@endif
										</td>
									</tr>
									@endif
									<tr>
										<td>{{ trans('translate.examination_type') }}</td>
										<td colspan="2">: {{ $item->jns_pengujian }} ( {{ $item->desc_pengujian }} )</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_user') }}</td>
										<td colspan="2">: {{ $item->userName }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_company') }}</td>
										<td colspan="2">: {{ $item->companiesName }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_date_regist') }}</td>
										<td colspan="2">: {{ $item->created_at }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_equipment') }}</td>
										<td colspan="2">: {{ $item->nama_perangkat }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_mark') }}</td>
										<td colspan="2">: {{ $item->merk_perangkat }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_model') }}</td>
										<td colspan="2">: {{ $item->model_perangkat }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_capacity') }}</td>
										<td colspan="2">: {{ $item->kapasitas_perangkat }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_serial_number') }}</td>
										<td colspan="2">: {{ $item->serialNumber }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_number_exam_form') }}</td>
										<td colspan="2">: {{ $item->function_test_NO }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_labs_name') }}</td>
										<td colspan="2">: {{ $item->labs_name }}</td>
									</tr>
								</table>
							</div>
							<div class="option-progress right"> 
							@if($item->is_cancel == 0)
								<a class="button button-3d nomargin btn-blue btn-sky" href="{{URL::to('cetakPengujian/'.$item->id.'')}}" target="_blank">{{ trans('translate.examination_print') }}</a>
							@endif
								<a class="button button-3d nomargin btn-blue btn-sky" href="{{URL::to('pengujian/'.$item->id.'/detail')}}">{{ trans('translate.examination_detail') }}</a>
								<!-- jika is_cancel == 1, tampilkan alert ini -->
								@if($item->is_cancel == 1)
								<div class="alert" style="background-color : #ffcccc; color : #1e272e;text-align : left;">
									{{ trans('translate.cancel_warning') }} 
								</div> 
								@endif
							@if($item->is_cancel == 0)
								@if($item->registration_status == '1' && $item->function_status != '1')
									@if($item->deal_test_date == NULL)
									<a class="button button-3d download_progress_btn edit_btn nomargin btn-blue btn-sky" onclick="reSchedule('@php echo $item->id @endphp','@php echo $item->cust_test_date @endphp','1','@php echo $item->deal_test_date @endphp','@php echo $item->urel_test_date @endphp','@php echo $item->function_test_TE_temp @endphp','@php echo $item->function_test_date_temp @endphp')">{{ trans('translate.examination_reschedule_test_date') }}</a>
									@elseif($item->deal_test_date != NULL && $item->urel_test_date == NULL && $item->function_test_date_approval == 0)
									<a class="button button-3d download_progress_btn edit_btn nomargin btn-blue btn-sky" onclick="reSchedule('@php echo $item->id @endphp','@php echo $item->cust_test_date @endphp','2','@php echo $item->deal_test_date @endphp','@php echo $item->urel_test_date @endphp','@php echo $item->function_test_TE_temp @endphp','@php echo $item->function_test_date_temp @endphp')">{{ trans('translate.examination_reschedule_test_date') }}</a>
									<a class="button button-3d download_progress_btn edit_btn nomargin btn-blue btn-sky" onclick="reSchedule('@php echo $item->id @endphp','@php echo $item->cust_test_date @endphp','3','@php echo $item->deal_test_date @endphp','@php echo $item->urel_test_date @endphp','@php echo $item->function_test_TE_temp @endphp','@php echo $item->function_test_date_temp @endphp')">{{ trans('translate.examination_approve_test_date') }}</a>
									@elseif($item->urel_test_date != NULL && $item->function_date == NULL)
									<a class="button button-3d download_progress_btn edit_btn nomargin btn-blue btn-sky" onclick="reSchedule('@php echo $item->id @endphp','@php echo $item->cust_test_date @endphp','2','@php echo $item->deal_test_date @endphp','@php echo $item->urel_test_date @endphp','@php echo $item->function_test_TE_temp @endphp','@php echo $item->function_test_date_temp @endphp')">{{ trans('translate.examination_reschedule_test_date') }}</a>
									@endif
								@endif
								
								@php if($item->registration_status != 1){ @endphp
									<a class="button edit_btn button-3d nomargin btn-blue btn-sky" href="{{url('editprocess/'.$item->jns_pengujian.'/'.$item->id)}}">{{ trans('translate.examination_edit') }}</a>
								@php } @endphp

								<!-- jika function_test_TE_temp == 1, tampilkan alert ini -->
								@if($item->function_test_TE_temp == 1)
								<div class="alert" style="background-color : #ffcccc; color : #1e272e;text-align : left;">
									{{ trans('translate.uf_warning') }} {{ $item->function_test_date_temp }}. 
								</div> 
								@endif
								@php if($item->spb_status == 1 && $item->payment_status != 1){ @endphp
									<a class="button edit_btn button-3d nomargin btn-blue btn-sky" href="{{URL::to('pengujian/'.$item->id.'/pembayaran')}}">{{ trans('translate.payment_process') }}</a>
									<a class="button edit_btn button-3d nomargin btn-blue btn-sky" href="{{URL::to('pengujian/'.$item->id.'/downloadSPB')}}">{{ trans('translate.download') }} SPB</a>
								@php } @endphp

								@if($item->registration_status != 1 || $item->function_status != 1 || $item->contract_status != 1 || $item->spb_status != 1 || $item->payment_status != 1)  
									<a class="button button-3d nomargin btn-blue btn-sky" href="javascript:void(0)" onclick="return reqCancel('{{$item->id}}');">{{ trans('translate.request_cancelation') }}</a>
								@endif

								@if($item->payment_status != 1 && $item->payment_method == 2 && $item->VA_expired < date("Y-m-d H:i:s"))
									<div class="alert" style="background-color : #ffcccc; color : #1e272e;text-align : left;">
										{{ trans('translate.stel_total_expired') }} <a href="{{url('/resend_va_spb/'.$data[0]->id)}}"> {{ trans('translate.here') }} </a> {{ trans('translate.stel_total_resend') }}. 
									</div> 
								@endif

								@php if(
				  $item->registration_status == 1 &&
                  $item->function_status == 1 &&
                  $item->contract_status == 1 &&
                  $item->spb_status == 1 &&
                  $item->payment_status == 1 &&
                  $item->spk_status == 1 &&
                  $item->examination_status == 1 &&
                  $item->resume_status == 1 &&
					date('Y-m-d') >= $item->resume_date
					){ @endphp
									<a class="button button-3d edit_btn download_progress_btn nomargin btn-blue btn-sky" href="javascript:void(0)" onclick="return isTestimonial('{{ $item->nama_perangkat }}','{{ URL::to('pengujian/'.$item->id.'/downloadLaporanPengujian') }}','device', '{{$item->jns_pengujian}} ({{$item->desc_pengujian}})', '{{$item->id}}');">{{ trans('translate.download') }} {{ trans('translate.examination_report') }}</a>
								@php } @endphp
								
								@php if(
                  $item->registration_status == 1 &&
                  $item->function_status == 1 &&
                  $item->contract_status == 1 &&
                  $item->spb_status == 1 &&
                  $item->payment_status == 1 &&
                  $item->spk_status == 1 &&
                  $item->examination_status == 1 &&
                  $item->resume_status == 1 &&
                  $item->qa_status == 1 &&
                  $item->qa_passed == 1 &&
                  $item->certificate_status == 1
                ){ @endphp
									<a class="button button-3d edit_btn download_progress_btn nomargin btn-blue btn-sky" href="javascript:void(0)" onclick="return isTestimonial('{{ $item->nama_perangkat }}','{{ URL::to('pengujian/'.$item->id.'/downloadSertifikat') }}','device', '{{$item->jns_pengujian}} ({{$item->desc_pengujian}})', '{{$item->id}}');">{{ trans('translate.download') }} {{ trans('translate.certificate') }}</a>
								@php } @endphp
							@endif
							</div>
						</div>
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
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
									@php echo $data->appends(array('search' => $search,'jns' => $jns,'status' => $status))->links(); @endphp
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
		
<form id="form" role="form" method="POST" action="{{ url('/pengujian/tanggaluji') }}" aria-label="Form Tanggal Uji">
{!! csrf_field() !!}
<input type="hidden" name="hide_id_exam" id="hide_id_exam"/>
<input type="hidden" name="hide_date_type" id="hide_date_type"/>
<div class="modal fade" id="reschedule-modal-content" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> {{ trans('translate.reschedule_message') }}</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;">
					<caption></caption>
					<thead class="hidden">
						<tr>
							<th scope="col">-</th>
						</tr>
					</thead>
					<tr>
						<td>
							<div class="form-group">
								<label>
									{{ trans('translate.reschedule_date') }} *
								</label>
									<input type="text" id="cust_test_date" class="form-control datepicker" name="cust_test_date" placeholder="Tanggal ..." readonly>
									<span class="input-group-btn">
											<em class="glyphicon glyphicon-calendar"></em>
									</span>
							</div>
						</td>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;">
					<caption></caption>
					<tr>
						<th scope="col">
							<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
</form>

<form id="form" role="form" method="POST" action="{{ url('/pengujian/tanggaluji') }}" aria-label="Form Tanggal Uji 2">
{!! csrf_field() !!}
<input type="hidden" name="hide_id_exam2" id="hide_id_exam2"/>
<input type="hidden" name="hide_date_type" id="hide_date_type2"/>
<div class="modal fade" id="reschedule-modal-content2" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> {{ trans('translate.reschedule_message') }}</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;">
					<caption></caption>
					<thead class="hidden">
						<tr>
							<th scope="col">-</th>
						</tr>
					</thead>
					<tr>
						<td>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date_te1') }} *
										</label>
											<input type="text" name="deal_test_date2" id="deal_test_date2" class="form-control" placeholder="Tanggal ..." readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date_cust1') }} *
										</label>
											<input type="text" id="cust_test_date2" class="form-control" placeholder="Tanggal ..." readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date') }} *
										</label>
											<input type="text" id="urel_test_date2" class="form-control datepicker" name="urel_test_date" placeholder="Tanggal ..." readonly>
											<span class="input-group-btn">
													<em class="glyphicon glyphicon-calendar"></em>
											</span>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_reason') }} *
										</label>
										<textarea name="alasan" class="form-control" placeholder="{{ trans('translate.reschedule_reason') }} ..."></textarea>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;">
					<caption></caption>
					<thead class="hidden">
						<tr>
							<th scope="col">-</th>
						</tr>
					</thead>
					<tr>
						<td>
							<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
</form>

<form id="form" role="form" method="POST" action="{{ url('/pengujian/tanggaluji') }}" aria-label="Form Tanggal Uji 3">
{!! csrf_field() !!}
<input type="hidden" name="hide_id_exam3" id="hide_id_exam3"/>
<input type="hidden" name="hide_date_type" id="hide_date_type3"/>
<div class="modal fade" id="reschedule-modal-content3" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em> {{ trans('translate.reschedule_message_agree') }}</h4>
			</div>
			
			<div class="modal-body">
				<table style="width: 100%;">
					<caption></caption>
					<thead class="hidden">
						<tr>
							<th scope="col">-</th>
						</tr>
					</thead>
					<tr>
						<td>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date_te1') }} *
										</label>
											<input type="text" name="deal_test_date3" id="deal_test_date3" class="form-control" placeholder="Tanggal ..." readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date_cust1') }} *
										</label>
											<input type="text" id="cust_test_date3" class="form-control" placeholder="Tanggal ..." readonly>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;">
					<caption></caption>
					<tr>
						<th scope="col">
							<button type="submit" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
						</th>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
</form>

<div id="modal_request_cancel" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">

	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h2 class="modal-title">{{ trans('translate.attention') }}</h2>
		</div>
		<div class="modal-body pre-scrollable">
			<div class="row">
				<h4>{{ trans('translate.reason_cancelation') }} : </h4>
				<div class="form-group">
					@php $no = 1; @endphp
					@foreach($data_cancel_reason as $item)
					<div class="material-group-item">
						<input type="radio" class="reason" name="reason" value="{{ $item->id }}||{{ $item->name }}" placeholder="{{ $item->name }}" id="reason-{{ $item->id }}"> 
						<label for="reason-{{ $item->id }}" style="font-weight:normal;">{{ $item->name }}</label>
					</div>
					@php $no++; @endphp
					@endforeach
					<div class="material-group-item">
						<input type="radio" class="reason" name="reason" value="0" placeholder="{{ trans('translate.other_reason') }}" id="reason-0"> 
						<label for="reason-0" style="font-weight:normal;">{{ trans('translate.other_reason') }}</label>
					</div>
				</div>
				<div class="form-group other_reason" style="display:none;">
					<label>{{ trans('translate.other_reason') }}</label>
					<input type="text" id="other_reason" name="other_reason" placeholder ="{{ trans('translate.other_reason') }}" class="form-control">
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="button button3d btn-sky" data-dismiss="modal">Cancel</button>
			<button id="btn-cancel" type="button" class="button button3d btn-sky" data-dismiss="modal">Submit</button>
		</div>
	</div>

	</div>
</div>

<input type="hidden" name="link" id="link"> 
   <div id="modal_kuisioner" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Survey Kepuasan Kastamer Eksternal</h4>
          </div>
          <div class="modal-body pre-scrollable">
            <form id="form-kuisioner1">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Tanggal</label>
                			<input type="text" id="tanggal" name="tanggal" placeholder="DD/MM/YYYY" class="form-control" value="@php echo $date_STRING;@endphp" readonly required>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" id="user_name" name="user_name" placeholder="-" class="form-control" value="{{ $currentUser->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <input type="text" id="company_name" name="company_name" placeholder="-" class="form-control" value="{{ $currentUser->company->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" id="company_phone" name="company_phone" placeholder="-" value="{{ $currentUser->company->phone }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Jenis Pengujian</label>
                            <input type="text" id="exam_type1" name="exam_type" placeholder="-" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" id="user_email" name="user_email" placeholder="-" value="{{ $currentUser->email }}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" id="user_phone" name="user_phone" placeholder="-" value="{{ $currentUser->phone }}" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h2>Harap isikan penilaian anda terhadap Layanan QA/TA/VT</h2>
                    <p>Kustomer diharapkan dapat untuk beberapa kriteria yang diajukan. Nilai tersebut merupakan nilai kustomer  berikan mengenai ekspetasi setuju dan PT. Telkom.

                    Skala pemberian nilai adalah 1 - 7 dengan nilai 7 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat memberikan dengan angka bulat.
                    </p>
                </div>
                <div class="row">
					<table id="table_kuisioner" style="width:100%; padding: 2px; border: 1px">
					  <caption></caption>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kriteria</th> 
                        <th scope="col">Nilai Ekspetasi</th>
                        <th scope="col">NIlai Performasi</th>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Pihak UREL (User Relation) mampu menjadi jembatan antara Kastamer dan Yesy Engineer Telkom.</td> 
                        <td><input type="number" min="1" max="7" name="quest1_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest1_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Proses pelayanan pengujian secara keseluruhan (sejak pengajuan hingga pelaporan) mudah dimengerti oleh Kastemer.</td> 
                        <td><input type="number" min="1" max="7" name="quest2_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest2_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>Pihak UREL memberika informasi serta melakukan pengecekan kelengkapan mengenai berkas-berkas yang harus disiapkan.</td> 
                        <td><input type="number" min="1" max="7" name="quest3_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest3_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>4</td>
                        <td>Setiap lini proses (sejak pengajuan hingga pelaporan) dilakukan dengan cepat.</td> 
                        <td><input type="number" min="1" max="7" name="quest4_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest4_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>5</td>
                        <td>Pihak UREL memberikan informasi yang dibutuhkan oleh Kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest5_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest5_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                    </table>
                </div>
                <div class="row">
                    <p>Menurut Anda, dalam proses pengajuan hingga pelaporan, tahap apa yang sebaiknya ditingkatkan oleh PT. Telkom? Dan mengapa harus ditingkatkan?</p>
                    <textarea name="quest6" class="form-control" placeholder="Jawaban"></textarea>
                </div>
                <div class="row">
                    <p>Pada tahap ini, silahkan mengisi nilai dengan sekala 1-7 untuk nilai ekspetasi awal dan nilai performansi. Kastemer diharapkan mengisi kolom nilai dan setiap kriteria, serta nilai performansi/kenyataan dari setiap kriteria</p>
                    <p>Nilai 7 adalah penilaian Sangat Baik atau Sangat Setuju dan nilai 1 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat memberikan nilai dengan angka bulat.</p>
                    <table id="table_kuisioner" style="width:100%; padding: 2px; border: 1px" >
					  <caption></caption>
					  <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kriteria</th> 
                        <th scope="col">Nilai Ekspetasi</th>
                        <th scope="col">Nilai Performasi</th>
                      </tr>
                      <tr>
                        <td>7</td>
                        <td>Kastemer percaya pada kualitas pengujian yang dilakukan oleh Telkom.</td> 
                        <td><input type="number" min="1" max="7" name="quest7_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest7_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>8</td>
                        <td>Kastemer merasa pihak UREL faham dan terpercaya.</td> 
                        <td><input type="number" min="1" max="7" name="quest8_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest8_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>9</td>
                        <td>Kastemer merasa pihak UREL sudah melakukan pemeriksaan kelengkapan administrasi dengan kinerja yang baik.</td> 
                        <td><input type="number" min="1" max="7" name="quest9_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest9_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>10</td>
                        <td>Kastemer measa aman sewaktu melakukan transaksi dengan pihak Telkom terutama pihak UREL.</td> 
                        <td><input type="number" min="1" max="7" name="quest10_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest10_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>11</td>
                        <td>Kastemer merasa Engineer Telkom sudah berpengalaman.</td> 
                        <td><input type="number" min="1" max="7" name="quest11_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest11_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>12</td>
                        <td>Alat ukur yang digunakan oleh pihak Telkom berkualitas, baik, dan akurat.</td> 
                        <td><input type="number" min="1" max="7" name="quest12_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest12_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>13</td>
                        <td>Laboratorium yang digunakan oleh pihak Telkom dalam keadaan bersih dan memenuhi Standar Laboratorium.</td> 
                        <td><input type="number" min="1" max="7" name="quest13_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest13_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>14</td>
                        <td>Tarif Pengujian yang ditetapkan oleh pihak PT. Telkom sesuai dan bersaing dengan harga pasar.</td> 
                        <td><input type="number" min="1" max="7" name="quest14_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest14_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>15</td>
                        <td>Pihak UREL yang melayani kastamer berpakaian rapih dan sopan.</td> 
                        <td><input type="number" min="1" max="7" name="quest15_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest15_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>16</td>
                        <td>Kontor Telkom DDB dalam kondisi nyaman, bersih dan sudah sesuai kondisi keseluruhannya.</td> 
                        <td><input type="number" min="1" max="7" name="quest16_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest16_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>17</td>
                        <td>Pihak Telkom mengembalikan barang/perangkat yang diujikan dalam keadaan baik seperti awal.</td> 
                        <td><input type="number" min="1" max="7" name="quest17_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest17_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>18</td>
                        <td>Sertifikat yang diterima oleh kastemer tidak mengalami kesalahan informasi.</td> 
                        <td><input type="number" min="1" max="7" name="quest18_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest18_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>19</td>
                        <td>Pihak Telkom DDB terutama pihak UREL yang melayani proses pengajuan hingga pelaporan sudah memahami kebutuhan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest19_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest19_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>20</td>
                        <td>Proses pengujian secara keseluruhan tidak memakan durasi waktu yang lama.</td> 
                        <td><input type="number" min="1" max="7" name="quest20_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest20_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>21</td>
                        <td>Pihak UREL cepat dan tepat dalam merespon keluhan yang diberikan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest21_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest21_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>22</td>
                        <td>Pihak UREL tanggapan dalam membantu permasalahan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest22_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest22_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>23</td>
                        <td>Engineer tanggapan pada permasalahan yang dihadapi kastamer selama proses pengajuan hingga pelaporan.</td> 
                        <td><input type="number" min="1" max="7" name="quest23_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest23_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>24</td>
                        <td>Pihak UREL mudah dihubungi dan tanggap pada segala pertanyaan yang diajukan kastamer terkait pengujian perangkat.</td> 
                        <td><input type="number" min="1" max="7" name="quest24_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest24_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>25</td>
                        <td>Pihak UREL bersikap ramah dan profesional terhadap kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest25_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest25_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                    </table>
                </div>
            </form>
          </div>
          <div class="modal-footer">
      <button type="button" id="submit-kuisioner1" class="button button3d btn-sky">Simpan</button>
          </div>
        </div>

      </div>
    </div>
  
  <div id="modal_kuisioner2" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Kuisioner Kepuasan Customer</h4>
          </div>
          <div class="modal-body pre-scrollable">
            <form id="form-kuisioner">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
              <input type="hidden" id="exam_id" name="exam_id">
                            <label>Nama Responden</label>
              <input type="text" id="user_name" name="user_name" placeholder="-" class="form-control" value="{{ $currentUser->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Perusahaan</label>
                            <input type="text" id="company_name" name="company_name" placeholder="-" class="form-control" value="{{ $currentUser->company->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>No. Tlp / HP</label>
                            <input type="text" id="company_phone" name="company_phone" placeholder="-" value="{{ $currentUser->company->phone }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Jenis Pengujian</label>
                            <input type="text" id="exam_type" name="exam_type" placeholder="-" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Perangkat</label>
                            <input type="text" id="device_name" placeholder="Smartphone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" id="tanggal" name="tanggal" placeholder="DD/MM/YYYY" class="form-control" value="@php echo $date_STRING;@endphp" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <p>Survey ini terdiri dari dua bagian, yaitu tingkat kepentingan dan tingkat kepuasan Anda. Tingkat kepentingan menunjukan seberapa penting sebuah pernyataan bagi Anda. Sedangkan, tingkat kepuasan menunjukkan seberapa puas pengalaman Anda setelah melakukan pengujian di Infrasutructure Assurance (IAS) Divisi Digital Business (DDB) PT. Telekomuniasi Indonesia, Tbk.
                    </p>
                    <p>Besar pengharapan kami agar pengisian survey ini dapat dikerjakan dengan sebaik-baiknya. Atas kerja samanya, kami ucapkan terimakasih.</p>
                    <p>
                    Skala pemberian nilai adalah 1 - 10 dengan nilai 1 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat memberikan dengan angka bulat.
                    </p>
                </div>
                <div class="row">
					<table id="table_kuisioner" style="width:100%; padding: 2px; border: 1px;">
					  <caption></caption>
                      <tr>
                        <th scope="col">NO</th>
                        <th scope="col">PERTANYAAN</th>
                        <th style="width: 25%;" scope="col">TINGKAT KEPENTINGAN</th>
                        <th style="width: 25%;" scope="col">TINGKAT KEPUASAN</th>
                      </tr>
                      <tbody>
            @php $no = 0; @endphp
            @foreach($data_kuisioner as $item)
              <input type="hidden" name="question_id[]" value="{{ $item->id }}">
              <input type="hidden" name="is_essay[]" value="{{ $item->is_essay }}">
              @php $no++; @endphp
              <tr>
                @if($item->is_essay)
                <td colspan = 2>{{ $item->question }}</td>
                <td colspan = 2>
                  <textarea name="eks{{$no-1}}" class="form-control" placeholder="..."></textarea>
                </td>
                @else
                <td>{{ $no }}</td>
                <td>{{ $item->question }}</td>
                <td>
                	<div class="radio-toolbar">
                		@php
                			for ($i=0; $i<10 ; $i++) { 
                		@endphp
                				<input type="radio" id="eks{{$no.$i}}" name="eks{{$no-1}}" value="{{$i+1}}" @php echo $i == 0 ? "checked" : "";@endphp><label for="eks{{$no.$i}}">{{$i+1}}</label>
                		@php
                			}
                		@endphp
                	</div>
                </td>
                <td>
                	<div class="radio-toolbar">
                		@php
                			for ($i=0; $i<10 ; $i++) { 
                		@endphp
                				<input type="radio" id="pref{{$no.$i}}" name="pref{{$no-1}}" value="{{$i+1}}" @php echo $i == 0 ? "checked" : "";@endphp><label for="pref{{$no.$i}}">{{$i+1}}</label>
                		@php
                			}
                		@endphp
                	</div>
                </td>
                @endif
              </tr>
            @endforeach
                      </tbody>
                    </table>
                </div>
            </form>
          </div>
          <div class="modal-footer">
      <button type="button" id="submit-kuisioner" class="button button3d btn-sky">Simpan</button>
          </div>
        </div>

      </div>
    </div>

    <div id="modal_complain" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Customer Complaint</h4>
          </div>
          <div class="modal-body pre-scrollable">
                <form id="form-complain">
					<table id="table_kuisioner" style="width:100%; padding: 2px; border:1px">
						<caption></caption>
                        <tr>
                            <th colspan="2" scope="colgroup">No</th>
                            <td colspan="2"><input type="text" name="no" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th scope="col">Sheet</th>
                            <td><input type="text" name="no" class="form-control" readonly></td>
                            <th scope="col">of</th>
                            <td><input type="text" name="no" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th colspan="2" scope="colgroup">
                                <label>Customer Name and Address</label>
                                <textarea class="form-control" placeholder="-" readonly>{{ $currentUser->name }} / {{ $currentUser->address }}</textarea>
                            </th>
                            <td colspan="2">
                                <select class="form-control">
                                    <option>Walk In</option>
                                    <option>Call In</option>
                                    <option>Web In</option>        
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" scope="colgroup">
                                <label>Customer Contact</label>
                                <input type="text" name="no" class="form-control" placeholder="-" value="{{ $currentUser->phone }}" readonly>
                            </th>
                            <td colspan="2">
                <input type="hidden" id="my_exam_id" name="my_exam_id">
                <label>Date</label>
                  <input type="text" id="tanggal_complaint" name="tanggal_complaint" placeholder="DD/MM/YYYY" class="form-control" value="@php echo $date_STRING;@endphp" readonly required>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Customer Complaint</label>
                                <textarea name="complaint" class="form-control" placeholder="Your Complaint"></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Name of Recipient</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Corrective Action Taken</label>
                                <textarea class="form-control" placeholder="-" readonly></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" scope="colgroup">
                                <label>Completed Date</label>
                                <input type="text" name="no" class="form-control" placeholder="DD/MM/YYYY" readonly>
                            </th>
                            <td colspan="2">
                                <label>CPAR No</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Name of Actiones</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" scope="colgroup">
                                <label>USer Relation Manager Signature</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                            <td colspan="2">
                                <label>Date</label>
                                <input type="text" name="no" class="form-control" placeholder="DD/MM/YYYY">
                            </td>
                        </tr>
                    </table>
                </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="submit-complain" class="button button3d btn-sky submit-complain">Lewati</button>
            <button type="button" id="submit-complain2" class="button button3d btn-sky submit-complain2">Simpan</button>
          </div>
        </div>

      </div>
    </div>

    <div id="modal_status_barang" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Status Barang</h4>
          </div>
          <div class="modal-body pre-scrollable">
               <div class="row">
                    <h2>Silakan Ambil Barang di Gudang DDB Telkom, Sebelum mengunduh Sertifikat. Terima Kasih</h2>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
          </div>
        </div>

      </div>
    </div> 

	<div id="modal_status_uf" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Status Uji Fungsi</h4>
          </div>
          <div class="modal-body pre-scrollable">
               <div class="row">
                    <h2 id='h2_modal_status_uf'> </h2>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
          </div>
        </div>

      </div>
    </div> 
  
  <div id="modal_status_download" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Status Download</h4>
          </div>
          <div class="modal-body pre-scrollable">
               <div class="row">
                    <h2>Batas Maksimal Unduh Sertifikat adalah 3 kali. Silakan Hubungi Petugas URel untuk Informasi Lebih Lanjut.</h2>
          <div id="historiDownload">
          </div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
          </div>
        </div>

      </div>
    </div>
    
@endsection
 
@section('content_js')
 
<script type="text/javascript" src="{{ asset('template-assets/bootstrap-assets/js/bootstrap.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('template-assets/plugins/owl-carousel/owl.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/js/jquery.easing.min.js')}}"></script> 
<script type="text/javascript" src="{{ asset('template-assets/plugins/countTo/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/plugins/inview/jquery.inview.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/plugins/Lightbox/dist/js/lightbox.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/plugins/WOW/dist/wow.min.js')}}"></script>

  
<script type="text/javascript" src="{{ asset('template-assets/js/jquery.backstretch.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/js/retina-1.1.0.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/js/scripts.js')}}"></script>

<script type="text/javascript" src="{{ asset('template-assets/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/vendor/jquery-smart-wizard/jquery.smartWizard.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/assets/js/form-wizard.js')}}"></script>
<script>
	jQuery(document).ready(function() {
		// Main.init();
		// FormWizard.init();
    doCalendar();
		$(".rad-jns_perusahaan-edit").change(function(){
			var jns_perusahaan = $('input[name="jns_perusahaan"]:checked').val();
			var jns_pengujian = $('.hide_jns_pengujian_edit').val();
			if(jns_pengujian == 1){					
				if(jns_perusahaan == 'Pabrikan'){
					$(".dv-srt-dukungan-prinsipal").hide();
				}
				else{
					$(".dv-srt-dukungan-prinsipal").show();
				}
			}
		});

    // var dateToday = new Date();

    // $('.datepicker').datepicker({
    //     dateFormat: 'yy-mm-dd', 
    //     autoclose: true,
    //     numberOfMonths: 2 ,
    //     showButtonPanel: true,
    //     minDate: dateToday,
    //     beforeShowDay: $.datepicker.noWeekends,

    // });

    function doCalendar(){
      var holidays = [
        "2017-08-17",
        "2017-09-01",
        "2017-09-21"
        ];
        if(holidays != null && holidays.length > 0){
          var dateToday = new Date();

          $('.datepicker').datepicker({
              dateFormat: 'yy-mm-dd', 
              autoclose: true,
              numberOfMonths: 2 ,
              showButtonPanel: true,
              minDate: dateToday,
              beforeShowDay: function(date){
               var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
               var noWeekend = $.datepicker.noWeekends(date);
               var month = date.getMonth()+1; // +1 because JS months start at 0
		           if (noWeekend[0]) {
                    // return  [$.inArray(string, holidays) == -1],[!(month == 12), ""];
                    return  [$.inArray(string, holidays) == -1];
                   }
                   else
                    return noWeekend;
              }
          });
        }else{
          var dateToday = new Date();

          $('.datepicker').datepicker({
              dateFormat: 'yy-mm-dd', 
              autoclose: true,
              numberOfMonths: 2 ,
              showButtonPanel: true,
              minDate: dateToday,
              beforeShowDay: $.datepicker.noWeekends,

          });
        }
    }

	var reason = $('input[name="reason"]:checked').val();
	reason == 0 ? $(".other_reason").show() : $(".other_reason").hide();

		$(".reason").change(function(){
			var reason = $('input[name="reason"]:checked').val();
			reason == 0 ? $(".other_reason").show() : $(".other_reason").hide();
		});
	});
</script>
<script type="text/javascript" >
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$('#cmb-jns-pengujian').val('@php echo $jns; @endphp');
	$('#cmb-jns-status').val('@php echo $status; @endphp');
	
	$('.update-permohonan').click(function(){
		if($('#hide_cekSNjnsPengujian_edit').val() > 0){
			return false;
		}
		var form = $('#form-permohonan-edit')[0]; // You need to use standart javascript object here
		var formData = new FormData(form);
	 
		$.ajax({
			type: "POST",
			url : "updatePermohonan",
			// data: {'_token':"{{ csrf_token() }}", 'nama_pemohon':nama_pemohon, 'nama_pemohons':nama_pemohon},
			// data:new FormData($("#form-permohonan")[0]),
			data:formData,
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";		
			},
			success: function(data){
				// document.getElementById("overlay").style.display="none";
				console.log(data);
				window.open("cetakPermohonan");
			},
			error:function(){
				alert("Gagal mengambil data");
			}
		});
	});
	
	$('.cek-SN-jnsPengujian').click(function(){
		var jnsPelanggan = $('.hide_jns_pengujian_edit').val();
		var true_serialNumber_perangkat = $('#hide_serial_number_edit').val();
		var true_nama_perangkat = $('#hide_name_edit').val();
		var true_model_perangkat = $('#hide_model_edit').val();
		var serialNumber_perangkat = $('#f1-serialNumber-perangkat').val();
		var nama_perangkat = $('#f1-nama-perangkat').val();
		var model_perangkat = $('#f1-model-perangkat').val();
		if((true_nama_perangkat != nama_perangkat) && (true_model_perangkat != model_perangkat)){
		// if(true_serialNumber_perangkat != serialNumber_perangkat){
			//var serialNumber_perangkat = document.getElementsByName("f1-serialNumber-perangkat-edit");
			$.ajax({
				type: "POST",
				url : "cekPermohonan",
				data: {'_token':"{{ csrf_token() }}", 'jnsPelanggan':jnsPelanggan, 'serialNumber_perangkat':serialNumber_perangkat, 'nama_perangkat':nama_perangkat, 'model_perangkat':model_perangkat},
				// dataType:'json',
				type:'post',
				success: function(data){
					console.log(data);
					$('#hide_cekSNjnsPengujian_edit').val(data);
				}
			});
		}else{
			$('#hide_cekSNjnsPengujian_edit').val('0');
		}
	});
	
	
	$("#siupp-file").click(function() {
		var file = $('#hide_siupp_file').val();
		downloadFileCompany(file);
	});
	
	$("#sertifikat-file").click(function() {
		var file = $('#hide_sertifikat_file').val();
		downloadFileCompany(file);
	});
	
	$("#npwp-file").click(function() {
		var file = $('#hide_npwp_file').val();
		downloadFileCompany(file);
	});
	
	$("#ref-uji-file").click(function() {
		var file = $('#hide_ref_uji_file').val();
		downloadFile(file);
	});
	
	$("#prinsipal-file").click(function() {
		var file = $('#hide_prinsipal_file').val();
		downloadFile(file);
	});
	
	$("#sp3-file").click(function() {
		var file = $('#hide_sp3_file').val();
		downloadFile(file);
	});
	
	$("#attachment-file").click(function() {
		var file = $('#hide_attachment_file').val();
		downloadFile(file);
	});
	
	function downloadFile(file){
		var path = "{{ URL::asset('media/examination') }}";
		var id_exam = $('#hide_exam_id_edit').val();
		//Get file name from url.
		var url = path+'/'+id_exam+'/'+file;
		var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
		var xhr = new XMLHttpRequest();
		xhr.responseType = 'blob';
		xhr.onload = function() {
			if (this.status === 404) {
			   // not found, add some error handling
			   alert("File Tidak Ada!");
			   return false;
			}
			var a = document.createElement('a');
			a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
			a.download = filename; // Set the file name.
			a.style.display = 'none';
			document.body.appendChild(a);
			a.click();
			delete a;
		};
		xhr.open('GET', url);
		xhr.send();
	}
	
	function downloadFileCompany(file){
		var path = "{{ URL::asset('media/company') }}";
		var company_id = $('#hide_company_id').val();
		//Get file name from url.
		var url = path+'/'+company_id+'/'+file;
		var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
		var xhr = new XMLHttpRequest();
		xhr.responseType = 'blob';
		xhr.onload = function() {
			if (this.status === 404) {
			   // not found, add some error handling
			   alert("File Tidak Ada!");
			   return false;
			}
			var a = document.createElement('a');
			a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
			a.download = filename; // Set the file name.
			a.style.display = 'none';
			document.body.appendChild(a);
			a.click();
			delete a;
		};
		xhr.open('GET', url);
		xhr.send();
	}
	
	function reSchedule(a,b,c,d,e,f,g){
		// jika function_test_TE_temp == 1, tidak bisa memilih tanggal dan munculkan alert
		if(f==1){
			document.getElementById("h2_modal_status_uf").innerHTML = "{{ trans('translate.uf_warning') }} "+g;
			$('#modal_status_uf').modal('show');
			return false;
		}
		if(c==1){
			$('#reschedule-modal-content').modal('show');
			$('#hide_id_exam').val(a);
			$('#hide_date_type').val(c);
			$('#reschedule-modal-content').on('shown.bs.modal', function() {
				$('#cust_test_date').val(b);
				$("#cust_test_date").focus();
			});
		}else if(c==2){
			$('#reschedule-modal-content2').modal('show');
			$('#hide_id_exam2').val(a);
			$('#hide_date_type2').val(c);
			$('#reschedule-modal-content2').on('shown.bs.modal', function() {
				$('#cust_test_date2').val(b);
				$('#deal_test_date2').val(d);
				$('#urel_test_date2').val(e);
				$("#urel_test_date2").focus();
			});
		}else if(c==3){
			$('#reschedule-modal-content3').modal('show');
			$('#hide_id_exam3').val(a);
			$('#hide_date_type3').val(c);
			$('#reschedule-modal-content3').on('shown.bs.modal', function() {
				$('#cust_test_date3').val(b);
				$('#deal_test_date3').val(d);
			});
		}
	}
	$('.btn-submit').click(function(){
		var APP_URL = {!! json_encode(url('/pengujian')) !!};
		location.reload(APP_URL);
		// $('#myModal').modal('hide');
		// document.getElementById("form-permohonan").reset();
	});
</script>
<script type="text/javascript" src="{{ asset('assets/js/search/pengujian.js')}}"></script>
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script type="text/javascript">
	function isTestimonial(a,b,c,d,e){
		var link = document.getElementById('link');
			// link.value = '/pengujian/download/'+a+'/'+b+'/'+c;
			link.value = b;
		
		$.ajax({
			type: "POST",
			url : "checkKuisioner",
			data: {'_token':"{{ csrf_token() }}", 'id':e},
			type:'post',
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				if(response=='0'){
					$('#modal_kuisioner2').modal('show');
					$('#modal_kuisioner2').on('shown.bs.modal', function() {
						$("#device_name").val(a);
						$("#exam_type").val(d);
						$("#exam_id").val(e);
						$("#tanggal").focus();
					});
				}else if(response=='' || response==undefined || response==undefined){
					$("#my_exam_id").val(e);
					$('#modal_kuisioner2').modal('hide');
					$('#modal_complain').modal('show');	
					$('#modal_complain').on('shown.bs.modal', function() {
						$("#tanggal_complaint").focus();
					});	
				}else{
					checkAmbilBarang(e);
				}
			}
		});
	}
	
	$('#submit-kuisioner').click(function () {
		$("#my_exam_id").val(document.getElementById('exam_id').value);
		$.ajax({
			url : "insertKuisioner",
			data:new FormData($("#form-kuisioner")[0]),
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				// if(response==1){
					$('#modal_kuisioner2').modal('hide');
					$('#modal_complain').modal('show');	
					$('#modal_complain').on('shown.bs.modal', function() {
						$("#tanggal_complaint").focus();
					});					
				// }
			}
		});
	});

	$('.submit-complain').click(function () {
		checkAmbilBarang(document.getElementById('my_exam_id').value);
	});
	
	$('.submit-complain2').click(function () {
		$.ajax({
			url : "insertComplaint",
			data:new FormData($("#form-complain")[0]),
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				checkAmbilBarang(document.getElementById('my_exam_id').value);
			}
		});
	});
	
	function checkAmbilBarang(a){
		var link = document.getElementById('link').value;
		$.ajax({
			type: "POST",
			url : "cekAmbilBarang",
			data: {'_token':"{{ csrf_token() }}", 'my_exam_id':a},
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				$('#modal_complain').modal('hide');
				if(response==1){
					// window.location.href = '/telkomdds/public'+link;
					window.open(link);
				}else{
					$('#modal_status_barang').modal('show');
				}
			}
		});
	}

	function reqCancel(a){
		$("#my_exam_id").val(a);
		$('#modal_request_cancel').modal('show');
	}

	$('#btn-cancel').click(function () {
		rad_validation = ($('input[name=reason]:checked'));
		if(rad_validation.length == 0 || (rad_validation[0].value == 0 && $("#other_reason").val() == '')){
			alert('Reason is Required');
			return false;
		}
		var baseUrl = "{{URL::to('/')}}";
		if (confirm("{{ trans('translate.message_request_cancelation') }}")) {
			var a = document.getElementById('my_exam_id').value;
			var b = rad_validation[0].value;
			var c = document.getElementById('other_reason').value;
			$.ajax({
				type: "POST",
				url : "reqCancel",
				data: {'_token':"{{ csrf_token() }}", 'my_exam_id':a, 'reason':b, 'other_reason':c},
				beforeSend: function(){
					// document.getElementById("overlay").style.display="inherit";
				},
				success:function(response){
					// document.getElementById("overlay").style.display="none";
					console.log(response);
					location.reload();
				}
			});
		}
	});
</script>

@endsection