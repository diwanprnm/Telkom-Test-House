@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Perusahaan</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Perusahaan</span>
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
				{!! Form::open(array('url' => 'admin/company', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Tambah Perusahaan Baru
						</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nama Perusahaan *
									</label>
									<input type="text" name="name" class="form-control" placeholder="Nama Perusahaan" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Alamat Perusahaan *
									</label>
									<textarea type="text" name="address" class="form-control" placeholder="Alamat Perusahaan" required></textarea>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										PLG_ID *
									</label>
									<input type="text" name="plg_id" class="form-control" placeholder="PLG_ID" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										NIB *
									</label>
									<input type="text" name="nib" class="form-control" placeholder="NIB" required>
								</div>
							</div>
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Email *
									</label>
									<input type="email" name="email" class="form-control" placeholder="Email" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kota *
									</label>
									<input type="text" name="city" class="form-control" placeholder="Kota" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kode Pos
									</label>
									<input type="text" name="postal_code" class="form-control" placeholder="Kode Pos">
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
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Nomor NPWP
									</label>
									<input type="text" name="npwp_number" class="form-control" placeholder="NPWP">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File NPWP
									</label>
									<input type="file" name="npwp_file" class="form-control" accept="application/pdf, image/*">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor SIUP
									</label>
									<input type="text" name="siup_number" class="form-control" placeholder="SIUP">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Masa Berlaku SIUP
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="siup_date" class="form-control">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File SIUP
									</label>
									<input type="file" id="siup_file" name="siup_file" accept="application/pdf, image/*">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nomor Sertifikat Manajemen Mutu
									</label>
									<input type="text" name="qs_certificate_number" class="form-control" placeholder="Nomor Sertifikat Manajemen Mutu">
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Masa Berlaku Sertifikat Manajemen Mutu
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="qs_certificate_date" class="form-control">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<em class="glyphicon glyphicon-calendar"></em>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										File Sertifikat Manajemen Mutu
									</label>
									<input type="file" name="qs_certificate_file" class="form-control" accept="application/pdf, image/*">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Keterangan
									</label>
									<textarea type="text" name="keterangan" class="form-control" placeholder="Keterangan terkait perusahaan ..."></textarea>
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
	                            <a style=" color:white !important;" href="{{URL::to('/admin/company')}}">
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