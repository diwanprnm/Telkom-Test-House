@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Video Tutorial</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Tools</span>
					</li>
					<li class="active">
						<span>Video Tutorial</span>
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
			
			@if(count($data))
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/videoTutorial/'.$data[0]->id, 'method' => 'PUT', 'id' => 'form-update')) !!}
					{!! csrf_field() !!}
    				<fieldset>
						<legend>
							Manage URL Information
						</legend>
						<div class="row">
						 	<div class="col-md-2">
                    			<label>
									Profile URL *
								</label>
							</div>
							<div class="col-md-10 form-group">
								<input type="text" name="profile_url" class="form-control" placeholder="https://www.youtube.com/embed/[id]" value="{{ $data[0]->profile_url }}">
							</div>
	                      	
	                      	<div class="col-md-2">
                    			<label>
									Buy STEL URL *
								</label>
							</div>
							<div class="col-md-10 form-group">
								<input type="text" name="buy_stel_url" class="form-control" placeholder="https://www.youtube.com/embed/[id]" value="{{ $data[0]->buy_stel_url }}">
							</div>
	                      
						 	<div class="col-md-2">
                    			<label>
									QA URL *
								</label>
							</div>
							<div class="col-md-10 form-group">
								<input type="text" name="qa_url" class="form-control" placeholder="https://www.youtube.com/embed/[id]" value="{{ $data[0]->qa_url }}">
							</div>
	                      	
	                      	<div class="col-md-2">
                    			<label>
									TA URL *
								</label>
							</div>
							<div class="col-md-10 form-group">
								<input type="text" name="ta_url" class="form-control" placeholder="https://www.youtube.com/embed/[id]" value="{{ $data[0]->ta_url }}">
							</div>
	                      
						 	<div class="col-md-2">
                    			<label>
									VT URL *
								</label>
							</div>
							<div class="col-md-10 form-group">
								<input type="text" name="vt_url" class="form-control" placeholder="https://www.youtube.com/embed/[id]" value="{{ $data[0]->vt_url }}">
							</div>
	                      	
	                      	<div class="col-md-2">
                    			<label>
									Playlist URL *
								</label>
							</div>
							<div class="col-md-10 form-group">
								<input type="text" name="playlist_url" class="form-control" placeholder="https://www.youtube.com/embed?list=[id]" value="{{ $data[0]->playlist_url }}">
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
@endsection