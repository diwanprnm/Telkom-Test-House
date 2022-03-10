@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Update Dokumen STEL</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Sales</span>
					</li>
					<li class="active">
						<span>Upload Dokumen STEL</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->

		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		@if (Session::get('error'))
			<div class="alert alert-error alert-danger">
				{{ Session::get('error') }}
			</div>
		@endif
		
		@if (Session::get('message'))
			<div class="alert alert-info">
				{{ Session::get('message') }}
			</div>
		@endif

		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/sales/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
					<fieldset>
						<legend>
							Upload Dokumen STEL
						</legend>
						<div class="row"> 
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
										<caption>Uploaded File Table</caption>
										<thead>
											<tr>
												<th class="center" scope="col">No</th> 
												<th class="center" scope="col">Document Name</th> 
												<th class="center" scope="col">Document Code</th> 
												<th class="center" scope="col">Attachment</th> 
												<th class="center" scope="col">Upload File</th> 
											</tr>
										</thead>
										<tbody>
											@foreach($dataStel as $keys => $item)
												<tr>
													<td class="center">{{++$keys}}</td> 
													<td class="center">{{ $item->name }}</td>
													<td class="center">{{ $item->code }}</td>
													<td class="center"><a href="{{ URL::to('/admin/downloadstelwatermark/'.$item->id) }}" target="_blank">{{ $item->attachment }}</a></td>
													<td class="center">
														@if($item->stelAttach !='')
															<a href="{!! url("cetakstel?invoice_id={$item->invoice}&attach={$item->stelAttach}&company_name={$item->company_name}") !!}" target="_blank"> Generate Watermark</a>
														@endif
														<input type="file" name="stel_file[]" class="form-control" accept="application/pdf" style="width: auto;" <?php if(!$item->attachment){echo "required";}?>>
														<input type="hidden" name="stels_sales_detail_id[]" value="{{ $item->id }}">
														<input type="hidden" name="stels_sales_attachment[]" value="{{ $item->attachment }}">
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
									<div>
										<a style=" color:white !important;" href="{{URL::to('/admin/sales')}}">
											<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-right">
											Cancel
											</button>
										</a>
										<input type="hidden" name="payment_status" value="{{ $dataStel[0]->payment_status }}"/>
										<button class="btn btn-wide btn-green btn-squared pull-right">
											Upload
										</button>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				{!! Form::close() !!}
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
	</div>
</div>
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
@endsection