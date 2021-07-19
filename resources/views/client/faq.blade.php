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
						<?php
							for ($i=0; $i <7 ; $i++) { 
						?>
								<div class="col_one_fourth nobottommargin {{ fmod($i+1, 4) == 0 ? 'col_last' : '' }}">
									<h4>REGISTRASI AKUN</h4>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h6 class="panel-title">
												<a class="accordion-toggle" data-toggle="collapse" data-target="#collapse{{ $i }}" href="javascript:void(0);">Berapa lama waktu yang dibutuhkan untuk aktivasi akun baru?</a>
											</h6>
										</div>
										<div id="collapse{{ $i }}" class="panel-collapse collapse">
											<div class="panel-body">
												Setelah pelanggan melakukan registrasi akun, Admin akan melakukan approval terlebih dahulu paling lama 1 hari. Kemudian Admin akan menghubungi pelanggan bahwa akun sudah aktif dan dapat digunakan. 
											</div>
										</div>
									</div>
									<div class="line"></div>
								</div>
						<?php	
							}
						?>
					</div>
					
				</div>

			</div>

		</section><!-- #content end -->


@endsection