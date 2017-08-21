@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.process') }} - Telkom DDS</title>
@section('content')
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
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<a href="{{url('detailprocess/qa')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
				</div>

				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabEnergi.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Type Approval (TA)</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<a href="{{url('detailprocess/ta')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
				</div>

				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabKabel.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Voluntary Test (VT)</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<a href="{{url('detailprocess/vt')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
				</div>

				<div class="col-md-12 container-list">
					<div class="col-md-4">
						<img src="{{asset('template-assets/img/portfolio/LabTransmisi.jpg')}}">
					</div>
					<div class="col-md-8">
						<h4>Calibration (CAL)</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<a href="{{url('detailprocess/cal')}}" class="button button-3d nomargin btn-sky">{{ trans('translate.process') }}</a>
					</div>
				</div>


			</div>
			

		</div>



	</div>


</section><!-- #content end -->

@endsection
 
