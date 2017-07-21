@extends('layouts.login')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('translate.reset_password') }}</div>

                <div class="panel-body">
                    <form id="form-reset" class="form-horizontal" role="form" method="POST" action="{{ url('/client/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{ trans('translate.reset_password_email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" readonly>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">{{ trans('translate.reset_password_new_pass') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">{{ trans('translate.reset_password_confirm_pass') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control pass" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
								<span class="help-block error_text" style="display: none;">
									<strong>{{ trans('translate.register_password_not_match') }}</strong>
								</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-refresh"></i> {{ trans('translate.reset_password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src={{ asset("vendor/jquery/jquery.min.js") }}></script>
<script>
	$( ".pass" ).keyup(function() {
		if ($(".pass").val() == null || $(".pass").val() == "") {
	  	// alert("Wrong Type");
	  	$(".error_text").hide();
	  	$("#password-confirm").removeClass("error");
	  }
	  if ($(".pass").val() != $("#password").val()) {
	  	// alert("Wrong Type");
	  	$(".error_text").show();
	  	$("#password-confirm").addClass("error");
	  }
	  if ($(".pass").val() == $("#password").val()) {
	  	// alert("Wrong Type");
	  	$(".error_text").hide();
	  	$("#password-confirm").removeClass("error");
	  }
	});
	
	$( document ).ready(function() {
		$("#form-reset").on("click",function(){
			var password = $("#password").val();
			var confirmPassword = $("#password-confirm").val();
			if(password != confirmPassword){
				return false;
			}else{
				return true;
			}
		});

		 $('#password, #password-confirm').bind("cut copy paste",function(e) {
			 e.preventDefault();
		 });
	 
	 }); 
</script>
@endsection
