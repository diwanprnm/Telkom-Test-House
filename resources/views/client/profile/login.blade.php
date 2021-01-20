@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.login') }} - Telkom DDB</title>
	@section('content')
		<!-- Content
		============================================= -->
		<section id="content"> 
			<div class="content-wrap"> 
				<div class="container clearfix">  
					@if(session()->has('send_new_user'))
						<div class="alert alert-info" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<div style="text-align:center">
								{{ trans('translate.notification_after_register') }}
							</div>
						</div>
					@elseif(session()->has('send_new_password'))
						<div class="alert alert-info" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<div style="text-align:center">
								{{ trans('translate.notification_after_reset_password') }}
							</div>
						</div>
					@endif
					<div class="panel panel-default nobottommargin divcenter" style="max-width: 500px;">
						<div class="panel-body" style="padding: 40px;"> 
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/client/login') }}">
							 	{{ csrf_field() }}
								<input id="type_url" type="hidden" name="type_url" class="type_url">
								@foreach ($errors->all() as $error)
								    <div class="alert-warning"><strong>{{ $error }}</strong></div>
								@endforeach 
								<h3>{{ trans('translate.login_title') }}</h3>
								

								<div class="col_full form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label for="login-form-username">Email: *</label>
									<input type="text" id="login-form-username" name="email" value="{{ old('email') }}" placeholder="john@mail.com" class="form-control" required /> 
									@if ($errors->has('email'))
										<span class="help-block">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
									@endif   
								</div>

								<div class="col_full form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label for="login-form-password">Password: *</label>
									<input type="password" id="login-form-password"  name="password"  value="" class="form-control" placeholder="p@ssw0rd" id="password" data-toggle="password" required/>
									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
								</div>

								<div class="col_full form-group">
									<button class="button button-3d btn-sky nomargin hidden-xs hidden-sm show-md show-lg " id="login-form-submit" name="login-form-submit" value="login">{{ trans('translate.login') }}</button>
									<button class="button button-3d btn-sky btn-login-lg hidden-md hidden-lg show-xs show-sm nomargin" id="login-form-submit" name="login-form-submit" value="login">{{ trans('translate.login') }}</button>

									<div class="login-link">
										<a href="{{ url('/client/password/resetPass') }}" class="fright">{{ trans('translate.forgot') }}</a><br>
										<a href="{{ url('/register') }}" class="fright">{{ trans('translate.have_not_account') }}</a>	
									</div>
								</div>
							</form>
						 
						</div> 
					</div>
					 
				</div> 
			</div>

		</section><!-- #content end -->
		@if(!empty(Session::get('type_url')))
		<script type="text/javascript">
		$(function() {
			$('#type_url').val("{{Session::get('type_url')}}");
		});
		</script>
		@endif

@endsection
 
