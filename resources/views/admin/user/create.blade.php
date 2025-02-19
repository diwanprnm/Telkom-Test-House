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
</style>>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah User Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>User Eksternal</span>
					</li>
					<li class="active">
						<span>Tambah</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/usereks', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Tambah User Baru
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Perusahaan *
									</label>
									<select class="form-control" id="company_id" name="company_id" required>
										@foreach($company as $item)
											<option value="{{$item->id}}">{{$item->name}}</option>
										@endforeach
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
											<option value="{{$item->id}}">{{$item->name}}</option>
										@endforeach									
									</select>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Email *
									</label>
									<input type="text" name="email" class="form-control" placeholder="Email" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Password *
									</label>
									<input type="password" name="password" class="form-control" placeholder="Password" required>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Gambar
									</label>
									<input type="file" name="picture" class="form-control">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Alamat *
									</label>
									<textarea type="text" name="address" class="form-control" placeholder="Alamat" required></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor Telepon *
									</label>
									<input type="text" name="phone_number" class="form-control" placeholder="Nomor Telepon" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Fax
									</label>
									<input type="text" name="fax" class="form-control" placeholder="Fax">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label for="form-field-select-2">
										Status *
									</label>
									<select name="is_active" class="cs-select cs-skin-elastic" required>
										<option value="" disabled selected>Select...</option>
										<option value="1">Active</option>
										<option value="0">Not Active</option>
										
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
<script src="{{ asset("assets/js/jquery-checktree.js") }}"></script>
 
<script type="text/javascript">
	$('#company_id').chosen();
	// $('#company_id').val(0);
	$('#company_id').trigger("chosen:updated");
</script>
@endsection