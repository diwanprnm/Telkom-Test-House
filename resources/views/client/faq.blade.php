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
						@php $cat = 1; @endphp
						@foreach ($data as $category_key => $values)
						<div class="col_one_fourth nobottommargin {{ $i = 4 ? 'col_last' : '' }}">
							<h4>{{ strtoupper($category_key)}}
							<div class="panel panel-default">
								@for($i = 0; $i < count($values); $i++)
								@php $cat++; @endphp
								<div class="panel-heading"> <!-- QUESTION -->
									<h6 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-target="#collapse-cat{{$cat}}" href="javascript:void(0);">{{$i+1}}. {{$values[$i]->question}}</a>
									</h6>
								</div>
								<div id="collapse-cat{{$cat}}" class="panel-collapse collapse"> <!-- ANSWER -->
									<div class="panel-body">
										{!! $values[$i]->answer !!} 
									</div>
								</div>
								@endfor
							</div>
						</div>
						@endforeach
						<div class="line"></div>
					</div>
					
				</div>

			</div>

		</section><!-- #content end -->


@endsection