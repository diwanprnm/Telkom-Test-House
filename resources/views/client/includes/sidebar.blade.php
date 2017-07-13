<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
			<li class="sidebar" style=" height:150px;">
            <img src="{{asset('asset/back.png')}}" width="235" height="150">
				<center>
                <div style="padding-top:40px; margin-top:-150px;" >
				@if( Auth::User()->image!=null)
					<img alt="" class="circle avatar" style="width:50px;" src="{{ asset(Auth::User()->image) }}"/><br>
				@else
					<img alt="" class="circle avatar" style="width:50px;" src="{{ asset('asset/pp.png') }}"/><br>
				@endif
				<span style="color:rgba(107,107,107,1.00); font-weight:bold;">{{ Auth::user()->name }}</span>
				</div>
                </center>
			</li>
			<?php 
				$segment_1 = Request::segment(1);
				$segment_2 = Request::segment(2);
			?>
				
			@if( Auth::User()->hasPermission('home_view') )
			<li @if($segment_1=='home') ? class="start active open" : "" @endif>
				<a href="{{URL::to('/home')}}">
				<i class="fa fa-dashboard"></i>
				<span class="title">Home</span>
				</a>
			</li>
			@endif
			
			@if( Auth::User()->hasPermission('user_view') )
			<li @if($segment_1=='users') ? class="start active open" : "" @endif>
				<a href="{{URL::to('/users')}}">
				<i class="fa fa-user"></i>
				<span class="title">
				Users</span>
				</a>
			</li>
			@endif
			
			@if( Auth::User()->hasPermission('userprivileges_view') )
			<li @if($segment_1=='privileges') ? class="start active open" : "" @endif>
				<a href="{{URL::to('/privileges')}}">
				<i class="fa fa-key"></i>
				<span class="title">User Privileges</span>
				</a>
			</li>			
			@endif
			
			<li>
				<a href="{{URL::to('logout')}}">
				<i class="fa fa-lock"></i>
				<span class="title">Logout</span>
				</a>
			</li>
		</ul>
	</div>
</div>