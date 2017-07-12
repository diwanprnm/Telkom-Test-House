@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.reset_password') }} - Telkom DDS</title>
@section('content')        
        <!-- Content
        ============================================= -->
        <section id="content">

            <div class="content-wrap"> 
                <div class="container clearfix">  
                       <!--  <div id="msg_box" class="msg_sent"> Your maessage has been sent</div>
                        <div id="msg_box" class="msg_loading"> Sending...</div> -->
                        @if(session()->has('status'))
                            <div id="msg_box" class="msg_sent">
                                {{ session()->get('status') }}
                            </div>
                        @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/client/password/email') }}">
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
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="button button-3d btn-sky">
                                    <i class="fa fa-btn fa-envelope"></i> {{ trans('translate.reset_password_send') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div>

        </section><!-- #content end --> 

@endsection
 
