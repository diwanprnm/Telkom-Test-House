@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.home') }} - Telkom DDB</title>
@section('content')
<style type="text/css">
	#root {
	  display: flex;
	}

	.playlist {
	  display: flex;
	  /*box-sizing: border-box;*/
	  /*flex-direction: column;*/
	  /*margin: 0 20px;*/
	  /*height: 270px;*/
	  width: 20%;
	  /*overflow-y: hidden;*/
	}

	.playlist-item {
	  display: flex;
	  box-sizing: border-box;
	  width: 100%;
	  height: 90px;
	  flex-shrink: 0;
	  transition: all 0.3s;
	}
	.center_arrow{
	  width: 190px;
	  text-align: center;
	}
	#iframe_yt{
	  margin-top: 29px;
	}


	/* Modal Content (Image) */
	.modal-content {
	  margin: auto;
	  display: block;
	  width: 80%;
	  max-width: 700px;
	  margin-top: 10%;
	}

	/* Caption of Modal Image (Image Text) - Same Width as the Image */
	#caption {
	  margin: auto;
	  display: block;
	  width: 80%;
	  max-width: 700px;
	  text-align: center;
	  color: #ccc;
	  padding: 10px 0;
	  height: 150px;
	}


	/* The Close Button */

	.close {
	  /*position: absolute;*/
	  color: red;
	  font-size: 20px;
	  opacity: 0.6;
	}

	.close:hover,
	.close:focus {
	  color: #bbb;
	  text-decoration: none;
	  cursor: pointer;
	}

	/* 100% Image Width on Smaller Screens */
	@media only screen and (max-width: 700px){
		.modal-content {
			width: 100%;
		}
	}

	/* padding-bottom and top for image */
.mfp-no-margins img.mfp-img {
	padding: 0;
}
/* position of shadow behind the image */
.mfp-no-margins .mfp-figure:after {
	top: 0;
	bottom: 0;
}
/* padding for main container */
.mfp-no-margins .mfp-container {
	padding: 0;
}


/* 

for zoom animation 
uncomment this part if you haven't added this code anywhere else

*/
/*

.mfp-with-zoom .mfp-container,
.mfp-with-zoom.mfp-bg {
	opacity: 0;
	-webkit-backface-visibility: hidden;
	-webkit-transition: all 0.3s ease-out; 
	-moz-transition: all 0.3s ease-out; 
	-o-transition: all 0.3s ease-out; 
	transition: all 0.3s ease-out;
}

.mfp-with-zoom.mfp-ready .mfp-container {
		opacity: 1;
}
.mfp-with-zoom.mfp-ready.mfp-bg {
		opacity: 0.8;
}

.mfp-with-zoom.mfp-removing .mfp-container, 
.mfp-with-zoom.mfp-removing.mfp-bg {
	opacity: 0;
}
*/
</style>
	<link rel="stylesheet" href="{{url('vendor/chosen/chosen.css')}}">
 	<section id="slider" class="slider-parallax swiper_wrapper clearfix" style="height: 600px;" data-loop="true">

		<div class="swiper-container swiper-parent">
			<div class="swiper-wrapper">
			
				@foreach($data_slideshow as $item)
				<div class="swiper-slide dark" data-timeout="{{ $item->timeout*1000 }}" style="background-image: url('media/slideshow/<?php echo $item->image?>');">
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



				<div id="tutorial" class="clear"></div>

				<div class="divider"><i class="icon-circle"></i></div>
				<div id="section-playlist" class="title-center page-section">
					<h2>{{ trans('translate.video_tutorial') }}</h2>
				</div>
				<div id="root">
				  <iframe          
				          src="{{ $playlist_url }}&listType=playlist&index=0" 
				          frameborder="0" 
				          allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
				          allowfullscreen="1"
				          id="iframe_yt"
				          ></iframe>
				</div>
				<!-- <div class="playlist"></div> -->
				
				<div class="clear"></div>

				<div class="divider"><i class="icon-circle"></i></div>
				<div id="section-partner" class="heading-block title-center page-section">
					<h2>{{ trans('translate.our_partner') }}</h2>
				</div>
				@if ($count_partners > 5)
					<ul class="clients-grid grid-6 nobottommargin clearfix">
						@foreach($partners as $item)
							<li><a href="#"><img src="{{asset('media/footer/'.$item->image)}}" alt="partners"></a></li>
						@endforeach
					</ul>
				@else
					<ul class="clients-grid grid-{{$count_partners}} nobottommargin clearfix">
						@foreach($partners as $item)
							<li><a href="#"><img src="{{asset('media/footer/'.$item->image)}}" alt="partners"></a></li>
						@endforeach
					</ul>
				@endif

				<div class="clear"></div>

				<!-- <div class="divider divider-short divider-center"><i class="icon-circle"></i></div>

				<div id="section-contact" class="heading-block title-center page-section">
					<h2>{{ trans('translate.contact') }}</h2>
					<span>{{ trans('translate.contact_description') }}</span>
				</div> -->

				<!-- Contact Form
				============================================= -->
				<!-- <div class="col_full">

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

							<div class="col-md-8 col-md-offset-2">
								<div class="g-recaptcha" data-sitekey="6Le87jIUAAAAADQF_jaDT4vnN0yiKM8kFoLJdICO" data-callback="recaptchaCallback"></div>
								<input type="hidden" class="hiddenRecaptcha required sm-form-control" name="hiddenRecaptcha" id="hiddenRecaptcha">
							</div>

							<div class="clear"></div>

							<div class="col-md-8 col-md-offset-2" style="margin-top:15px">
								<button type="submit" class="button button-3d nomargin full btn-sky" type="submit" name="template-contactform-submit" value="submit">{{ trans('translate.contact_send') }}</button>
							</div>

						</form>

					</div>


				</div> -->
				<!-- Contact Form End -->

				</div>



			</div>

		</div>

	</section><!-- #content end -->
	@if($data_pop_up_information)
		<a class="image-popup-no-margins" href="media/popupinformation/<?php echo $data_pop_up_information[0]->image?>" style = "display: none;">
			<img src="media/popupinformation/<?php echo $data_pop_up_information[0]->image?>" style="width: 100%; height: auto;">
		</a>
	@endif
		<!-- <div id="modal_notice" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
	        <img src="media/certification/<?php // echo $data_pop_up_information[0]->image?>" class="modal-content">
	        <div id="caption"><span class="close" data-dismiss="modal">{{ trans('translate.close') }} <i class="fa fa-times-circle-o" aria-hidden="true"></i></span></div>
		</div> -->
