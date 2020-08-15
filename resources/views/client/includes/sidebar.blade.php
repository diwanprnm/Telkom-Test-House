<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
			<li class="sidebar" style=" height:150px;">
            <img src="{{asset('asset/back.png')}}" width="235" height="150" alt="back">
			 	
                <div style="padding-top:40px; margin-top:-150px;" >
				@if( Auth::User()->image!=null)
					<img alt="" class="circle avatar" style="width:50px;" src="{{ asset(Auth::User()->image) }}"/><br>
				@else
					<img alt="" class="circle avatar" style="width:50px;" src="{{ asset('asset/pp.png') }}"/><br>
				@endif
				<span style="color:rgba(107,107,107,1.00); font-weight:bold;">{{ Auth::user()->name }}</span>
				</div> 
			</li>
			@php 
				$segment_1 = Request::segment(1);
				$segment_2 = Request::segment(2);
			@endphp
				
			@if( Auth::User()->hasPermission('home_view') )
			<li @if($segment_1=='home') ? class="start active open" : "" @endif>
				<a href="{{URL::to('/home')}}">
				<em class="fa fa-dashboard"></em>
				<span class="title">Home</span>
				</a>
			</li>
			@endif
			
			@if( Auth::User()->hasPermission('user_view') )
			<li @if($segment_1=='users') ? class="start active open" : "" @endif>
				<a href="{{URL::to('/users')}}">
				<em class="fa fa-user"></em>
				<span class="title">
				Users</span>
				</a>
			</li>
			@endif
			
			@if( Auth::User()->hasPermission('userprivileges_view') )
			<li @if($segment_1=='privileges') ? class="start active open" : "" @endif>
				<a href="{{URL::to('/privileges')}}">
				<em class="fa fa-key"></em>
				<span class="title">User Privileges</span>
				</a>
			</li>			
			@endif
			
			<li>
				<a href="{{URL::to('logout')}}">
				<em class="fa fa-lock"></em>
				<span class="title">Logout</span>
				</a>
			</li>
		</ul>
	</div>
</div>