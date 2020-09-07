@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.about') }} {{ trans('translate.about_us') }} - Telkom DDB</title>
@section('content')
<style type="text/css">
	.fluid-width-video-wrapper iframe {
	    width: 50%;
	    height: 50%;
	}
</style>
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.about') }} {{ trans('translate.about_us') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_company') }}</a></li>
					<li class="active">{{ trans('translate.about') }} {{ trans('translate.about_us') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">


				<div class="container clearfix">

					<div class="col_full">
						<img src="images/slider.jpg">
					</div>

					<div class="col_full">
						@if( Config::get('app.locale') == 'in')
							<?php if(count($data)==0){
								echo "Data Not Found";
							}else{
								echo strip_tags($data[0]->description);
							}
							?>
						@else
							<?php if(count($data)==0){
								echo "Data Not Found";
							}else{
								echo strip_tags($data[0]->description_english);
							}
							?>
						@endif
					</div>
					
				</div>



				</div>

			</div>

		</section><!-- #content end -->


@endsection
 
