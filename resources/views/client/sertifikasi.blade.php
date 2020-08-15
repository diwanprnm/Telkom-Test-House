@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.certification') }} - Telkom DDS</title>
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.certification') }} </h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_company') }}</a></li>
					<li class="active">{{ trans('translate.certification') }} </li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">


				<div class="container clearfix">

					<div class="col_full">
						@if( Config::get('app.locale') == 'in')
							@php if(count($data)>0){
								echo strip_tags($data[0]->description);
							}
							@endphp
						@else
							@php if(count($data)>0){
								echo strip_tags($data[0]->description_english);
							}
							@endphp
						@endif
					</div>

					<div class="col_full">

					<!-- Portfolio Items
					============================================= -->
					<div id="portfolio" class="portfolio grid-container portfolio-3 clearfix">
					
					@foreach($data_certification as $item)
					<article class="portfolio-item pf-media pf-icons">
						<div class="portfolio-image">
							<a href="#">
								<img src="{{ asset('media/certification/'.$item->image) }}" alt="Open Imagination">
							</a>
							<div class="portfolio-overlay">
								<a href="{{ asset('media/certification/'.$item->image) }}" class="center-icon" data-lightbox="image"><em class="icon-line-search"></em></a>
							</div>
						</div>
						<div class="portfolio-desc">
							<h3>{{ $item->title }}</h3>
						</div>
					</article>
					@endforeach
					</div><!-- #portfolio end -->
					</div>

				</div>



				</div>

			</div>

		</section><!-- #content end -->
@endsection
 
