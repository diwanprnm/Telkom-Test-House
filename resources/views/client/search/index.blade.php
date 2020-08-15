@extends('layouts.client')

@section('content') 
		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap"> 
				<div class="container clearfix">

				 	<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-white" id="panel1">
									<div class="panel-body">
										<div class="col-md-12">
										<!-- start: WIZARD FORM -->
											<div id="wizard" class="swMain">
												<div class="form-group">
													<table class="table table-condensed">
														<caption></caption>
														<thead>
															<tr>
																<th colspan="3" scope="colgroup">{{ trans('translate.search_result') }}</th>
															</tr>
														</thead>
														<tbody>
															@php $no=1; if(count($data)>0){ @endphp
															@foreach($data as $item?
																<tr>
																	<td>@php echo $no.'. '; @endphp {{ trans('translate.search_result_title') }} : </td>
																	<td>@php echo $item->title @endphp</td>
																</tr>
																<tr>
																	<td>{{ trans('translate.search_result_desc') }} : </td>
																	<td>@php echo $item->description @endphp</td>
																</tr>
																@php if($item->jns == 1){ @endphp
																	<tr><td> 
																		</td></tr>
																@php }else if($item->jns == 2){ @endphp
																	<tr><td> 
																		</td></tr>
																@php }else if($item->jns == 4){ @endphp
																	<tr><td>
																			<a class="btn btn-wide btn-danger" href="{{URL::to('pengujian/'.$item->id.'/detail')}}">{{ trans('translate.search_result_exam_detail') }}</a>
																		</td></tr>
																@php } $no++; @endphp
															@endforeach
																@php }else{@endphp
															<div class="table-responsive font-table">
																<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
																	<caption></caption>
																	<thead>
																		<tr class="center">
																			<th colspan="3" style="text-align: center;" scope="colgroup">{{ trans('translate.data_not_found') }}</th>
																		</tr>
																	</thead>
																</table>
															</div>
															@php }@endphp
													</table>
												</div>
											</div>
										</div>
										<!-- end: WIZARD FORM -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
 
				</div>

			</div>

		</section><!-- #content end -->
		

@endsection
 
