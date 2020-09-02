<div class="row">
	<div class="col-md-12">
		<div class="table-responsive font-table">
			<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
				<caption></caption>
				<thead>
					<tr>
						<th class="center" scope="col">{{ trans('translate.charge_no') }}</th>
						<th class="center" scope="col">{{ trans('translate.charge_stel') }}</th>
						<th class="center" scope="col">{{ trans('translate.charge_name') }}</th>
						<th class="center" scope="col">{{ trans('translate.charge_category') }}</th>
						<th class="center" scope="col">{{ trans('translate.charge_duration') }}</th>
						<th class="center" scope="col">{{ trans('translate.charge_cost') }}</th>
					</tr>
				</thead>
				<tbody>
					@php $no=1; @endphp

					@if (count($data)>0)
						@foreach($data as $item)
						<tr>
							<td class="center">@php echo $no; @endphp</td>
							<td class="center">{{ $item['stel'] }}</td>
							<td class="center">{{ $item['device_name'] }}</td>
							<td class="center">{{ $item['category'] }}</td>
							<td class="center">{{ $item['duration'] }}</td>
							<td class="center">{{ $item['price'] }}</td>
						</tr>
						@php $no++ @endphp
						@endforeach
					@else
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
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>