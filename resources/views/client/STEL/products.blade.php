@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.see_product') }} - Telkom DDS</title>
@section('content') 

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

			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 left-content"></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 right-content"></div>
			</div>

		</div> 
		<div class="container  pagecontainer offset-0"> 
			<!-- LIST CONTENT-->
			<div class="rightcontent col-md-9 offset-0">
			
				<div class="hpadding20">
					<!-- Top filters -->
					<div class="topsortby">
						<div class="col-md-4 offset-0">

						</div>			
						<div class="col-md-4">
						</div>
						<div class="col-md-4 offset-0 right">
							 <div class="btn-group">
					            <a href="#" id="list" class="btn btn-default btn-sm">
					            <i class="fa fa-th"></i> List</a> 
					            <a href="#" id="grid" class="btn btn-default btn-sm">
					            	<i class="fa fa-th-large"></i>
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
			                              {{ trans('translate.stel_rupiah') }}. <?php echo number_format($stel->price + ($stel->price * (config('cart.tax')/100)), 0, '.', ','); ?><span style="color:red;font-size: 12px;"> {{ trans('translate.stel_include') }} ppn (10%)</span></p>
			                        </div>
			                        <div class="col-xs-12 col-md-6">
			                        <form action="products" method="POST">
									  {!! csrf_field() !!}
									  <input type="hidden" name="id" value="{{ $stel->id }}">
									  <input type="hidden" name="name" value="{{ $stel->name }}">
									  <input type="hidden" name="code" value="{{ $stel->code }}">
									  <input type="hidden" name="price" value="{{ $stel->price }}">
									  <input type="submit" class="btn btn-success" value="{{ trans('translate.stel_add_to_cart') }}">
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
					<?php echo $stels->render(); ?> 

				</div>

			</div>
			<!-- END OF LIST CONTENT-->


			<!-- FILTERS -->
			<div class="col-md-3 filters offset-0">
				<div class="header-cart padding20">
					<div class="header-cart-icon"><i class="icon-cart"></i></div>
					<div class="header-cart-text"><h4> {{ trans('translate.stel_total_in_cart') }}<br><span>{{Cart::count()}} Pcs</span></h4></div>
				</div>
				@foreach(Cart::content() as $row)
				<div class="line2"></div>
				<div class="list-cart padding20">
					{!! Form::open(array('url' => 'products/'.$row->rowId, 'method' => 'DELETE')) !!}
													{!! csrf_field() !!}
					<button class="btn btn-transparent btn-xs pull-right" tooltip-placement="top" tooltip="Remove" onclick="return confirm('{{ trans('translate.stel_delete_item') }}')"><i class="fa fa-times fa fa-white"></i></button>
					{!! Form::close() !!}
					<p>{{ trans('translate.stel_name') }} : {{$row->name}}</p>
					<p>{{ trans('translate.stel_price') }} : {{ trans('translate.stel_rupiah') }} <?php echo number_format($row->price + ($row->price * (config('cart.tax')/100)), 0, '.', ','); ?></p>
				</div>
			 	@endforeach
				
				<!-- TOP TIP -->
				<div class="filtertip">
					<div class="padding20" style="background-color: maroon;">
						<h4>{{ trans('translate.stel_price_total') }}<br>{{ trans('translate.stel_rupiah') }} <?php echo number_format(Cart::total(), 0, '.', ','); ?></h4>
					</div>
				</div>
				
				<div class="list-cart padding20">
					<a class="button button-3d nomargin full btn-sky" href="#myModal1"  data-lightbox="inline" >Checkout</a>
				</div> 

					<!-- Modal -->
					<div class="modal1 mfp-hide" id="myModal1">
						<div class="block divcenter" style="background-color: #FFF; max-width: 500px;">
							<div  style="padding: 50px;">
								<h3>{{ trans('translate.stel_term_condition') }}</h3>
								<p class="nobottommargin">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum delectus, tenetur obcaecati porro! Expedita nostrum tempora quia provident perspiciatis inventore, autem eaque, quod explicabo, ipsum, facilis aliquid! Sapiente, possimus quo!</p> 
								<ul>
									<li> 1. Pela</li>
									<li> 1. Pela</li>
									<li> 1. Pela</li>
								</ul>
							</div>
							   <form class="form-horizontal" role="form" method="POST" action="{{ url('checkout') }}">
                        {{ csrf_field() }}
							<div style="padding: 20px;">
								<input type="checkbox" name="agree" required value="1"> {{ trans('translate.stel_agree_statement') }}
							</div>
							<div class="section nomargin" style="padding: 10px;">
								<button class="button button-3d btn-blue" type="submit">{{ trans('translate.stel_agree') }}</button> 
								<a class="button button-3d btn-grey" onClick="$.magnificPopup.close();return false;">{{ trans('translate.stel_not_agree') }}</a>
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
 
