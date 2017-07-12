@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Profile User</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>User</span>
					</li>
					<li class="active">
						<span>Profile</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/user/profile/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Profile User
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama" value="{{ $data->name }}" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Perusahaan *
									</label>
									<select name="company_id" class="cs-select cs-skin-elastic" required>
										@if ($company)
											@foreach($company as $item)
												@if($item->id == $data->company->id)
													<option value="{{$item->id}}" selected disabled>{{$item->name}}</option>
												@else
													<option value="{{$item->id}}">{{$item->name}}</option>
												@endif
											@endforeach									
										@else
											<option value="" disabled selected>Select...</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Email *
									</label>
									<input type="text" name="email" class="form-control" placeholder="Email" value="{{ $data->email }}" disabled>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Password Lama
									</label>
									<input type="password" name="old_password" class="form-control" placeholder="Old Password">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Password Baru
									</label>
									<input type="password" name="new_password" class="form-control" placeholder="New Password">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Konfirmasi Password Baru
									</label>
									<input type="password" name="confirm_new_password" class="form-control" placeholder="Confirm Password">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Gambar
									</label>
									<img src="{{asset('media/user/'.$data->id.'/'.$data->picture)}}" width="240px">
									<input type="file" name="picture" class="form-control">
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="Update" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                                <a style=" color:white !important;" href="{{URL::to('/admin')}}">
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