@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.examination_detail') }} - Telkom DDS</title>


@section('content')
 	<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>Detail {{ trans('translate.examination') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="{{ url('/') }}">{{ trans('translate.home') }}</a></li>
					<li>{{ trans('translate.menu_testing') }}</li>
					<li><a href="{{ url('/pengujian') }}"></a>{{ trans('translate.examination') }}</li>
					<li class="active">Detail</li>
				</ol>
			</div>

		</section><!-- #page-title end -->



	<input type="hidden" name="exam_id" id="exam_id" value="<?php echo $data[0]->id ?>">
	<input type="hidden" name="link" id="link">
	<div class="modal fade" id="myModal_testimonial" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="fa fa-eyes-open"></i> {{ trans('translate.download_certificate_message') }} </h4>
				</div>
				
				<div class="modal-body">
					<table width=100%>
						<tr>
							<td>
								<div class="form-group">
									<label for="message">{{ trans('translate.contact_message') }}:</label>
									<textarea class="form-control" rows="5" name="message" id="message"></textarea>
								</div>
							</td>
						</tr>
					</table>
				</div><!-- /.modal-content -->
				<div class="modal-footer">
					<table width=100%>
						<tr>
							<td>
								<button type="button" id="submit-testimonial" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
							</td>
						</tr>
					</table>
				</div>
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
		
		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap"> 
				<div class="container clearfix">
						<div class="col-md-12">
				    		<a class="button button-3d btn-sky nomargin" href="{{url('/pengujian')}}">Back</a>
				    	  </div>
					<div class="container-fluid container-fullw bg-white">
						@if(count($exam_schedule->data)>0)
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-white" id="panel1">
									<div class="panel-body">
										<div class="col-md-12">
										<!-- start: WIZARD FORM -->
											<div id="wizard" class="swMain">
												<div class="form-group">
													<table class="table table-condensed">
														<thead>
															<tr>
																<th colspan="2">{{ trans('translate.examination_date') }}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="center">{{ trans('translate.examination_date_begin') }} : {{ $exam_schedule->data[0]->testing_type }}</td>
																<td class="center">{{ trans('translate.examination_date_end') }} : {{ $exam_schedule->data[0]->testing_type }}</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										<!-- end: WIZARD FORM -->
										</div>
									</div>
								</div>
							</div>
						</div>
						@endif
						 <!-- start: WIZARD FORM -->
						<?php $no=1; //print_r($data);exit;
						//foreach($data as $item){
						?>
						<form action="#" role="form" class="smart-wizard" id="form">
							{!! csrf_field() !!}
							<div id="wizard" class="swMain">
							@foreach($data as $item)
								<div class="form-group">
									<table class="table table-condensed">
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_application') }}</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{ trans('translate.service_application_name') }}</td>
												<td> : {{ $item->namaPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_address') }}</td>
												<td> : {{ $item->alamatPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_phone') }}</td>
												<td> : {{ $item->telpPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_fax') }}</td>
												<td> : {{ $item->faxPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_email') }}</td>
												<td> : {{ $item->emailPemohon }}</td>
											</tr>
										</tbody>
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_company') }} [{{ $item->jnsPerusahaan }}]</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{ trans('translate.service_company_name') }}</td>
												<td> : {{ $item->namaPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_address') }}</td>
												<td> : {{ $item->alamatPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_phone') }}</td>
												<td> : {{ $item->telpPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_fax') }}</td>
												<td> : {{ $item->faxPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_email') }}</td>
												<td> : {{ $item->emailPerusahaan }}</td>
											</tr>
										</tbody>
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_device') }}</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{ trans('translate.service_device_serial_number') }}</td>
												<td> : {{ $item->serialNumber }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_equipment') }}</td>
												<td> : {{ $item->nama_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_mark') }}</td>
												<td> : {{ $item->merk_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_capacity') }}</td>
												<td> : {{ $item->kapasitas_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_manufactured_by') }}</td>
												<td> : {{ $item->pembuat_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_model') }}</td>
												<td> : {{ $item->model_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_test_reference') }}</td>
												<td> : {{ $item->referensi_perangkat }}</td>
											</tr>
										</tbody>
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_device_document') }}</th>
											</tr>
										</thead>
										<tbody>
											@foreach($data_attach as $item_attach)
											<tr>
												<td> 
													@if($item_attach->name == 'Sertifikat')
														<a class="btn btn-link" href="javascript:void(0)" style="color:black !important;" onclick="return isTestimonial('{{ $item_attach->id_attach }}','{{ $item_attach->attachment }}','{{ $item_attach->jns }}');">{{ $item_attach->name }} </a>
													@elseif($item_attach->name == 'Laporan Uji' && $item_attach->attachment != '')
														<a class="btn btn-link" href="#" style="color:black !important;">{{ $item_attach->name }} </a>
													@else	
														<a class="btn btn-link" href="{{URL::to('/pengujian/download/'.$item_attach->id_attach.'/'.$item_attach->attachment.'/'.$item_attach->jns)}}" style="color:black !important;">{{ $item_attach->name }} </a>
													@endif
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="row">
									<div class=" pull-right col-xs-12">
										<a class="btn btn-wide btn-danger pull-right col-xs-12 col-lg-1" style="margin-bottom:10px;" 
											href="{{URL::to('cetakPengujian/'.$item->id.'')}}" target="_blank">
											{{ trans('translate.examination_print') }}
										</a>
									</div>
								</div>										
							</div>
							<?php 
							$no++;
							//}
							?>
							@endforeach
						</form>
						<!-- end: WIZARD FORM -->
						<div id="wizard" class="swMain">
							<div class="form-group">
								<table class="table table-condensed">
									<thead>
										<tr>
											<th colspan="4">{{ trans('translate.examination_history') }}</th>
										</tr>
										<tr>
											<td class="center">{{ trans('translate.examination_history_step') }}</td>
											<td class="center">{{ trans('translate.examination_history_status') }}</td>
											<td class="center">{{ trans('translate.examination_history_notes') }}</td>
											<td class="center">{{ trans('translate.examination_history_date') }}</td>
										</tr>
									</thead>
									<tbody>
									@foreach($exam_history as $item)
										<tr>
											<td class="center">{{ $item->tahap }}</td>
											@if($item->status == 1)
												<td class="center">Completed</td>
											@else
												<td class="center">Not Completed</td>
											@endif
											<td>{{ $item->keterangan }}</td>
											<td class="center">{{ $item->date_action }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- #content end -->
@endsection

@section('content_js')
<script type="text/javascript">
	// jQuery(document).ready(function() {
		// Main.init();
		// FormWizard.init();
	// });
	
	// $.ajaxSetup({
	// 	headers: {
	// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 	}
	// });
	
	function isTestimonial(a,b,c){
		var link = document.getElementById('link');
			link.value = '/pengujian/download/'+a+'/'+b+'/'+c;
		var message = document.getElementById('message');
		$('#myModal_testimonial').modal('show');
		message.focus();
	}
	
	$('#submit-testimonial').click(function () {
		var link = document.getElementById('link').value;
		var message = document.getElementById('message').value;
		var exam_id = document.getElementById('exam_id').value;
		if(message == ''){
			$('#myModal_testimonial').modal('show');
			return false;
		}else{
			$.ajax({
				type: "POST",
				url : "{{URL::to('/testimonial')}}",
				data: {'_token':"{{ csrf_token() }}", 'message':message, 'exam_id':exam_id},
				// data:new FormData($("#form-permohonan")[0]),
				// data:formData,
				dataType:'json',
				// async:false,
				// type:'post',
				// processData: false,
				// contentType: false,
				beforeSend: function(){
					// document.getElementById("overlay").style.display="inherit";		
				},
				success: function(response){
					// return false;
					// document.getElementById("overlay").style.display="none";
					$('#myModal_testimonial').modal('hide');
					window.location.href = '/telkomtesthouse/public'+link;
				},
				error:function(){
					alert("Gagal mengambil data");
				}
			});
		}
	});
</script>
@endsection