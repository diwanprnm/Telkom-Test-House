@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>FAQ - Telkom DDB</title>
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>FAQ</h1>
				
				<ol class="breadcrumb">
					<li class="active">FAQ</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">


				<div class="container clearfix">

					<div id="faqs" class="faqs">

						<h3>Some of your Questions:</h3>

						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, velit, eum, delectus aliquid dolore numquam dolorem assumenda nisi nemo eveniet et illo tempore voluptatem cum in repudiandae pariatur. Architecto, exercitationem perspiciatis nam quod tenetur alias necessitatibus quibusdam eum accusamus a.</p>

						<div class="divider"><em class="icon-circle"></em></div>
						<div class="col_one_fourth nobottommargin">
							<h4>REGISTRASI AKUN</h4> <!-- CATEGORY -->
							<div class="panel panel-default">
								<div class="panel-heading"> <!-- QUESTION -->
									<h6 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-target="#collapse-cat1" href="javascript:void(0);">1. Berapa lama waktu yang dibutuhkan untuk aktivasi akun baru?</a>
									</h6>
								</div>
								<div id="collapse-cat1" class="panel-collapse collapse"> <!-- ANSWER -->
									<div class="panel-body">
										Setelah pelanggan melakukan registrasi akun, Admin akan melakukan approval terlebih dahulu paling lama 1 hari. Kemudian Admin akan menghubungi pelanggan bahwa akun sudah aktif dan dapat digunakan. 
									</div>
								</div>
								<div class="panel-heading"> <!-- QUESTION -->
									<h6 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-target="#collapse-cat2" href="javascript:void(0);">2. Berapa lama waktu yang dibutuhkan untuk aktivasi akun baru?</a>
									</h6>
								</div>
								<div id="collapse-cat2" class="panel-collapse collapse"> <!-- ANSWER -->
									<div class="panel-body">
										Setelah pelanggan melakukan registrasi akun, Admin akan melakukan approval terlebih dahulu paling lama 1 hari. Kemudian Admin akan menghubungi pelanggan bahwa akun sudah aktif dan dapat digunakan. 
									</div>
								</div>
							</div>
							<div class="line"></div>
						</div>
					</div>
					
				</div>

			</div>

		</section><!-- #content end -->


@endsection