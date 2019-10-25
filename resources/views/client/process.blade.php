@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.process') }} - Telkom DDS</title>
@section('content')
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
						<a href="{{url('detailprocess/qa')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
					<div id="videoStory1" class="mfp-hide" style="max-width: 75%; margin: 0 auto;">
						<iframe width="853" height="480" src="https://www.youtube.com/embed/4sL5-d9yxl8" frameborder="0" allowfullscreen></iframe>
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
						<a href="{{url('detailprocess/ta')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
					<div id="videoStory2" class="mfp-hide" style="max-width: 75%; margin: 0 auto;">
						<iframe width="853" height="480" src="https://www.youtube.com/embed/Ju-uU2kJ3m8" frameborder="0" allowfullscreen></iframe>
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
						<a href="{{url('detailprocess/vt')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
					<div id="videoStory3" class="mfp-hide" style="max-width: 75%; margin: 0 auto;">
						<iframe width="853" height="480" src="https://www.youtube.com/embed/uGxUzfekYIE" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>

				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabTransmisi.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Calibration (CAL)</h4>
						<p>{{ trans('translate.subtitle_cal_process') }}</p>
						<a href="{{url('detailprocess/cal')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
				</div>


			</div>
			

		</div>



	</div>


</section><!-- #content end -->

@endsection

@section('content_js')
<script type="text/javascript">
	$('.videoLink').magnificPopup({
		type:'inline',
		midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  	});
</script>
@endsection