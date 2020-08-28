@extends('layouts.app')

@section('content')
<style type="text/css">
	ul.checktree-root, ul#tree ul {
		list-style: none;
		}
		ul.checktree-root label {
		font-weight: normal;
		position: relative;
		}
		ul.checktree-root label input {
		position: relative;
		top: 2px;
		left: -5px;
		}
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Edit User</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>User Eksternal</span>
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
				{!! Form::open(array('url' => 'admin/usereks/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Edit User
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
									<select class="form-control" id="company_id" name="company_id" required>
										@if (!empty($company))
											@foreach($company as $item)
												@if(!empty($data->company) && $item->id == $data->company->id)
													<option value="{{$item->id}}" selected>{{$item->name}}</option>
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
										Role *
									</label>
									<select name="role_id" class="cs-select cs-skin-elastic" required>
										@foreach($role as $item)
											@if($item->id == $data->role->id)
												<option value="{{$item->id}}" selected>{{$item->name}}</option>
											@else
												<option value="{{$item->id}}">{{$item->name}}</option>
											@endif
										@endforeach									
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
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Password
									</label>
									<input type="password" name="password" class="form-control" placeholder="Password">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Gambar
									</label>
									<img src="{{asset('media/user/'.$data->id.'/'.$data->picture)}}" width="240px" alt="">
									<input type="file" name="picture" class="form-control">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Alamat *
									</label>
									<textarea type="text" name="address" class="form-control" placeholder="Alamat" required>{{ $data->address }}</textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor Telepon *
									</label>
									<input type="text" name="phone_number" class="form-control" placeholder="Nomor Telepon" value="{{ $data->phone_number }}" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Fax
									</label>
									<input type="text" name="fax" class="form-control" placeholder="Fax" value="{{ $data->fax }}">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic">
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
							
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                                <a style=" color:white !important;" href="{{URL::to('/admin/usereks')}}">
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
<script src={{ asset("assets/js/jquery-checktree.js") }}></script>
<script type="text/javascript">
	$('#company_id').chosen();
	$('#company_id').trigger("chosen:updated");
</script>
@endsection