@endsection

@section('content_js')
	@if($data_pop_up_information)
		<script>
			(function($){
			    $(window).load(function() {
			       $('.image-popup-no-margins').trigger('click');    	
			    });
			})(jQuery);
		</script>
	@endif
<script>
	$(document).ready(function() {

		$('.image-popup-no-margins').magnificPopup({
			type: 'image',
			enableEscapeKey: false,
			closeOnBgClick: false,
			closeOnContentClick: false,
			fixedContentPos: true,
			mainClass: 'mfp-img-mobile',
			image: {
				verticalFit: true
			},
			zoom: {
				enabled: true,
				duration: 300 // don't foget to change the duration also in CSS
			}
		});

	});

	// Set individual slide timeout for dynamic autoplay
	var setSwiperSlideTimeout = function ( swiper ) {
	    var timeout = $( swiper.slides[ swiper.activeIndex ] ).data( "timeout" );

	    if (timeout === undefined || timeout === "" || timeout === 0) {
	        timeout = 60000;
	    }

	    var slideNumberCurrent = $("#slide-number-current");
	    if( slideNumberCurrent.length > 0 ){
			slideNumberCurrent.html( Number( $('.swiper_wrapper').find('.swiper-slide.swiper-slide-active').attr('data-swiper-slide-index') ) + 1 );
		}

	    swiper.params.autoplay = timeout;
	    swiper.update();
	    swiper.startAutoplay();
	};

	var mySwiper = new Swiper('.swiper-container', {
	    nextButton: '#slider-arrow-right',
	    prevButton: '#slider-arrow-left',
	    slidesPerView: 1,
	    paginationClickable: true,
	    autoplay: 0, // CHANGED THIS FROM 5000 to 0
	    loop: true,
	    onInit: function ( currentSwiper ) {
	        currentSwiper.stopAutoplay();
	        setSwiperSlideTimeout( currentSwiper );
	    },
	    onSlideChangeEnd: function ( currentSwiper ) {
	        currentSwiper.stopAutoplay();
	        setSwiperSlideTimeout( currentSwiper );
	    }
	});
$(document).ready(function(){
	if("{{ count($data_pop_up_information) }}">0){$("#modal_notice").modal('show');}
  // Add smooth scrolling to all links
  $("#click-section-playlist").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });

});
</script>

<script>
	$(".chosen-select").chosen(); 
</script>
<script src="{{url('vendor/chosen/chosen.jquery.js')}}" type="text/javascript"></script> 
@endsection