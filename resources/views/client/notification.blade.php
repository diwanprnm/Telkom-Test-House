@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.notification') }} - Telkom DDS</title>
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.notification') }}</h1> 

				<ol class="breadcrumb">
					<li><a href="{{url('/')}}">{{ trans('translate.home') }}</a></li>
					<li class="active">{{ trans('translate.notification') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap"> 
				<div class="container clearfix">

					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive font-table">
									<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
										<thead>
											<tr>
												<th class="center">No</th>
												<th class="center">{{ trans('translate.notification') }}</th>
										</thead>
										<tbody>
											<?php $no=1; if($notification_data_count>0){ ?>
											@foreach($notification_data as $item)
											<tr>
												<td class="center">{{$no}}</td>
												<td class="left"><a data-id="<?php echo $item['id'];?>" data-url="{{$item['url']}}" class="notifData">
													@if($item['is_read'])
														{{$item['message']}}
													@else
														<strong>{{$item['message']}}</strong>
													@endif
												</a></td>
											</tr>
											<?php $no++ ?>
											@endforeach
											<?php }else{?>
											<div class="table-responsive font-table">
												<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
													<thead>
														<tr align="center">
															<th colspan="3" style="text-align: center;">{{ trans('translate.data_not_found') }}</th>
														</tr>
													</thead>
												</table>
											</div>
											<?php }?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>



				</div>

			</div>

		</section><!-- #content end -->
		

@endsection
 