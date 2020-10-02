@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.reset_password') }} - Telkom DDB</title>
@section('content')        
        <!-- Content
        ============================================= -->
        <section id="content">

            <div class="content-wrap"> 
                <div class="container clearfix">  
						<div class="before_sent">
                            <div id="msg_box" class="msg_loading send_msg"> {{ trans('translate.sending') }}</div>
                            {{-- @if(session()->has('status'))
                                <div id="msg_box" class="msg_sent">
                                    {{ session()->get('status') }}Your message has been sent.
                                </div>
                            @endif --}}
                        <form id="form-reset" class="form-horizontal" role="form" method="POST" action="{{ url('/client/password/email') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">{{ trans('translate.reset_password_email') }} *</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required="" placeholder="user@mail.com" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
									
									<div id="error_msg" class="error_msg">
                                        <span class="help-block">
                                            <strong>{{ session()->get('message') }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="button button-3d btn-sky">
                                        <em class="fa fa-btn fa-envelope"></em> {{ trans('translate.reset_password_send') }}
                                    </button>
                                </div>
                            </div>
                        </form>
						</div>
                       <div class="done_sent">
                           <div class="done">
                               <em class="fa fa-check-circle" aria-hidden="true"></em>
                           </div>
                           <div class="content">
                               <h3>{{ trans('translate.pass_reset_email_sent') }}</h3>
                               <p>{{ trans('translate.an_email_has_been_sent') }} <strong>{{ session()->get('status') }}.</strong></p>
                               <p>{{ trans('translate.follow_the_direction') }}</p>
                           </div>
                           <div class="footer">
                               <a href="{{ url('/login') }}" style="color:#299ec0 !important">{{ trans('translate.done') }}</a>
                           </div>
                       </div>

                </div> 
            </div>

        </section><!-- #content end -->  
@endsection

@section('content_js')
@if(session()->has('message'))
	<script type="text/javascript">
		$(".error_msg").show();
		$(".done_sent").hide();
		$(".send_msg").hide();
	</script>
@else
	@if(session()->has('status'))
		<script type="text/javascript">
			$(".done_sent").show();
			$(".before_sent").hide();
			$(".error_msg").hide();
		</script>
	@else
		<script type="text/javascript">
			$(".before_sent").show();
			$(".done_sent").hide();
			$(".send_msg").hide();
			$(".error_msg").hide();
			$('#form-reset').submit(function(ev) {
				ev.preventDefault(); // to stop the form from submitting
				/* Validations go here */
				$(".send_msg").show();
				this.submit(); // If all the validations succeeded
			});
		</script>
	@endif
@endif
@endsection