<div class="row">
	<div class="col-md-12">
		<div class="panel panel-white" id="panel1">
			<div class="panel-body">
				<div class="col-md-12">
				<!-- start: WIZARD FORM -->
				<?php $no=1;?>
				<form action="#" role="form" class="smart-wizard" id="form">
					{!! csrf_field() !!}
					<div id="wizard" class="swMain">
					<?php $SELECTED_DONE_STRING = "selected done"; $DISABLED_STRING = "disabled";
					if(count($data)>0){?>
					@foreach($data as $item)
						<!-- start: WIZARD SEPS -->
						<ul>
							<li>
								<a class="<?php if($item->registration_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->registration_status }}" rel="1" href="#step-1">
									<div class="stepNumber">
										1
									</div>
									<span class="stepDesc"><small> {{ trans('translate.examination_reg') }} </small></span>
								</a>
							</li>
							<li>
								<a class="<?php if($item->spb_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->spb_status }}" rel="2" href="#step-2">
									<div class="stepNumber">
										2
									</div>
									<span class="stepDesc"> <small> {{ trans('translate.examination_spb') }} </small></span>
								</a>
							</li>
							<li>
								<a class="<?php if($item->payment_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->payment_status }}" rel="3" href="#step-3">
									<div class="stepNumber">
										3
									</div>
									<span class="stepDesc"> <small> {{ trans('translate.examination_payment') }} </small> </span>
								</a>
							</li>
							<li>
								<a class="<?php if($item->spk_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->spk_status }}" rel="4" href="#step-4">
									<div class="stepNumber">
										4
									</div>
									<span class="stepDesc"> <small> {{ trans('translate.examination_spk') }} </small> </span>
								</a>
							</li>
							<li>
								<a class="<?php if($item->examination_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->examination_status }}" rel="5" href="#step-5">
									<div class="stepNumber">
										5
									</div>
									<span class="stepDesc"> <small> {{ trans('translate.examination_exam') }} </small> </span>
								</a>
							</li>
							<li>
								<a class="<?php if($item->resume_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->resume_status }}" rel="6" href="#step-6">
									<div class="stepNumber">
										6
									</div>
									<span class="stepDesc"> <small> {{ trans('translate.examination_report') }} </small> </span>
								</a>
							</li>
							<li>
								<a class="<?php if($item->qa_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->qa_status }}" rel="7" href="#step-7">
									<div class="stepNumber">
										7
									</div>
									<span class="stepDesc"> <small> {{ trans('translate.examination_qa') }} </small> </span>
								</a>
							</li>
							<li>
								<a class="<?php if($item->certificate_status > 0){echo $SELECTED_DONE_STRING;} else{echo $DISABLED_STRING;} ?>" isdone="{{ $item->certificate_status }}" rel="8" href="#step-8">
									<div class="stepNumber">
										8
									</div>
									<span class="stepDesc"> <small> {{ trans('translate.examination_certificate') }} </small> </span>
								</a>
							</li>
						</ul>
						<div id="step-1">

						<div class="form-group">
							<table class="table table-condensed">
								<caption></caption>
								<thead>
									<tr>
										<th colspan="3" scope="colgroup">{{ trans('translate.examination_status') }}</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>{{ trans('translate.examination_no') }} : </td>
										<td>{{ $no }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_equipment') }} : </td>
										<td>{{ $item->nama_perangkat }}</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class=" pull-right col-xs-12">
								<a class="btn btn-wide btn-primary btn-margin pull-right col-xs-12 col-lg-1" style="margin-bottom:10px;" href="{{URL::to('cetakPengujian/'.$item->id.'')}}" target="_blank">{{ trans('translate.examination_print') }}</a>
								
								<?php if($item->spb_status == 1 && $item->payment_status == 0){ ?>
									<a class="btn btn-wide btn-primary btn-margin pull-right col-xs-12 col-lg-1 " href="{{URL::to('pengujian/'.$item->id.'/pembayaran')}}">{{ trans('translate.examination_payment') }}</a>
								<?php } ?>
								
								<a class="btn btn-wide btn-primary btn-margin pull-right col-xs-12 col-lg-1 " href="{{URL::to('pengujian/'.$item->id.'/detail')}}">{{ trans('translate.examination_detail') }} </a>
								
								<?php if($item->registration_status == 0){ ?>
									<a class="btn btn-wide btn-primary btn-margin pull-right col-xs-12 col-lg-1 " href="#" onclick="return edit('<?php echo $item->id ?>',<?php echo $item->registration_status ?>);">{{ trans('translate.examination_edit') }}</a>
								<?php } ?>
							</div>
						</div>										
					</div>
					<?php 
					$no++;
					?>
					@endforeach
					<?php }else{?>
						<div class="form-group">
							<table class="table table-condensed">
								<caption></caption>
								<thead>
									<tr class="center">
										<th colspan="3" style="text-align: center;" scope="colgroup">{{ trans('translate.data_not_found') }}</th>
									</tr>
								</thead>
							</table>
						</div>
					<?php }?>
					</div>
				</form>
				<!-- end: WIZARD FORM -->
				</div>
			</div>
		</div>
	</div>
</div>