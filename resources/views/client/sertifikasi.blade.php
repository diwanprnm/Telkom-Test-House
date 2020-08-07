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
							<?php if(count($data)>0){
								echo strip_tags($data[0]->description);
							}
							?>
						@else
							<?php if(count($data)>0){
								echo strip_tags($data[0]->description_english);
							}
							?>
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
<!--
						<article class="portfolio-item pf-illustrations">
							<div class="portfolio-image">
								<a href="#">
									<img src="images/certificate/cf lab - kalibrasi.jpg" alt="Locked Steel Gate">
								</a>
								<div class="portfolio-overlay">
									<a href="images/certificate/cf lab - kalibrasi.jpg" class="center-icon" data-lightbox="image"><i class="icon-line-search"></i></a>
								</div>
							</div>
							<div class="portfolio-desc">
								<h3>SERTIFIKASI AKREDITASI LABORATORIUM KALIBRASI</h3>
							</div>
						</article>

						<article class="portfolio-item pf-graphics pf-uielements">
							<div class="portfolio-image">
								<a href="#">
									<img src="images/certificate/cf - lab qa.png" alt="Mac Sunglasses">
								</a>
								<div class="portfolio-overlay">
									<a href="images/certificate/cf - lab qa.png" class="center-icon" data-lightbox="image"><i class="icon-line-search"></i></a>
								</div>
							</div>
							<div class="portfolio-desc">
								<h3>SERTIFIKASI AKREDITASI LABORATORIUM QA</h3>
							</div>
						</article>
-->
					</div><!-- #portfolio end -->
					</div>

				</div>



				</div>

			</div>

		</section><!-- #content end -->
@endsection
 
