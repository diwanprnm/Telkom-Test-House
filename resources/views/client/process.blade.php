@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.process') }} - Telkom DDS</title>
@section('content')
<link rel="stylesheet" href="{{url('vendor/chosen/chosen.css')}}">
<style type="text/css">
	.fluid-width-video-wrapper iframe {
	    width: 100%;
	    height: 100%;
	}
</style>
	<!-- Page Title
============================================= -->
<section id="page-title">

	<div class="container clearfix">
		<h1>{{ trans('translate.process') }}</h1>			
			<ol class="breadcrumb">
				<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
				<li class="active">{{ trans('translate.process') }}</li>
			</ol>
	</div>

</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">

	<div class="content-wrap">


		<div class="container clearfix box">

			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 left-content"></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 right-content"></div>
			</div>

		</div>


		<div class="container">

			
			<div class="row">
				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabCPE.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Quality Assurance (QA)</h4>
						<p>{{ trans('translate.subtitle_qa_process') }}</p>
							<a href="#videoStory1" class="btn btn-default btn-sm videoLink">{{ trans('translate.video_guide') }} <i class="fa fa-play-circle" aria-hidden="true">&nbsp;</i></a>
						@if($qs_certificate_date == 1)
							<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_qs_certificate_date">{{ trans('translate.process') }}</a>
						@else
							@if(count($data_layanan))
								<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_status_layanan_qa">{{ trans('translate.process') }}</a>
							@else
								<a href="{{url('detailprocess/qa')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
							@endif
						@endif
					</div>
					<div id="videoStory1" class="mfp-hide" style="max-width: 75%; margin: 0 auto;">
						<iframe width="853" height="480" src="{{ $qa_video_url }}" frameborder="0" allowfullscreen></iframe>
					</div> 
					<div id="modal_status_layanan_qa" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
				      <div class="modal-dialog modal-lg">

				        <!-- Modal content-->
				        <div class="modal-content">
				          <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal">&times;</button>
				            <h2 class="modal-title">{{ trans('translate.attention') }}</h2>
				          </div>
				          <div class="modal-body pre-scrollable" style="padding-bottom: 20%;">
				               <div class="row">
				               		@if(count($data_layanan_active) == 0)
				                    	<h2>{{ trans('translate.message_close_lab') }}</h2>
				                    @else
				                    	<p>
				                    		<b style="font-size: 130%;">{{ trans('translate.message_close_lab_separate') }}</b><br>
				                    		@foreach($data_layanan as $data)
				                    			<i class="fa fa-angle-right"></i> {{ $data->name }} {{ trans('translate.close_until') }} {{ date('d M Y', strtotime($data->close_until)) }}.<br>
				                    		@endforeach
				                    	</p>
				                    	<h4>{{ trans('translate.available_stel') }}</h4>
					                    	<select id="chosen-qa" class="chosen-select" placeholder="{{ trans('translate.service_device_test_reference') }}" class="required"> 
												<option value="" selected>{{ trans('translate.examination_choose_stel') }}</option>
											@foreach($data_stels_qa as $item)
												@if(in_array($item->lab,$data_layanan_not_active))
													<option value="{{ $item->stel }}" disabled>{{ $item->stel }} || {{ $item->device_name }}</option>
												@else
													<option value="{{ $item->stel }}">{{ $item->stel }} || {{ $item->device_name }}</option>
												@endif
											@endforeach
										</select>
				                    @endif
				                </div>
				          </div>
				          <div class="modal-footer">
				          	@if(count($data_layanan_active) == 0)
				          		<button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          	@else
				          		<button id="ok_qa" type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          		<a id="next_qa" href="{{url('detailprocess/qa')}}" class="button button3d btn-sky">{{ trans('translate.process') }}</a>
				          	@endif
				          </div>
				        </div>

				      </div>
				    </div>
				</div>

				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabEnergi.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Type Approval (TA)</h4>
						<p>{{ trans('translate.subtitle_ta_process') }}</p>
						<a href="#videoStory2" class="btn btn-default btn-sm videoLink">{{ trans('translate.video_guide') }} <i class="fa fa-play-circle" aria-hidden="true">&nbsp;</i></a>
						@if($qs_certificate_date == 1)
							<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_qs_certificate_date">{{ trans('translate.process') }}</a>
						@else
							@if(count($data_layanan))
								<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_status_layanan_ta">{{ trans('translate.process') }}</a>
							@else
								<a href="{{url('detailprocess/ta')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
							@endif
						@endif
					</div>
					<div id="videoStory2" class="mfp-hide" style="max-width: 75%; margin: 0 auto;">
						<iframe width="853" height="480" src="{{ $ta_video_url }}" frameborder="0" allowfullscreen></iframe>
					</div>
					<div id="modal_status_layanan_ta" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
				      <div class="modal-dialog modal-lg">

				        <!-- Modal content-->
				        <div class="modal-content">
				          <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal">&times;</button>
				            <h2 class="modal-title">{{ trans('translate.attention') }}</h2>
				          </div>
				          <div class="modal-body pre-scrollable" style="padding-bottom: 20%;">
				               <div class="row">
				               		@if(count($data_layanan_active) == 0)
				                    	<h2>{{ trans('translate.message_close_lab') }}</h2>
				                    @else
				                    	<p>
				                    		<b style="font-size: 130%;">{{ trans('translate.message_close_lab_separate') }}</b><br>
				                    		@foreach($data_layanan as $data)
				                    			<i class="fa fa-angle-right"></i> {{ $data->name }} {{ trans('translate.close_until') }} {{ date('d M Y', strtotime($data->close_until)) }}.<br>
				                    		@endforeach
				                    	</p>
				                    	<h4>{{ trans('translate.available_stel') }}</h4>
					                    	<select id="chosen-ta" class="chosen-select" placeholder="{{ trans('translate.service_device_test_reference') }}" class="required"> 
												<option value="" selected>{{ trans('translate.examination_choose_stel') }}</option>
											@foreach($data_stels as $item)
												@if(in_array($item->lab,$data_layanan_not_active))
													<option value="{{ $item->stel }}" disabled>{{ $item->stel }} || {{ $item->device_name }}</option>
												@else
													<option value="{{ $item->stel }}">{{ $item->stel }} || {{ $item->device_name }}</option>
												@endif
											@endforeach
										</select>
				                    @endif
				                </div>
				          </div>
				          <div class="modal-footer">
				          	@if(count($data_layanan_active) == 0)
				          		<button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          	@else
				          		<button id="ok_ta" type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          		<a id="next_ta" href="{{url('detailprocess/ta')}}" class="button button3d btn-sky">{{ trans('translate.process') }}</a>
				          	@endif
				          </div>
				        </div>

				      </div>
				    </div>
				</div>

				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabKabel.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Voluntary Test (VT)</h4>
						<p>{{ trans('translate.subtitle_vt_process') }}</p>
						<a href="#videoStory3" class="btn btn-default btn-sm videoLink">{{ trans('translate.video_guide') }} <i class="fa fa-play-circle" aria-hidden="true">&nbsp;</i></a>
						@if($qs_certificate_date == 1)
							<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_qs_certificate_date">{{ trans('translate.process') }}</a>
						@else
							@if(count($data_layanan))
								<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_status_layanan_vt">{{ trans('translate.process') }}</a>
							@else
								<a href="{{url('detailprocess/vt')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
							@endif
						@endif
					</div>
					<div id="videoStory3" class="mfp-hide" style="max-width: 75%; margin: 0 auto;">
						<iframe width="853" height="480" src="{{ $vt_video_url }}" frameborder="0" allowfullscreen></iframe>
					</div>
					<div id="modal_status_layanan_vt" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
				      <div class="modal-dialog modal-lg">

				        <!-- Modal content-->
				        <div class="modal-content">
				          <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal">&times;</button>
				            <h2 class="modal-title">{{ trans('translate.attention') }}</h2>
				          </div>
				          <div class="modal-body pre-scrollable" style="padding-bottom: 20%;">
				               <div class="row">
				               		@if(count($data_layanan_active) == 0)
				                    	<h2>{{ trans('translate.message_close_lab') }}</h2>
				                    @else
				                    	<p>
				                    		<b style="font-size: 130%;">{{ trans('translate.message_close_lab_separate') }}</b><br>
				                    		@foreach($data_layanan as $data)
				                    			<i class="fa fa-angle-right"></i> {{ $data->name }} {{ trans('translate.close_until') }} {{ date('d M Y', strtotime($data->close_until)) }}.<br>
				                    		@endforeach
				                    	</p>
				                    	<h4>{{ trans('translate.available_stel') }}</h4>
					                    	<select id="chosen-vt" class="chosen-select" placeholder="{{ trans('translate.service_device_test_reference') }}" class="required"> 
												<option value="" selected>{{ trans('translate.examination_choose_stel') }}</option>
											@foreach($data_stels as $item)
												@if(in_array($item->lab,$data_layanan_not_active))
													<option value="{{ $item->stel }}" disabled>{{ $item->stel }} || {{ $item->device_name }}</option>
												@else
													<option value="{{ $item->stel }}">{{ $item->stel }} || {{ $item->device_name }}</option>
												@endif
											@endforeach
										</select>
				                    @endif
				                </div>
				          </div>
				          <div class="modal-footer">
				          	@if(count($data_layanan_active) == 0)
				          		<button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          	@else
				          		<button id="ok_vt" type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          		<a id="next_vt" href="{{url('detailprocess/vt')}}" class="button button3d btn-sky">{{ trans('translate.process') }}</a>
				          	@endif
				          </div>
				        </div>

				      </div>
				    </div>
				</div>

				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabTransmisi.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Calibration (CAL)</h4>
						<p>{{ trans('translate.subtitle_cal_process') }}</p>
						@if($qs_certificate_date == 1)
							<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_qs_certificate_date">{{ trans('translate.process') }}</a>
						@else
							@if(count($data_layanan))
								<a class="button button-3d nomargin btn-sky" data-toggle="modal" data-target="#modal_status_layanan_cal">{{ trans('translate.process') }}</a>
							@else
								<a href="{{url('detailprocess/cal')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
							@endif
						@endif
					</div>
					<div id="modal_status_layanan_cal" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
				      <div class="modal-dialog modal-lg">

				        <!-- Modal content-->
				        <div class="modal-content">
				          <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal">&times;</button>
				            <h2 class="modal-title">{{ trans('translate.attention') }}</h2>
				          </div>
				          <div class="modal-body pre-scrollable" style="padding-bottom: 20%;">
				               <div class="row">
				               		@if(count($data_layanan_active) == 0)
				                    	<h2 align="justify">{{ trans('translate.message_close_lab') }}</h2>
				                    @else
				                    	<p>
				                    		<b style="font-size: 130%;">{{ trans('translate.message_close_lab_separate') }}</b><br>
				                    		@foreach($data_layanan as $data)
				                    			<i class="fa fa-angle-right"></i> {{ $data->name }} {{ trans('translate.close_until') }} {{ date('d M Y', strtotime($data->close_until)) }}.<br>
				                    		@endforeach
				                    	</p>
				                    	<h4>{{ trans('translate.available_stel') }}</h4>
					                    	<select id="chosen-cal" class="chosen-select" placeholder="{{ trans('translate.service_device_test_reference') }}" class="required"> 
												<option value="" selected>{{ trans('translate.examination_choose_stel') }}</option>
											@foreach($data_stels as $item)
												@if(in_array($item->lab,$data_layanan_not_active))
													<option value="{{ $item->stel }}" disabled>{{ $item->stel }} || {{ $item->device_name }}</option>
												@else
													<option value="{{ $item->stel }}">{{ $item->stel }} || {{ $item->device_name }}</option>
												@endif
											@endforeach
										</select>
				                    @endif
				                </div>
				          </div>
				          <div class="modal-footer">
				          	@if(count($data_layanan_active) == 0)
				          		<button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          	@else
				          		<button id="ok_cal" type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
				          		<a id="next_cal" href="{{url('detailprocess/cal')}}" class="button button3d btn-sky">{{ trans('translate.process') }}</a>
				          	@endif
				          </div>
				        </div>

				      </div>
				    </div>
				</div>

				<div id="modal_qs_certificate_date" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
			      <div class="modal-dialog modal-lg">

			        <!-- Modal content-->
			        <div class="modal-content">
			          <div class="modal-header">
			            <button type="button" class="close" data-dismiss="modal">&times;</button>
			            <h2 class="modal-title">{{ trans('translate.attention') }}</h2>
			          </div>
			          <div class="modal-body pre-scrollable" style="padding-bottom: 20%;">
			               <div class="row">
			               		<h2>{{ trans('translate.qs_certifcate_date_expired_message1') }}
			               		{{ trans('translate.qs_certifcate_date_expired_message2') }}</h2>
			                </div>
			          </div>
			          <div class="modal-footer">
			          	<button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
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
<script src="{{url('vendor/chosen/chosen.jquery.js')}}" type="text/javascript"></script> 
<script type="text/javascript">
	$(".chosen-select").chosen({width: "95%"}); 
	$('.videoLink').magnificPopup({
		type:'inline',
		midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  	});

	$("#next_qa").hide();
	$("#next_ta").hide();
	$("#next_vt").hide();
	$("#next_cal").hide();

  	$("#chosen-qa").change(function(){
		if(this.value != ""){
			$("#ok_qa").hide();			
			$("#next_qa").show();			
		}else{
			$("#next_qa").hide();	
			$("#ok_qa").show();
		}
	});
  	$("#chosen-ta").change(function(){
		if(this.value != ""){
			$("#ok_ta").hide();			
			$("#next_ta").show();			
		}else{
			$("#next_ta").hide();	
			$("#ok_ta").show();
		}
	});
  	$("#chosen-vt").change(function(){
		if(this.value != ""){
			$("#ok_vt").hide();			
			$("#next_vt").show();			
		}else{
			$("#next_vt").hide();	
			$("#ok_vt").show();
		}
	});
  	$("#chosen-cal").change(function(){
		if(this.value != ""){
			$("#ok_cal").hide();			
			$("#next_cal").show();			
		}else{
			$("#next_cal").hide();	
			$("#ok_cal").show();
		}
	});
</script>
@endsection