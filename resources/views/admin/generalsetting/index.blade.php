@extends('layouts.app')

@section('content')
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
			
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/generalSetting/'.$data[0]->id, 'method' => 'PUT')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Manage General Information
						</legend>
						<div class="row">
						 	<div class="col-md-12">
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
					</fieldset>
				{!! Form::close() !!}
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
	</div>
</div>
@endsection

@section('content_js')
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
<script type="text/javascript">
	<?php
		if($data[1]->is_active){
	?>		
			$('#is_poh').prop('checked', true);
			$("#poh_manager_urel-div").show();
			$("#manager_urel-div").hide();
	<?php
		}else{
	?>
			$('.is_poh').prop('checked', false);
			$("#manager_urel-div").show();
			$("#poh_manager_urel-div").hide();
	<?php
		}
	?>
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
	});
</script> 
@endsection