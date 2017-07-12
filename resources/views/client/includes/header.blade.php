<header class="cd-main-header animate-search navbar-fixed-top">
	<div class="cd-logo"><a href="#0"><img src="{{asset('template-assets/img/baru/logo.png')}}" width="130" alt="Logo"></a></div>
		<nav class="cd-main-nav-wrapper">
			<a href="#search" class="cd-search-trigger cd-text-replace"></a>			
			 <!-- .cd-main-nav -->
		</nav> <!-- .cd-main-nav-wrapper -->
		<nav class="cd-main-nav-wrapper asa" style="background-color:rgba(251,70,73,1.00);">
			<ul class="cd-main-nav">
				<li><a href="{{url('/')}}#owl-hero" class="page-scroll">{{ trans('translate.home') }}</a></li>
				<li><a href="{{url('/')}}#about" class="page-scroll">{{ trans('translate.about') }}</a></li>
				<li><a href="{{url('/')}}#procedure" class="page-scroll">{{ trans('translate.procedure') }}</a></li>
				<li><a href="{{url('/')}}#portfolio" class="page-scroll">{{ trans('translate.service') }}</a></li>
				<li><a href="{{url('/')}}#contact1" class="page-scroll">{{ trans('translate.contact') }}</a></li>
				<li class="dropdown"><button class="page-scroll dropdown-toggle btn-header" type="button" id="menu1" data-toggle="dropdown">{{ trans('translate.stel_ref_uji') }}<span class="caret"></span></button>
					<ul class="dropdown-menu b-red" role="menu" aria-labelledby="menu1">
						<li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('/STELclient')}}" class="page-scroll">{{ trans('translate.stel') }}</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('/STSELclient')}}" class="page-scroll">{{ trans('translate.stels') }}</a></li>
					</ul></a>
				</li>
				<li><a href="{{url('/Chargeclient')}}" class="page-scroll">{{ trans('translate.charge') }}</a></li>
				<li><a href="{{url('/Devclient')}}" class="page-scroll">{{ trans('translate.devic_test_passed') }}</a></li>
				<li><a href="#" class="url-pengujian">{{ trans('translate.examination') }}</a></li>
			</ul>
			<ul class="cd-main-nav-kanan pull-right marginavlogin">
				<li><a href="{{URL::to('/client/downloadUsman')}}" class="page-scroll"><img src="{{asset('template-assets/img/baru/PDF.png')}}" width="13" style="margin-top:-4.7px;"></a></li>
				<li><a href="{!! url('language') !!}/en" class="page-scroll"><img src="{{asset('template-assets/img/baru/united-kingdom.png')}}" width="13" style="margin-top:-4.7px;"></a></li>
				<li><a href="{!! url('language') !!}/in" class="page-scroll"><img src="{{asset('template-assets/img/baru/indonesia.png')}}" width="13" style="margin-top:-4.7px;"></a></li>
				<?php
					$currentUser = Auth::user();
					if($currentUser){
				?>
					<!-- <li><a href="{{url('/client/logout')}}" class="page-scroll"><strong>LOGOUT</strong></a></li> -->
					<li class="dropdown"><button class="page-scroll dropdown-toggle btn-header" type="button" id="menu1" data-toggle="dropdown"><?php echo $currentUser['attributes']['name'];?><span class="caret"></span></button>
						<ul class="dropdown-menu b-red" role="menu" aria-labelledby="menu1">
							<li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('/client/profile')}}">{{ trans('translate.profile') }}</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('/client/logout')}}">{{ trans('translate.logout') }}</a></li>
						</ul></a>
					</li>
				<?php		
					}else{
				?>
					<li><a href="#" class="page-scroll" data-toggle="modal" data-target="#myModallogin"><strong>{{ trans('translate.login') }}</strong></a></li>
				<?php
					}
				?>
			</ul>
		</nav>
	<a href="#0" class="cd-nav-trigger cd-text-replace">Menu<span></span></a>
</header>
	<main class="cd-main-content">
		<!-- your content here -->		
	</main>
	
@if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
<script type="text/javascript">
$(function() {
	$('#myModallogin').modal('show');
});
</script>
@endif
	
@if(!empty(Session::get('type_url')))
<script type="text/javascript">
$(function() {
	$('#type_url').val("{{Session::get('type_url')}}");
});
</script>
@endif

<div class="modal fade bs-example-modal-lg" id="myModallogin" aria-labelledby="myModalloginLabel" style="display: none;" data-backdrop="static" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content">
			<div id="contact">
				<div class="panel panel-default red-telkom">
					<div class="panel-heading red-telkom">{{ trans('translate.login') }}</div>
					 <a data-dismiss="modal" style="cursor:pointer;"><img src="{{asset('template-assets/img/close (2).png')}}" style=" margin-top:-38px; margin-right:14px; float:right;" width="18"></a>
				</div>
				<form class="form-horizontal" role="form" method="POST" action="{{ url('/client/login') }}">
					<input id="type_url" type="hidden" name="type_url" class="type_url">
					{{ csrf_field() }}

					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<label for="email" class="col-md-4 control-label">{{ trans('translate.email') }}</label>

						<div class="col-md-6">
							<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<label for="password" class="col-md-4 control-label">{{ trans('translate.password') }}</label>

						<div class="col-md-6">
							<input id="password" type="password" class="form-control" name="password" required>

							@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
						</div>
					</div>
					
					<div class="form-group f1-buttons" style="margin-bottom:25px; height:25px;">
						<div class="col-md-6 col-md-offset-4">
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-btn fa-sign-in"></i> {{ trans('translate.login') }}
							</button>		
						</div>
					</div>
					<div class="form-group" style="margin-bottom:-5px; height:25px; font-size: 90%;">
								{{ trans('translate.have_not_account') }} ?
								<a class="btn btn-link" style="margin-left:-10px; height:37px; color:black !important; font-size: 100%;" href="{{ url('/client/register') }}">{{ trans('translate.click_here') }} !</a>
							</div>
							<div class="form-group" style="margin-bottom:10px; height:25px; font-size: 90%;">
								{{ trans('translate.forgot') }} ?
								<a class="btn btn-link" style="margin-left:-10px; height:37px; color:black !important; font-size: 100%;" href="{{ url('/client/password/resetPass') }}">{{ trans('translate.click_here') }} !</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$('.url-pengujian').click(function(){
		$.ajax({
			type: "POST",
			url : "{{url('cekLogin')}}",
			data: {'_token':"{{ csrf_token() }}"},
			// dataType:'json',
			type:'post',
			success: function(data){
				if(data == 0){
					$('#myModallogin').modal('show');
					$('#type_url').val(2);
				}else{
					// var APP_URL = {!! json_encode(url('/pengujian')) !!};
					location.href = "{{url('/pengujian')}}";
				}
			}
		});
	});
	
	$('#selectLanguageEn').click(function(){
		$.ajax({
			type: "POST",
			url : "{{url('/change/en')}}",
			data: {'_token':"{{ csrf_token() }}"},
			// dataType:'json',
			type:'post',
			success: function(data){
				location.href = "{{url('/')}}";
			}
		});
	});
	
	$('#selectLanguageIn').click(function(){
		$.ajax({
			type: "POST",
			url : "{{url('/change/in')}}",
			data: {'_token':"{{ csrf_token() }}"},
			// dataType:'json',
			type:'post',
			success: function(data){
				location.href = "{{url('/')}}";
			}
		});
	});
});
</script>