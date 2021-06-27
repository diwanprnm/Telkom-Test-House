@extends('layouts.app')

@section('content')
<style type=”text/css”>
table {
    margin: 8px;
}


</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">General Setting</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Tools</span>
					</li>
					<li class="active">
						<span>General Setting</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
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

			@if ($message)
				<div class="alert alert-error alert-danger">
					{{ $message }}
				</div>
			@endif
			
			@if(isset($data[0]))
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/generalSetting/'.$data[0]->id, 'method' => 'PUT', 'id' => 'form-update')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Manage General Information
						</legend>
						<div class="row">
						 	<div class="col-md-3">
								<div class="form-group">
									<label>
										<input type="checkbox" id="is_poh" name="is_poh">
										POH
									</label>
								</div> 
							</div>
	                        <div class="col-md-12">
                        		<div id="poh_manager_urel-div" class="form-group">
									<label>
										POH *
									</label>
									<input type="text" name="poh_manager_urel" class="form-control" placeholder="POH" value="{{$data[1]->value}}" required autofocus="">
								</div>
								<div id="manager_urel-div" class="form-group">
									<label>
										Manager Urel *
									</label>
									<input type="text" name="manager_urel" class="form-control" placeholder="Manager Urel" value="{{$data[0]->value}}" required autofocus="">
								</div>
							</div>
	                      
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
	                            <a style=" color:white !important;" href="{{URL::to('/admin')}}">
									<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
									Cancel
									</button>
								</a>
	                        </div>
						</div>
						<div class="modal fade" id="myModal_update" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><em class="fa fa-eyes-open"></em>Setting Manager URel, POH dan/atau Aktifasi Send Email Akan Diganti, Mohon Berikan Keterangan!</h4>
									</div>
									
									<div class="modal-body">
										<table style="width: 100%;">
										<caption></caption>
											<tr>
												<th scope="col">
													<div class="form-group">
														<label for="keterangan">Keterangan:</label>
														<textarea class="form-control" rows="5" name="keterangan" id="keterangan"></textarea>
													</div>
												</th>
											</tr>
										</table>
									</div><!-- /.modal-content -->
									<div class="modal-footer">
										<table style="width: 100%;">
										<caption></caption>
											<tr>
												<th scope="col">
													<button type="submit" id="btn-modal-update" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
												</th>
											</tr>
										</table>
									</div>
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div>
					</fieldset>
					<fieldset>
						<legend>
							Aktifasi Fungsi Send Email
						</legend>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>
										<input checked="checked" type="checkbox" id="is_send_email_active" name="is_send_email_active">
											Pengaktifan Fungsi Send Email
									</label>
								</div> 
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
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
			@endif
		</div>
		<!-- end: RESPONSIVE TABLE -->
	</div>
</div>
@endsection

@section('content_js')
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
@if($data)
<script type="text/javascript">
	@php
		if(isset($data[2]) && $data[2]->is_active) {
	@endphp
			$('#is_send_email_active').prop('checked', true);
	@php
		} else {
	@endphp
			$('#is_send_email_active').prop('checked', false);
	@php
		}
	@endphp

	@php
		if(isset($data[1]) && $data[1]->is_active){
			@endphp		
			$('#is_poh').prop('checked', true);
			$("#poh_manager_urel-div").show();
			$("#manager_urel-div").hide();
	@php
		}else{
	@endphp
			$('.is_poh').prop('checked', false);
			$("#manager_urel-div").show();
			$("#poh_manager_urel-div").hide();
	@php
		}
	@endphp
	$(document).ready(function() {
	    $('#is_poh').change(function() {
	        if(this.checked) {
	            $("#poh_manager_urel-div").show();
	            $("#manager_urel-div").hide();
	        }else{
	            $("#manager_urel-div").show();
	        	$("#poh_manager_urel-div").hide();
	        }
	    });

	    $('#myModal_update').on('shown.bs.modal', function () {
		    $('#keterangan').focus();
		});
	});

	$('#form-update').submit(function () {
		var keterangan = document.getElementById('keterangan').value;
		if(keterangan == ''){
			$('#myModal_update').modal('show');
			return false;
		}else{
			$('#myModal_update').modal('hide');
			document.getElementById("overlay").style.display="inherit";
		}
	});
</script> 
@endif
@endsection