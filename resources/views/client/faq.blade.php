@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>FAQ - Telkom DDB</title>
@section('content')

<style>
	.grid-container {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		padding: 2px;
		grid-auto-flow: row;
		justify-items: start;
		align-items: start;
		place-items: start;
	}
	.grid-item {
		display: grid;
		background-color: rgba(255, 255, 255, 0.8);
		border: 1px solid rgba(0, 0, 0, 0.8);
		padding: 10px;
		font-size: 30px;
		text-align: center;
		grid-column-start: 1;
  		grid-column-end: 4;
		width: 25%;
		justify-self: center;
		align-self: auto;
	}
</style>
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
						<div class="col-md-6 pull-right">
							<span class="input-icon input-icon-right search-table">
								<input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="">
								<em class="ti-search"></em>
							</span>
						</div> 
					</div>

					<div class="grid-container">

						<h3>Some of your Questions:</h3>

						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, velit, eum, delectus aliquid dolore numquam dolorem assumenda nisi nemo eveniet et illo tempore voluptatem cum in repudiandae pariatur. Architecto, exercitationem perspiciatis nam quod tenetur alias necessitatibus quibusdam eum accusamus a.</p>

						<div class="divider"><em class="icon-circle"></em></div>

						@php $cat = 1; @endphp

						@foreach ($data as $category_key => $values)
							<div class="grid-item">
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
					</div>
				</div>
			</div>
		</section><!-- #content end -->
@endsection

@section('content_js')
<script src={{ asset("vendor/maskedinput/jquery.maskedinput.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js") }}></script>
<script src={{ asset("vendor/autosize/autosize.min.js") }}></script>
<script src={{ asset("vendor/selectFx/classie.js") }}></script>
<script src={{ asset("vendor/selectFx/selectFx.js") }}></script>
<script src={{ asset("vendor/select2/select2.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.js") }}></script>
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
<script type="text/javascript">
$( function() {
		$( "#search_value" ).autocomplete({
			minLength: 3,
			source: function (request, response) {
				$.ajax({
					type: 'GET',
					url: 'adm_stel_autocomplete/'+request.term,
					dataType: "json",
					cache: false,
					success: function (data) {
						console.log(data);
						response($.map(data, function (item) {
							return {
								label:item.autosuggest
							};
						}));
					},
				});
			}, 
			select: function( event, ui ) {
				$( "#search_value" ).val( ui.item.label );
				return false;
			}
		})

		.autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<div>" + item.label + "</div>" )
			.appendTo( ul );
		};
	});
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