@extends('layouts.app')

@section('content')


<div class="modal" id="modal_resend" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em>Resend OTP</h4>
			</div>
			<div class="modal-body">
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em>Mohon Masukkan email Anda!</h4>
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<div class="form-group">
								<label for="email">Email:</label>
								<input id="email" type="email" name="email" class="form-control" placeholder="Email anda">
								<span class="email-message" style="color: red;"></span>
							</div>
						</th>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="submit" id="btn-modal-resend" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o" data-toggle="modal" data-dismiss="modal"></em> Kirim OTP</button>
							{{ csrf_field() }}
						</th>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="modal" id="modal_kirim_otp" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em>Verifikasi Akun</h4>
			</div>
			<div class="modal-body">
				<p>Sebelum melakukan approve akan dilakukan verfikasi akun terlebih dahulu kode OTP akan dikirim ke email akun anda. Kode OTP hanya berlaku selama 5 menit.</p>
				{{-- <div id="emailform">
					<meta name="_token" content="{{ csrf_token }}"/>
					{{ form::open(array) }}
				</div> --}}
			</div>
			<div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="submit" id="btn-modal-send" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o" data-toggle="modal" data-dismiss="modal" data-target="#myModal_assign"></em> Kirim OTP</button>
							{{-- <input type = "submit" class = "btn btn-primary m-d do-ajax" value = "AJAX" /> --}}
							{{ csrf_field() }}
						</th>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<input type="hide" id="hide_approval_id" name="hide_approval_id">

<div class="modal" id="myModal_assign" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><em class="fa fa-eyes-open"></em>Mohon Masukkan kode OTP yang telah dikirimkan ke email Anda!</h4>
			</div>

			
			<div class="modal-body">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<div class="form-group">
								<label for="otp">Kode OTP:</label>
								<input id="otp" type="text" name="otp" class="form-control" placeholder="OTP">
								<span class="otp-message" style="color: red;"></span>
								<br>
								<p>Belum menerima email? <a data-toggle="modal" data-dismiss="modal" data-target="#modal_resend">Resend OTP</a></p>
							</div>
						</th>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table style="width: 100%;"><caption></caption>
					<tr>
						<th scope="col">
							<button type="button" id="btn-modal-assign" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o" ></em> Submit</button>
							{{ csrf_field() }}
						</th>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>



