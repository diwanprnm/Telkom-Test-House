@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>FAQ - Telkom Test House</title>
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

					<div class="row"> 
						<a class="btn btn-default pull-right" style="margin-right: 1.25rem" href="{{URL::to('faq')}}">Reset <em class="fa fa-refresh"></em></a>
						<div class="col-md-4 pull-right">
							<span class="input-icon input-icon-right search-table">
								<input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
								<em class="ti-search"></em>
							</span>
						</div> 
					</div>

					<div class="panel-group" id="accordion">
						@php $cat = 1; @endphp

						@foreach ($data as $category_key => $values)
							<div class="faqHeader" style="font-size: 150%;margin-top: 1%;margin-bottom: 1%;">{{ $category_key}}</div>
							@for($i = 0; $i < count($values); $i++)
							@php $cat++; @endphp
							<div class="panel panel-default">
								<div class="panel-heading"> <!-- QUESTION -->
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" data-target="#collapse-cat{{$cat}}" href="javascript:void(0);">{{$i+1}}. {{$values[$i]->question}}</a>
									</h4>
								</div>
								<div id="collapse-cat{{$cat}}" class="panel-collapse collapse"> <!-- ANSWER -->
									<div class="panel-body">
										{!! $values[$i]->answer !!} 
									</div>
								</div>
							</div>
							@endfor
							<div class="divider"><em class="icon-circle"></em></div>
						@endforeach
					</div>
				</div>
			</div>
		</section><!-- #content end -->
@endsection

@section('content_js')
<script type="text/javascript">
	jQuery(document).ready(function() {       
		$('#search_value').keydown(function(event) {
            if (event.keyCode == 13) {
                var baseUrl = "{{URL::to('/')}}";
                var params = {
                    search:document.getElementById("search_value").value,
                };
                document.location.href = baseUrl+'/faq?'+jQuery.param(params);
            }
        }); 
	});
</script>
@endsection