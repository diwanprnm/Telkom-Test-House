@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.see_product') }} - Telkom DDS</title>
@section('content') 
</style>
	<section id="page-title"> 
		<div class="container clearfix">
			<h1>{{ trans('translate.see_product') }}</h1>
			
			<ol class="breadcrumb">
				<li><a href="#">STEL</a></li>
				<li class="active">{{ trans('translate.see_product') }}</li>
			</ol>
		</div>

	</section><!-- #page-title end -->

<!-- Content
============================================= -->
	<section id="content">

	<div class="content-wrap"> 

		<div class="container clearfix box">
			@if (Session::get('error'))
				<div class="alert alert-error alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
					{{ Session::get('error') }}
				</div>
			@endif

			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 left-content"></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 right-content"><a href="#videoStory" class="btn btn-default btn-lg more pull-right" id="videoLink">{{ trans('translate.video_guide') }} <i class="fa fa-play-circle" aria-hidden="true">&nbsp;</i></a></div>
			</div>
			<div id="videoStory" class="mfp-hide" style="max-width: 75%; margin: 0 auto;">
				<iframe title="video story" width="853" height="480" src="{{ $video_url }}" allowfullscreen></iframe>
			</div>

		</div> 
		<div class="container  pagecontainer offset-0"> 
			<!-- LIST CONTENT-->
			<div class="rightcontent col-md-9 offset-0">
			
				<div class="hpadding20">
					<!-- Top filters -->
					<div class="topsortby">
						<div class="col-md-4 offset-0"> 
							<span class="input-icon input-icon-right search-table"> 
								<input id="search_stel_product" name="search" type="text" placeholder="{{ trans('translate.search_STEL') }}" id="form-field-17" class="form-control " value="{{ $search }}">
								<em class="ti-search"></em>
							</span> 
						</div>			
						<div class="col-md-4">
						</div>
						<div class="col-md-4 offset-0 right">
							 <div class="btn-group">
					            <a href="#" id="list" class="btn btn-default btn-sm">
					            <em class="fa fa-th"></em> List</a> 
					            <a href="#" id="grid" class="btn btn-default btn-sm">
					            	<em class="fa fa-th-large"></em>
					            Grid</a>
					        </div>
						</div>
					</div>
					<!-- End of topfilters-->
				</div>
				<!-- End of padding --> 
				<div class="clearfix"></div> 
				<div id="products" class=" list-group">
				 	@foreach($stels as $stel) 
			        <div class="item  col-xs-4 col-lg-4 list-group-item">
			            <div class="thumbnail">
			                <img class="group list-group-image" src="images/product/note.png" width="240px" alt="" />
			                <div class="caption">
			                    <h4 class="group inner list-group-item-heading">
			                       {{$stel->name}}
			                    </h4>
			                   	<p class="group inner list-group-item-text">
			                    	{{ trans('translate.stel_code') }}	:	{{$stel->code}}
								</p>
			                    <div class="row">
			                        <div class="col-xs-12 col-md-6">
			                            <p class="lead">
			                              {{ trans('translate.stel_rupiah') }}. @php echo number_format($stel->price); @endphp<span style="color:red;font-size: 12px;"> {{ trans('translate.stel_exclude') }} ppn (10%)</span></p>
			                        </div>
			                        <div class="col-xs-12 col-md-6">
			                        <form action="products" method="POST">
										{!! csrf_field() !!}
										<input type="hidden" name="id" value="{{ $stel->id }}">
										<input type="hidden" name="name" value="{{ $stel->name }}">
										<input type="hidden" name="code" value="{{ $stel->code }}">
										<input type="hidden" name="price" value="{{ $stel->price }}">
										@php  
										$is_exist = false;
										 foreach (Cart::content() as $item) {
										 	if($item->id == $stel->id){$is_exist = true;}
										 } 
										@endphp

									  	@if(($stel->is_buyed == 0) && !$is_exist )
									  		<input type="submit" class="btn btn-success" value="{{ trans('translate.stel_add_to_cart') }}">
										@else
											@if($is_exist)
												<label style="color:red;">{{ trans('translate.stel_in_cart') }}</label>
											@else
												<label style="color:red;">{{ trans('translate.stel_buyed') }}</label>
											@endif
										
										@endif
									</form>
			                            
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </div>

			        @endforeach
			         
			    </div>
				<!-- End of offset1-->	

				<div class="hpadding20">
					@php echo $stels->render(); @endphp 

				</div>

			</div>
			<!-- END OF LIST CONTENT-->


			<!-- FILTERS -->
			<div class="col-md-3 filters offset-0">
				<div class="header-cart padding20">
					<div class="header-cart-icon"><em class="icon-cart"></em></div>
					<div class="header-cart-text"><h4> {{ trans('translate.stel_total_in_cart') }}<br><span>{{Cart::count()}} Pcs</span></h4></div>
				</div>
				<div style="overflow-y: auto; max-height:750px;">
					@foreach(Cart::content() as $row)
					<div class="line2"></div>
					<div class="list-cart padding20">
						{!! Form::open(array('url' => 'products/'.$row->rowId, 'method' => 'DELETE')) !!}
														{!! csrf_field() !!}
						<button class="btn btn-transparent btn-xs pull-right" tooltip-placement="top" tooltip="Remove" onclick="return confirm('{{ trans('translate.stel_delete_item') }}')"><em class="fa fa-times fa fa-white"></em></button>
						{!! Form::close() !!}
						@php 
							$res = explode('myTokenProduct', $row->name);
							$stel_name = $res[0] ? $res[0] : '-';
							$stel_code = $res[1] ? $res[1] : '-';
						@endphp
						<p>{{ trans('translate.stel_name') }} : {{$stel_name}}</p>
						<p>{{ trans('translate.stel_price') }} : {{ trans('translate.stel_rupiah') }} @php echo number_format($row->qty*$row->price); @endphp(@php echo $row->qty@endphp)</p>
					</div>
					@endforeach
				</div>
				
				<!-- TOP TIP -->
				<div class="filtertip">
					<div class="padding20" style="background-color: maroon;">
						<h4>{{ trans('translate.stel_price_total') }}<br>{{ trans('translate.stel_rupiah') }} @php echo number_format(Cart::subTotal(), 0, '.', ','); @endphp</h4>
					</div>
				</div>
				
				<div class="list-cart padding20">
				@php if(Cart::count() > 0){@endphp
					<a class="button button-3d nomargin full btn-sky" href="#myModal1"  data-lightbox="inline" >{{ trans('translate.stel_checkout') }}</a>
				@php }else{@endphp
					<a class="button button-3d nomargin full btn-sky"  >{{ trans('translate.stel_checkout') }}</a>
				@php }@endphp
				</div> 

					<!-- Modal -->
					<div class="modal1 mfp-hide" id="myModal1">
						<div class="block divcenter" style="background-color: #FFF; max-width: 500px;">
							<div  style="padding: 50px;">
								<h3>{{ trans('translate.stel_term_condition') }}</h3>
								<ul>
									<li>{{ trans('translate.stel_term_condition_num1') }}</li>
									<li>{{ trans('translate.stel_term_condition_num2') }}</li>
									<li>{{ trans('translate.stel_term_condition_num3') }}</li>
									<li>{{ trans('translate.stel_term_condition_num4') }}</li>
									<li>{{ trans('translate.stel_term_condition_num5') }}</li>
									<li>{{ trans('translate.stel_term_condition_num6') }}</li>
								</ul>
							</div>
							<form class="form-horizontal" role="form" method="GET" action="{{ url('checkout') }}" onsubmit="javascript:document.getElementById('submit-btn').style.display = 'none';document.getElementById('submit-msg').style.display = 'block';">
                        		{{ csrf_field() }}
								<div style="padding: 20px;">
									<input type="checkbox" name="agree" required value="1"> {{ trans('translate.stel_agree_statement') }}
								</div>
								<div id="submit-btn" class="section nomargin" style="padding: 10px;">
									<button class="button button-3d btn-blue" type="submit">{{ trans('translate.stel_agree') }}</button> 
									<a class="button button-3d btn-grey" onClick="$.magnificPopup.close();return false;">{{ trans('translate.stel_not_agree') }}</a>
								</div>
								<div id="submit-msg" class="section nomargin" style="padding: 10px;" hidden="">
									<p>Please Wait ...</p>
								</div>
							</form>
						</div>
					</div>
				
			</div>
			<!-- END OF FILTERS -->

		</div> 
	</div>


	</section><!-- #content end -->
@endsection
 
@section('content_js')
<script type="text/javascript">
	$('#videoLink').magnificPopup({
		type:'inline',
		midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  	});
</script>
@endsection