<style type="text/css">
	.chosen-container.chosen-container-single {
		width: 100% !important;
	}   
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Approval</h1>
				</div>
				<ol class="breadcrumb">
					<li class="active">
						<span>Approval</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
	        <div class="row">
	        	<div class="col-md-6 pull-right">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="search_value" type="text" placeholder="Search" id="form-field-17" class="form-control " value="{{ $search }}">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	        </div>

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

			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer">
							<caption>Approval Table</caption>
							<thead>
								<tr>
									<th class="center" scope="col">No</th>
									<th class="center" scope="col">Jenis Dokumen</th>
                                    <th class="center" scope="col">Attachment</th>
                                    <th class="center" scope="col">Dibuat pada</th>
									<th class="center" scope="col">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@php
									$no=1;
								@endphp
								@if(count($data)>0)
									@foreach($data as $item)
										<tr>
											<td class="center">{{$no}}</td>
											<td class="center">{{ $item->approval->authentikasi->name }}</td>
											<td class="center"><a href="{{ \Storage::disk('minio')->url($item->approval->reference_table.'/'.$item->approval->reference_id.'/'.$item->approval->attachment) }}" target="_blank">{{ $item->approval->attachment }}</a></td>
											<td class="center">{{ $item->created_at }}</td>
											<td class="center">
											@if($item->approval->status)
												<a href="{{URL::to('admin/approval/'.$item->approval->id)}}" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Detail" target="_blank"><em class="fa fa-eye"></em></a>
											@else
												@if($item->approve_date)
													Approved
												@else
													<a data-toggle="modal" data-target="#modal_kirim_otp" onclick="document.getElementById('hide_approval_id').value = '{{ $item->id }}'" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit" ><em class="fa fa-pencil-square-o"></em></a>
												@endif
											@endif
											</td>
										</tr>
									@php
										$no++
									@endphp
									@endforeach
								@else
									<tr>
										<td colspan=5 class="center">
											Data Not Found
										</td>
									</tr>
								@endif
                            </tbody>
						</table>
					</div>
					<div class="row">
	                    <div class="col-md-12 col-sm-12">
	                        <div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
	                             {{$data->appends(array('search' => $search))->links()}}
	                        </div>
	                    </div>
	                </div>
				</div>
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
		$('#search_value').keydown(function(event) {
	        if (event.keyCode == 13) {
	            var baseUrl = "{{URL::to('/')}}";
				var params = {
					search:document.getElementById("search_value").value,
				};
				document.location.href = baseUrl+'/admin/approval?'+jQuery.param(params);
	        }
	    });

		$('#myModal_assign').on('shown.bs.modal', function () {
		    $('#otp').focus();
		});

		$('#otp').keydown(function(event) {
	        if (event.keyCode == 13) {
	            verifyAccount();
	        }
	    });

		// $("#btn-modal-assign").on("click",function(e) {
		// 	e.preventDefault();
		// 	var otp = $("otp").val();
		// 	$.ajax({
		// 		url:'approval/verifyOtp',
		// 		headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
		// 		type:'POST',
		// 		datatype: 'JSON',
		// 		cache:false,
		// 		data:{otp:otp},
		// 		success:function(resp){
		// 			console.log('haloo');
		// 			if(resp["success"] == "yes"){
		// 				verifyAccount();
		// 			}
		// 			if(resp["success"] == "no"){
		// 				$(".otp-message").html("Please enter valid OTP");
		// 			}
		// 		}
		// 	});
		// 	// verifyAccount();
		// });

		
			
	});


	$('body').on('click', '#btn-modal-send', function () {
		$.ajax({
			url: 'approval/verification',
			headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
			beforeSend: function(){
				$('#modal_kirim_otp').modal('hide');
				document.getElementById("overlay").style.display="inherit";
			},
			type: 'POST',
			datatype: 'JSON',
			success: function (resp) {	
				$('#myModal_assign').modal('show');
				alert(resp["success"]);
				document.getElementById("overlay").style.display="none";
			}
		});
	});

	$('body').on('click', '#btn-modal-assign', function(){
		var otp = document.getElementById('otp').value;
		var expires_time_otp = expire_time_otp;
		$.ajax({
			url: 'approval/verifyOtp',
			headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
			data: {otp:otp,expires_time_otp:expires_time_otp},
			beforeSend: function(){
				$('#myModal_assign').modal('hide');
				document.getElementById("overlay").style.display="inherit";
			},
			type: 'POST',
        	datatype: 'JSON',
        	success: function (resp) {	
				document.getElementById("overlay").style.display="none";
				
				if(resp["success"] == "yes"){
						verifyAccount();
				}
				else if(resp["success"] == "no"){
					$(".otp-message").html("  Please enter valid OTP");
					console.log(resp["otp_expire"])
				}
				else if(resp["success"] == "expired"){
					$('#myModal_assign').modal('show');
					$(".otp-message").html("OTP expired");
				}
			
        }
			
		})
	});


	$('body').on('click', '#btn-modal-resend', function () {
		var email = document.getElementById('email').value;
		$.ajax({
			url: 'approval/resendOtp',
			headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
			beforeSend: function(){
				$('#modal_resend').modal('hide');
				document.getElementById("overlay").style.display="inherit";
			},
			data: {email:email},
			type: 'POST',
			datatype: 'JSON',
			success: function (resp) {	
				document.getElementById("overlay").style.display="none";
				if(resp["send"] == "yes"){
					$('#myModal_assign').modal('show');
					alert(resp["success"]);
					expire_time_otp= resp["expires_otp"];
					console.log(resp["expires_otp"]);
				}
				else{
					$('#modal_resend').modal('show');
					$(".email-message").html("Email Salah");
				}
				
			}
		});
	});

	

	function verifyAccount(){
		var baseUrl = "{{URL::to('/')}}";
		var approval_id = document.getElementById('hide_approval_id').value;
		if(otp == ''){
			$('#myModal_assign').modal('show');
			return false;
		}else{
			$('#myModal_assign').modal('hide');
			if (confirm('Are you sure want to approve this document?')) {
				document.getElementById("overlay").style.display="inherit";	
				// document.location.href = baseUrl+'/admin/approval/assign/'+approval_id+'/'+encodeURIComponent(encodeURIComponent(password));
				document.location.href = baseUrl+'/admin/approval/assign/'+approval_id;
			}
		}
	}
</script>
@endsection