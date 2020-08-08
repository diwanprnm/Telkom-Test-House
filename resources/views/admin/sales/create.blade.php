@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Data Pembelian STEL</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Keuangan</span>
					</li>
					<li>
						<span>Rekap Pembelian STEL</span>
					</li>
					<li class="active">
						<span>Tambah</span>
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
				{!! Form::open(array('url' => 'admin/sales', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Tambah
						</legend>
						<div class="row">
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Kastamer / Perusahaan *
									</label>
									<select class="form-control" id="user_id" name="user_id" required>
										<option value="" disabled selected>Select User...</option>
										@foreach($users as $item)
											<option value="{{$item->id}}">{{ $item->name }} - {{ $item->company->name }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group">
									<label>
										Dokumen STEL *
									</label>
									<select multiple class="form-control" id="stels" name="stels[]" required>
										<option value="" disabled selected>Select Document...</option>
										@foreach($stels as $item)
											<option value="{{$item->id}}-myToken-{{$item->price}}">{{ $item->name }} - {{ $item->code }} ({{ $item->price }})</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label>
										<input type="checkbox" id="is_tax" name="is_tax">
										include tax 10% ppn
									</label>
								</div> 
							</div>
	                        
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
                                <a style=" color:white !important;" href="{{URL::to('/admin/sales')}}">
                            		<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
                                		Cancel
                            		</button>
                                </a>
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
<script src={{ asset("assets/js/form-elements.js") }}></script>
 
<script type="text/javascript">
	$('#user_id').chosen();
	$('#user_id').trigger("chosen:updated");
	$('#stels').chosen();
	$('#stels').trigger("chosen:updated");
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
@endsection