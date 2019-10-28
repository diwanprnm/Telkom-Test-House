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
						<span>User Internal</span>
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
				{!! Form::open(array('url' => 'admin/userin', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
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
	                        <div class="col-md-7">
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
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Akses Menu
									</label>
									<div class="form-group"> 
										<?php
										$html = '<ul id="tree">';
								        foreach ($tree as $key => $value) {
								            if(isset($value[0]['children'])) {
								                $html .= '<li><label> <input type="checkbox" class="chk" name="menus[]" checked value="'.$value[0]['id'].'" /> '.$value[0]['name'].'</label>';
								                $html .= '<ul>';
								               
								                foreach ($value[0]['children'] as $child) { 
								                   $html .= '<li><label> <input type="checkbox" class="chk" name="menus[]" checked value="'.$child['id'].'" /> '.$child['name'].'</label></li>'; 
								                }
								                $html .= '</ul>';
								            }else{
								              $html .= '<li><label> <input type="checkbox" class="chk" name="menus[]" checked value="'.$value[0]['id'].'" /> '.$value[0]['name'].'</label></li>';
								            }
								        }
								        $html .= '</ul></li>';

								        echo $html;
									 ?>
									</div>
									
								</div>
							</div>
							<div class="col-md-6 tree_examination">
								<div class="form-group">
									<label">
										Akses Pengujian
									</label>
									<div class="form-group"> 
										<input type="hidden" id="hide_admin_role" name="hide_admin_role">
										<ul id="tree_examination">
											<li>
												<label> <input type="checkbox">Registrasi</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="registration_status">Registrasi</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="function_status">Uji Fungsi</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="contract_status">Tinjauan Kontrak</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox">Keuangan</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="spb_status">SPB</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="payment_status">Pembayaran</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox">Pengujian</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="spk_status">Pembuatan SPK</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="examination_status">Pelaksanaan Uji</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox">Laporan & Sertifikat</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="resume_status">Laporan Uji</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="qa_status">Sidang QA</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="certificate_status">Penerbitan Sertifikat</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox">Lainnya</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="equipment_status">Edit Lokasi Barang</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="receipt_status">Tanda Terima Hasil Pengujian</label></li>
												</ul>
											</li>
										</ul>
									</div>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                                <a style=" color:white !important;" href="{{URL::to('/admin/userin')}}">
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
	// $('#company_id').val(0);
	$('#company_id').trigger("chosen:updated");
	jQuery(document).ready(function() {
		$('#hide_admin_role').val(0);
		$('.tree_examination').hide();
		$("input[type=checkbox]").each(function(){
			checkBox = this.labels[0].textContent;
			if(checkBox.trim() == "Pengujian"){
				if(this.checked) {
					$('#hide_admin_role').val(1);
					$('.tree_examination').show();
			    }
			}
		});

		FormElements.init();
		$('#tree').checktree();
		$('#tree_examination').checktree();
		$(".chk").change(function() {
			checkBox = this.labels[0].textContent;
			if(checkBox.trim() == "Pengujian"){
				if(this.checked) {
					$('#hide_admin_role').val(1);
					$('.tree_examination').show();
			    }else{
			    	$('#hide_admin_role').val(0);
			    	$('.tree_examination').hide();
			    }
			}
		});
	});
</script>
@endsection