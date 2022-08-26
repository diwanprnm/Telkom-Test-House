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
						<span>User Internal</span>
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
				{!! Form::open(array('url' => 'admin/userin/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
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
									<img src="{{ \Storage::disk('minio')->url('user/'.$data->id.'/'.$data->picture) }}" width="240px" alt="">
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
	                        <div class="col-md-7">
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
							<div class="col-md-6">
								<div class="form-group">
									<label">
										Akses Menu
									</label>
									<div class="form-group"> 
										@php
										$CHECKED_STRING = 'checked';
										function in_multiarray($elem, $array,$field)
										{
										    $top = sizeof($array) - 1;
										    $bottom = 0;
										    while($bottom <= $top)
										    {
										        if($array[$bottom][$field] == $elem || (
										        	is_array($array[$bottom][$field]) && 
										        	in_multiarray($elem, ($array[$bottom][$field]))
										        	)
										    	)
												{ 
														return true;
													 
												}
										        $bottom++;
										    }        
										    return false;
										}
									 
										$html = '<ul id="tree">';
								        foreach ($tree as $key => $value) {
								            if(isset($value[0]['children'])) {
		 							$is_active = (in_multiarray($value[0]['id'], $menu_user,'id'))?$CHECKED_STRING:'';
								                $html .= '<li><label> <input type="checkbox" class="chk" id="'.$value[0]['url'].'" name="menus[]" '.$is_active.' value="'.$value[0]['id'].'" /> '.$value[0]['name'].'</label>';
								                $html .= '<ul>';
								               
								                foreach ($value[0]['children'] as $child) { 
									$is_active = (in_multiarray($child['id'], $menu_user,'id'))?$CHECKED_STRING:'';
								                   	$html .= '<li><label> <input type="checkbox" class="chk" id="'.$child['url'].'" name="menus[]" '.$is_active.'  value="'.$child['id'].'" /> '.$child['name'].'</label></li>'; 
								                }
								                $html .= '</ul>';
								            }else{
								 			$is_active = (in_multiarray($value[0]['id'], $menu_user,'id'))?$CHECKED_STRING:'';
								              $html .= '<li>
								              				<label> 
								              				<input type="checkbox" 
								              						class="chk"
								              						id="'.$value[0]['url'].'"
								              						name="menus[]" '.$is_active.'  
								              						value="'.$value[0]['id'].'" /> 
								              				'.$value[0]['name'].'</label>
								              			</li>';
								            }
								        }
								        $html .= '</ul></li>';

								        echo $html;
									 @endphp
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
												<label> <input type="checkbox" @php if(isset($admin_role[0])){echo $admin_role[0]['registration_status'] || $admin_role[0]['function_status'] || $admin_role[0]['contract_status']? $CHECKED_STRING:'';}@endphp>Registrasi</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="registration_status" @php if(isset($admin_role[0])){echo $admin_role[0]['registration_status'] ?$CHECKED_STRING:'';}@endphp>Registrasi</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="function_status" @php if(isset($admin_role[0])){echo $admin_role[0]['function_status'] ?$CHECKED_STRING:'';}@endphp>Uji Fungsi</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="contract_status" @php if(isset($admin_role[0])){echo $admin_role[0]['contract_status'] ?$CHECKED_STRING:'';}@endphp>Tinjauan Kontrak</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox" @php if(isset($admin_role[0])){echo $admin_role[0]['spb_status'] || $admin_role[0]['payment_status']? $CHECKED_STRING:'';}@endphp>Keuangan</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="spb_status" @php if(isset($admin_role[0])){echo $admin_role[0]['spb_status'] ?$CHECKED_STRING:'';}@endphp>SPB</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="payment_status" @php if(isset($admin_role[0])){echo $admin_role[0]['payment_status'] ?$CHECKED_STRING:'';}@endphp>Pembayaran</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox" @php if(isset($admin_role[0])){echo $admin_role[0]['spk_status'] || $admin_role[0]['examination_status']? $CHECKED_STRING:'';}@endphp>Pengujian</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="spk_status" @php if(isset($admin_role[0])){echo $admin_role[0]['spk_status'] ?$CHECKED_STRING:'';}@endphp>Pembuatan SPK</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="examination_status" @php if(isset($admin_role[0])){echo $admin_role[0]['examination_status'] ?$CHECKED_STRING:'';}@endphp>Pelaksanaan Uji</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox" @php if(isset($admin_role[0])){echo $admin_role[0]['resume_status'] || $admin_role[0]['qa_status'] || $admin_role[0]['certificate_status']? $CHECKED_STRING:'';}@endphp>Laporan & Sertifikat</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="resume_status" @php if(isset($admin_role[0])){echo $admin_role[0]['resume_status'] ?$CHECKED_STRING:'';}@endphp>Laporan Uji</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="qa_status" @php if(isset($admin_role[0])){echo $admin_role[0]['qa_status'] ?$CHECKED_STRING:'';}@endphp>Sidang QA</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="certificate_status" @php if(isset($admin_role[0])){echo $admin_role[0]['certificate_status'] ?$CHECKED_STRING:'';}@endphp>Penerbitan Sertifikat</label></li>
												</ul>
											</li>
											<li>
												<label> <input type="checkbox" @php if(isset($admin_role[0])){echo $admin_role[0]['equipment_status'] || $admin_role[0]['receipt_status']? $CHECKED_STRING:'';}@endphp>Lainnya</label>
												<ul>
													<li><label> <input type="checkbox" name="examinations[]" value="tm_status" @php if(isset($admin_role[0])){echo $admin_role[0]['tm_status'] ?$CHECKED_STRING:'';}@endphp>Technical Meeting Pra Uji Fungsi</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="equipment_status" @php if(isset($admin_role[0])){echo $admin_role[0]['equipment_status'] ?$CHECKED_STRING:'';}@endphp>Edit Lokasi Barang</label></li>
													<li><label> <input type="checkbox" name="examinations[]" value="receipt_status" @php if(isset($admin_role[0])){echo $admin_role[0]['receipt_status'] ?$CHECKED_STRING:'';}@endphp>Tanda Terima Hasil Pengujian</label></li>
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
	
	$('#company_id').chosen();
	$('#company_id').trigger("chosen:updated");
</script>
@endsection