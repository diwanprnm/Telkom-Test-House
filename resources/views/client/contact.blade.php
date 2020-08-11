@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.contact') }} - Telkom DDS</title>
@section('content')
		<link rel="stylesheet" href="{{url('vendor/chosen/chosen.css')}}">
		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.contact') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_company') }}</a></li>
					<li class="active">{{ trans('translate.contact') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">


				<div class="container clearfix">
				
					<div class="col_one_third col_last contact-address">
							<div class="row">
										<address>
											<strong>{{ trans('translate.service_company_address') }}:</strong><br>
											Jl. Gegerkalong Hilir, Sukarasa, Sukasari<br>
											Kota Bandung, Jawa Barat 40152<br>
										</address>
										<abbr title="Customer Service"><strong>{{ trans('translate.service_company_cs') }}:</strong></abbr> (+62) 812-2483-7500<br>
										<abbr title="Email Address"><strong>{{ trans('translate.service_company_email') }}:</strong></abbr> urelddstelkom@gmail.com
									</div>
					</div>

				</div>



				</div>

			</div>

		</section><!-- #content end -->


@endsection
 

@section('content_js')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	$(".chosen-select").chosen(); 
</script>
<script src="{{url('vendor/chosen/chosen.jquery.js')}}" type="text/javascript"></script> 
@endsection