@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Perangkat Lulus Uji</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Perangkat Lulus Uji</span>
					</li>
					<li class="active">
						<span>Edit</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/device/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Perangkat Lulus Uji
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nama Perangkat *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Perangkat" value="{{$data->name}}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Nama Brand *
									</label>
									<input type="text" name="mark" class="form-control" placeholder="Nama Brand" value="{{$data->mark}}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Model/Type *
									</label>
									<input type="text" name="model" class="form-control" placeholder="Model/Type" value="{{$data->model}}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Kapasitas *
									</label>
									<input type="text" name="capacity" class="form-control" placeholder="Kapasitas" value="{{$data->capacity}}" required>
								</div>
							</div>
	                        <div class="col-md-4">
								<div class="form-group">
									<label>
										Referensi Uji *
									</label>
									<input type="text" id="txt-total" name="test_reference" class="form-control" placeholder="referensi Uji" value="{{$data->test_reference}}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Mulai Berlaku *
									</label>
									<p id="validFrom" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
										<input type="text" name="valid_from" id="valid_from" class="form-control" value="{{ $data->valid_from }}"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Akhir Berlaku *
									</label>
									<p id="validFrom" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
										<input type="text" name="valid_thru" id="valid_thru" class="form-control" value="{{ $data->valid_thru }}"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
	                        
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                            <a style=" color:white !important;" href="{{URL::to('/admin/device')}}">
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
	jQuery(document).ready(function() {
		FormElements.init();
	});
</script>
@endsection