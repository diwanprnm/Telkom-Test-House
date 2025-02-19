<div class="row">
	<div class="col-md-12">
		<div class="table-responsive font-table">
			<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
				<caption></caption>
				<thead>
					<tr>
						<th class="center" scope="col">{{ trans('translate.stel_no') }}</th>
						<th class="center" scope="col">{{ trans('translate.stel_name') }}</th>
						<th class="center" scope="col">{{ trans('translate.stel_code') }}</th>
						<th class="center" scope="col">{{ trans('translate.stel_price') }}</th>
						<th class="center" scope="col">{{ trans('translate.stel_version') }}</th>
						<th class="center" scope="col">{{ trans('translate.stel_category') }}</th>
					</tr>
				</thead>
				<tbody>
					@php 
						$no=1; if(count($data)>0){
					@endphp
					@foreach($data as $item)
					<tr>
						<td class="center">{{ $no }}</td>
						<td class="center">{{ $item['name'] }}</td>
						<td class="center">{{ $item['code'] }}</td>
						<td class="center">{{ $item['price'] }}</td>
						<td class="center">{{ $item['version'] }}</td>
						<td class="center">{{ $item['type'] }}</td>
					</tr>
					@php $no++ @endphp
					@endforeach
					@php }else{@endphp
					<div class="table-responsive font-table">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
							<caption></caption>
							<thead>
								<tr class="center">
									<th colspan="3" style="text-align: center;" scope="col">{{ trans('translate.data_not_found') }}</th>
								</tr>
							</thead>
						</table>
					</div>
					@php }@endphp
				</tbody>
			</table>
		</div>
	</div>
</div>