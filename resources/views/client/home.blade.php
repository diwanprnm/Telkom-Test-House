@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.home') }} - Telkom DDS</title>
@section('content')
<style type="text/css">
	#root {
	  display: flex;
	}

	.playlist {
	  display: flex;
	  box-sizing: border-box;
	  flex-direction: column;
	  margin: 0 20px;
	  height: 270px;
	  width: 150px;
	  overflow-y: hidden;
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
</style>
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
					<h2>{{ trans('translate.video_tutorial') }}</h2>
				</div>
				<div id="root">
				  <iframe 
				          width="438" 
				          height="250"         
				          src="https://www.youtube.com/embed?listType=playlist&list=PLl3Z5rVQaSyXdmOjIJ2pKhBAIAEOno63C&index=0" 
				          frameborder="0" 
				          allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
				          allowfullscreen="1"
				          id="iframe_yt"
				          ></iframe>
				  <div>
				    <div class="center_arrow">
				      <img src="http://aux.iconspalace.com/uploads/drop-down-vector-icon-256.png" width="25px" style="transform: scaleY(-1);" onClick="goUp();"/>
				    </div>
				    <div class="playlist"></div>
				    <div class="center_arrow">
				      <img src="http://aux.iconspalace.com/uploads/drop-down-vector-icon-256.png" width="25px" onClick="goDown();"/>
				      </div>
				  </div>
				</div>
				
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
@endsection

@section('content_js')
<script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>
<script type="text/javascript">
	const API_KEY = 'AIzaSyATpem3sOT-CfhfLsdeNpA3000nALWnE10';
	const API_ID = '724749132562-h3kncergsa5988h38nveevo7bra8ohmu.apps.googleusercontent.com';
	const PLAYLIST_ID = 'PLl3Z5rVQaSyXdmOjIJ2pKhBAIAEOno63C';

	var videoVisible = 0;
	var maxVideoVisible = 50;

	const playlistItems = [];
	const playlist = document.getElementsByClassName("playlist")[0];

	// From Google API Docs
	window.onLoadCallback = function(){
	  	gapi.load("client:auth2", function() {
	    gapi.auth2.init({client_id: API_ID});
	    loadClient().then(getPlaylistItems);
	  });  
	}
	/*function initAPIClient() {
	  gapi.load("client:auth2", function() {
	    gapi.auth2.init({client_id: API_ID});
	    loadClient().then(getPlaylistItems);
	  });  
	};*/

	// From Google API Docs
	function loadClient() {
	  gapi.client.setApiKey(API_KEY);
	  return gapi.client.load("https://www.googleapis.com/discovery/v1/apis/youtube/v3/rest")
	    .then(
	    function() { 
	      console.log("GAPI client loaded for API"); },
	    function(err) { 
	      console.error("Error loading GAPI client for API", err); 
	    });
	}

	function getPlaylistItems(pageToken) {
	    return gapi.client.youtube.playlistItems.list({
	      "part": "snippet,contentDetails",
	      "maxResults": 50, // This is the maximum available value, according to the google docs
	      "playlistId": PLAYLIST_ID,
	      pageToken
	    })
	      .then(function(response) {
	      const { items, nextPageToken } = response.result;
	      
	      // The items[] is an array of a playlist items.
	      // nextPageToken - if empty - there are no items left to fetch
	      
	      playlistItems.push(...items);
	      
	      // It's up to you, how to handle the item from playlist. 
	      // Add `onclick` events to navigate to another video, or use another thumbnail image quality 
	      var index = 0;
	      items.forEach(function(item) {
	          const thumbnailItem = createPlaylistItem(item, index);
	          playlist.appendChild(thumbnailItem);
	          index++;
	        });
	      
	      maxVideoVisible = index - 3;
	      

	      // Recursively get another portion of items, if there any left
	      if (nextPageToken) {
	        getPlaylistItems(nextPageToken);        
	      }
	    },
	  function(err) { console.error("Execute error", err); });
	}

	function createPlaylistItem(i, index) {
	  var changeIndex = index;
	  const item = document.createElement('div');
	  item.classList.add('playlist-item');
	  item.style.background = `url(https://i.ytimg.com/vi/${i.snippet.resourceId.videoId}/mqdefault.jpg) no-repeat center`;
	  item.style.backgroundSize = 'contain';
	  item.id = index.toString();
	  item.addEventListener("click", function(){ 
	    document.getElementById('iframe_yt').src = "https://www.youtube.com/embed?listType=playlist&list=PLl3Z5rVQaSyXdmOjIJ2pKhBAIAEOno63C&autoplay=1&index=" + changeIndex.toString();
	  });
	  return item;
	};

	// Before doing any action with the API ->
	// initAPIClient();

	function goUp(){
	  if (videoVisible > 0){
	    videoVisible--;
	    document.getElementById(videoVisible.toString()).style.height = "90px";
	  }
	}
	function goDown(){
	    if (videoVisible < maxVideoVisible){
	      document.getElementById(videoVisible.toString()).style.height = "0px";
	     videoVisible++;    
	  }  
	}

</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	$(".chosen-select").chosen(); 
</script>
<script src="{{url('vendor/chosen/chosen.jquery.js')}}" type="text/javascript"></script> 
@endsection