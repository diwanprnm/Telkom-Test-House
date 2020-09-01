@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit Lab Pengujian</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Lab Pengujian</span>
					</li>
					<li class="active">
						<span>Edit</span>
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
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/labs/'.$data->id, 'method' => 'PUT')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit Lab Pengujian
						</legend>
						<div class="row">
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Nama *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Lab" value="{{$data->name}}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Kode *
									</label>
									<input type="text" name="lab_code" class="form-control" placeholder="Kode Lab" value="{{$data->lab_code}}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Inisial *
									</label>
									<input type="text" name="lab_init" class="form-control" placeholder="Inisial Lab" value="{{$data->lab_init}}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Deskripsi *
									</label>
									<textarea type="text" name="description" class="form-control" placeholder="Deskripsi Lab" required>{{ $data->description }}</textarea>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
										@if($data->is_active)
											<option value="1" selected>Active</option>
											<option value="0">Not Active</option>
										@else
											<option value="1">Active</option>
											<option value="0" selected>Not Active</option>
										@endif
									</select>
								</div>
							</div>
							<div id="is_not_active_date">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tutup Sampai *
										</label>
										<p id="closeUntil" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
											<input type="text" name="close_until" id="close_until" class="form-control" value="{{ $data->close_until }}" required/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Dibuka Kembali *
										</label>
										<p id="openAt" class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd" />
											<input type="text" name="open_at" id="open_at" class="form-control" value="{{ $data->open_at }}" required/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                            <a style=" color:white !important;" href="{{URL::to('/admin/labs')}}">
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
	if("{{ $data->is_active }}" == 1){
		$("#is_not_active_date").hide();
	}else{
		$("#is_not_active_date").show();
	}
	jQuery(document).ready(function() {
		FormElements.init();

		SelectFx.prototype.options = {
			onChange: function (val) { 
				if(val == 1){
	        		$("#is_not_active_date").hide();
				}else{
					$("#is_not_active_date").show();
				}
			}
		};
	});
</script>
@endsection