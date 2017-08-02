@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.home') }} - Telkom DDS</title>
@section('content')
	<link rel="stylesheet" href="{{url('vendor/chosen/chosen.css')}}">
 	<section id="slider" class="slider-parallax swiper_wrapper clearfix" style="height: 600px;">

		<div class="swiper-container swiper-parent">
			<div class="swiper-wrapper">
			
				@foreach($data_slideshow as $item)
				<div class="swiper-slide dark" style="background-image: url('media/slideshow/<?php echo $item->image?>');">
					<div class="container clearfix"> 
					</div>
				</div>
				@endforeach
			
			</div>
			<div id="slider-arrow-left"><i class="icon-angle-left"></i></div>
			<div id="slider-arrow-right"><i class="icon-angle-right"></i></div>
			<div id="slide-number"><div id="slide-number-current"></div><span>/</span><div id="slide-number-total"></div></div>
		</div>

	</section>

	<!-- Content
	============================================= -->
	<section id="content">

		<div class="content-wrap">


			<div class="container clearfix">

				<div id="section-contact" class="heading-block title-center page-section">
					<h3>{{ trans('translate.header_services') }}</h3>
				</div>

				<div class="col_half nobottommargin">
					<div class="feature-box media-box">
						<div class="fbox-media">
							<img src="images/services/1.jpg" alt="Why choose Us?">
						</div>
						<div class="fbox-desc divcenter">
							<h3>{{ trans('translate.service') }}<span class="subtitle">{{ trans('translate.subtitle_service') }}.</span></h3><br>
							<a href="{{url('/process')}}" class="button button-3d nomargin full btn-sky">{{ trans('translate.see_service') }}</a>
						</div>
					</div>
				</div>

				<div class="col_half nobottommargin col_last">
					<div class="feature-box media-box">
						<div class="fbox-media">
							<img src="images/services/2.jpg" alt="Why choose Us?">
						</div>
						<div class="fbox-desc divcenter">
							<h3>{{ trans('translate.buy_stel') }}<span class="subtitle">{{ trans('translate.subtitle_buy_stel') }}.</span></h3><br>
							<a href="{{url('/products')}}" class="button button-3d nomargin full btn-sky">{{ trans('translate.see_service') }}</a>
						</div>
					</div>
				</div>



				<div class="clear"></div>

				<div class="divider"><i class="icon-circle"></i></div>

				<div id="section-partner" class="heading-block title-center page-section">
					<h2>{{ trans('translate.our_partner') }}</h2>
				</div>

				<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="20" data-nav="false" data-pagi="true" data-items-xxs="2" data-items-xs="3" data-items-sm="4" data-items-md="5" data-items-lg="6">
				
				</div>

				<div class="clear"></div>

				<div class="divider divider-short divider-center"><i class="icon-circle"></i></div>

				<div id="section-contact" class="heading-block title-center page-section">
					<h2>{{ trans('translate.contact') }}</h2>
					<span>{{ trans('translate.contact_description') }}</span>
				</div>

				<!-- Contact Form
				============================================= -->
				<div class="col_full">

					<div class="fancy-title">
						<h3>{{ trans('translate.contact_us') }}</h3>
					</div>

					<div class="contact-widget">

						<div class="contact-form-result">
							@if (Session::get('error_feedback'))
								<div class="alert alert-error alert-danger">
									{{ Session::get('error_feedback') }}
								</div>
							@endif
							
							@if (Session::get('message_feedback'))
								<div class="alert alert-info">
									{{ Session::get('message_feedback') }}
								</div>
							@endif 
						</div>

						 
						 <form id="form-send-feedback" class="nobottommargin"  role="form" method="POST" action="{{ url('/client/feedback') }}">
						 	{{ csrf_field() }}
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-process"></div>

							<div class="col-md-8 col-md-offset-2">
								<label for="template-contactform-name">{{ trans('translate.contact_question') }} <small>*</small></label>
								<select class="required chosen-select" id="question" name="question"> 
										<option value="0">-</option>
									@foreach($data_question as $item)
										<option value="{{ $item->id }}">{{ $item->name }}</option>
									@endforeach
								</select>
							</div>  

							<div class="clear"></div>

							<div class="col-md-8 col-md-offset-2">
								<label for="template-contactform-name">{{ trans('translate.contact_email') }} <small>*</small></label>
								<input type="text" name="email" id="Email" placeholder="john@mail.com" class="sm-form-control required" />
							</div>  

							<div class="clear"></div>

							<div class="col-md-8 col-md-offset-2">
								<label for="template-contactform-subject">{{ trans('translate.contact_subject') }} <small>*</small></label>
								<input type="text" name="subject" id="Subject" placeholder="{{ trans('translate.contact_subject_example') }}" class="required sm-form-control" />
							</div> 

							<div class="clear"></div>

							<div class="col-md-8 col-md-offset-2">
								<label for="template-contactform-message">{{ trans('translate.contact_message') }} <small>*</small></label>
								<textarea class="required sm-form-control" name="message" rows="3" placeholder="{{ trans('translate.contact_message') }}" rows="6" cols="30"></textarea>
							</div>

							<div class="clear"></div>

							<div class="col-md-8 col-md-offset-2" style="margin-top:15px">
								<button type="submit" class="button button-3d nomargin full btn-sky" type="submit" name="template-contactform-submit" value="submit">{{ trans('translate.contact_send') }}</button>
							</div>

						</form>

					</div>


				</div><!-- Contact Form End -->

				</div>



			</div>

		</div>

	</section><!-- #content end -->
@endsection

@section('content_js')

<script>
	$(".chosen-select").chosen(); 
</script>
<script src="{{url('vendor/chosen/chosen.jquery.js')}}" type="text/javascript"></script> 
@endsection