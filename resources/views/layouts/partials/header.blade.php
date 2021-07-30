@php
  $currentUser = Auth::user();
  if(!empty($currentUser)){

    $currentUserAttr = $currentUser['attributes'];
  } 
@endphp

<!-- Header
  ============================================= -->
  <header id="header" class="sticky-style-2 visible-lg visible-md hidden-sm hidden-xs">

    <div class="container clearfix">

      <!-- Logo
      ============================================= -->
      <div id="logo"> 
        <a href="{{url('/')}}" class="standard-logo"><img src="{{url('images/logo_telkom.png')}}" alt="logo telkom"></a>
        <a href="{{url('/')}}" class="retina-logo"><img src="{{url('images/logo_telkom.png')}}" alt="logo telkom hires"></a> 
      </div><!-- #logo end -->

      <ul class="header-extras">
        <li>
          <div class="he-text">
            <h2>{{ trans('translate.header_title') }}</h2>
          </div>
        </li>
      </ul>

    </div>

    <div id="header-wrap">

      <!-- Primary Navigation
      ============================================= -->
      <nav id="primary-menu" aria-label="Primary menu" class="style-2">

        <div class="container clearfix">

          <div id="primary-menu-trigger"><em class="icon-reorder"></em></div>

            <ul class="menuUtama">
              <li class="{{ (empty($page)) ?'current':''}}">
                <a href="{{url('/')}}">{{ trans('translate.home') }}</a>
              </li>  
              <li class="{{ (!empty($page) && ($page == 'about' || $page == 'sertifikasi' || $page == 'contact'))?'current':''}}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" >{{ trans('translate.menu_company') }}  <em class="icon-angle-down"></em></a>
                <ul class="dropdown-menu" role="menu">
                   <li><a href="{{url('about')}}">{{ trans('translate.about') }} {{ trans('translate.about_us') }}</a></li>
                  <li><a href="{{url('sertifikasi')}}">{{ trans('translate.certification') }}</a></li>
                  <li><a href="{{url('contact')}}">{{ trans('translate.contact') }}</a></li>
                </ul>
              </li>
              <li class="{{ (!empty($page) && ($page == 'Chargeclient' || $page == 'procedure' || $page == 'pengujian' || $page == 'process'))?'current':''}}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('translate.menu_testing') }} <em class="icon-angle-down"></em></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{url('procedure')}}">{{ trans('translate.procedure') }}</a></li>
                  <li><a href="{{url('process')}}">{{ trans('translate.process') }}</a></li>
                  <li><a href="{{ url('pengujian')}}">{{ trans('translate.examination') }}</a></li>
                  @php
                  if(!empty($new_charge[0])){
                    @endphp
                    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('translate.charge') }}</a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{url('Chargeclient')}}">{{ trans('translate.charge') }}</a></li>
                            <li><a href="{{url('NewChargeclient')}}">{{ trans('translate.new_charge') }} @php echo date("j M Y", strtotime($new_charge[0]->valid_from));@endphp</a></li>
                        </ul>
                    </li>
                  @php
                  }else{
                    @endphp
                    <li><a href="{{url('Chargeclient')}}">{{ trans('translate.charge') }}</a></li>
                  @php
                  }
                  @endphp
                </ul>
              </li> 
              <li class=" {{ (!empty($page) && $page == 'Devclient') ?'current':''}}">
                  <a href="{{url('Devclient')}}">{{ trans('translate.devic_test_passed') }}</a>
              </li>

              <li class=" {{ (!empty($page) && ($page == 'STEL' || $page == 'S-TSEL' || $page == 'STD' || $page == 'PERMENKOMINFO' || $page == 'PERDIRJEN' || $page == 'ANOTHER' || $page == 'purchase_history' || $page == 'products' || $page == 'checkout')) ?'current':''}}">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('translate.menu_reference') }} <em class="icon-angle-down"></em></a>
                   <ul class="dropdown-menu" role="menu">
                    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('translate.menu_ref') }}</a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{url('STEL')}}">STEL</a></li>
                            <li><a href="{{url('S-TSEL')}}">S-TSEL</a></li>
                            <li><a href="{{url('STD')}}">PED/STD/KJN</a></li>
                            <li><a href="{{url('PERMENKOMINFO')}}">PERMENKOMINFO</a></li>
                            <li><a href="{{url('PERDIRJEN')}}">PERDIRJEN</a></li>
                            <li><a href="{{url('OTHER')}}">OTHER(s)</a></li>
                        </ul>
                    </li>
                    <li>
                      <a href="{{url('/products')}}">{{ trans('translate.see_product') }}</a>
                    </li>
                      <li>
                      <a href="{{url('/purchase_history')}}">{{ trans('translate.payment_status') }}</a>
                    </li>
                  </ul>
              </li> 

              <li class="{{ (!empty($page) && $page == '#tutorial') ?'current':''}}">
                <a id="click-section-playlist" href="{{url('/')}}#tutorial">{{ trans('translate.video_tutorial') }}</a>
              </li>  

              <li class=" {{ (!empty($page) && $page == 'faq') ?'current':''}}">
                  <a href="{{url('faq')}}">FAQ</a>
              </li>
                 
            </ul>

            <ul class="menu-right">
              @if( Config::get('app.locale') == 'in')
                <li> <a href="#" class="dropdown-toggle" data-toggle="dropdown">INA <em class="icon-angle-down"></em></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a class="linkLang" href="{!! url('language') !!}/en">ENG</a></li>
                </ul>
                </li>
              @else
                <li> <a href="#" class="dropdown-toggle" data-toggle="dropdown">ENG  <em class="icon-angle-down"></em></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a class="linkLang" href="{!! url('language') !!}/in">INA</a></li>
                </ul></li>
              @endif 

              @php
              if($currentUser){
                @endphp 
                <li></li>
              @php   
              }else{
                @endphp
                <li><a href="{{ url('/register') }}">{{ trans('translate.sign_up') }}</a></li>
              @php
              }
              @endphp 

              @php
              if($currentUser){
                @endphp 
                <li class="loginMenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">@php echo substr($currentUserAttr['name'],0,7)."...";@endphp  <em class="icon-angle-down"></em></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{url('/client/profile')}}">{{ trans('translate.profile') }}</a></li>
                    <li><a href="{{url('/client/profile?tabs=company')}}">{{ trans('translate.company') }}</a></li>
                    <li><a href="{{url('/client/logout')}}">{{ trans('translate.logout') }}</a></li>
                  </ul> 
                </li>
              @php   
              }else{
                @endphp
                <li class="loginMenu"><a href="{{ url('login')}}">{{ trans('translate.login') }}</a></li>
              @php
              }
              @endphp

              @php
              if($currentUser){
                @endphp 
	              <div id="top-cart" style="float:left">
		                <a href="#" id="top-cart-trigger"><em class="icon-shopping-cart"></em><span>{{Cart::count()}}</span></a>
		                 @if(Cart::count() >= 1)
		                <div class="top-cart-content">
		                    <div class="top-cart-title">
		                      <h4>{{ trans('translate.shopping_cart') }}</h4>
		                    </div>
		                   
		                    @foreach(Cart::content() as $row)
		                    <div class="top-cart-items">
		                      <div class="top-cart-item clearfix"> 
		                        <div class="top-cart-item-desc">
		                          <a href="#">{{$row->name}}</a>
		                          <span class="top-cart-item-price">Rp. {{number_format($row->price, 0, '.', ',')}}</span>
		                          <span class="top-cart-item-quantity">x {{$row->qty}}</span>
		                        </div>
		                      </div>
		                    </div>
		                    @endforeach
		                    
		                    <div class="top-cart-action clearfix">
		                      <span class="fleft top-checkout-price">Rp. {{number_format(Cart::subTotal(), 0, '.', ',')}}</span>
		                      <a class="button button-3d button-small nomargin fright" href="{{url('products')}}">{{ trans('translate.view_cart') }}</a>
		                    </div>
		                </div>
		                @endif
	              </div>
	              <li>  <a href="#" id="top-notification-trigger"><em class="icon-bell"></em><span id="notification-count">@php echo $notification_count;@endphp</span></a></li>
	             	<div id="top-notification" style="float:left"> 
	                	<div class="top-notification-content">
                      @php 
                      foreach ($notification_data_user as $notif) { @endphp
                      <div class="top-notification-items">
                        <div class="top-notification-item clearfix">
                          <div class="top-notification-item-desc">
                            <a data-url="@php echo $notif['url']@endphp" data-id="@php echo $notif['id']@endphp" class="notifData">{{$notif['message']}}</a> 
                          </div>
                        </div>
                      </div>

                    @php }@endphp
						<div class="top-notification-items">
							<div class="top-notification-item clearfix">
							  <div class="top-notification-item-desc">
								<a href="{{url('/all_notifications')}}">{{ trans('translate.see_all_notifications') }}</a> 
							  </div>
							</div>
						  </div>
	                	</div> 
	              	</div> 
              @php   
              }else{
                @endphp
              	<div id="top-cart" style="float:left">
	            </div>
                <li></li>
              @php
              }
              @endphp


              


            </ul>

        </div>

      </nav><!-- #primary-menu end -->

    </div>

  </header><!-- #header end -->


  <!-- Header Mobile
  ============================================= -->
  <header id="header" class="full-header static-sticky hidden-lg hidden-md visible-sm visible-xs">

    <div id="header-wrap">

      <div class="container clearfix">

        <div id="primary-menu-trigger"><em class="icon-reorder"></em></div>

        <!-- Logo
        ============================================= -->
        <div id="logo">
            <a href="{{url('/')}}" class="standard-logo"><img src="{{url('images/logo_telkom.png')}}" alt="logo telkom"></a>
            <a href="{{url('/')}}" class="retina-logo"><img src="{{url('images/logo_telkom.png')}}" alt="logo telkom hires"></a> 
        </div><!-- #logo end -->

        <!-- Primary Navigation
        ============================================= -->
        <nav id="primary-menu" aria-label="Primary menu">

            <ul>
              <li class="{{ (empty($page)) ?'current':''}}">
                <a href="{{url('/')}}">{{ trans('translate.home') }}</a>
              </li> 
              <li class="{{ (!empty($page) && ($page == 'about' || $page == 'sertifikasi' || $page == 'contact'))?'current':''}}">
                <a href="#" >{{ trans('translate.menu_company') }}  <em class="icon-angle-down"></em></a>
                <ul>
                   <li><a href="{{url('about')}}">{{ trans('translate.about') }} {{ trans('translate.about_us') }}</a></li>
                  <li><a href="{{url('sertifikasi')}}">{{ trans('translate.certification') }}</a></li>
                  <li><a href="{{url('contact')}}">{{ trans('translate.contact') }}</a></li>
                </ul>
              </li>
              <li class="{{ (!empty($page) && ($page == 'STELclient' || $page == 'STSELclient' || $page == 'Chargeclient' || $page == 'procedure' || $page == 'pengujian'))?'current':''}}">
                <a href="#">{{ trans('translate.menu_testing') }} <em class="icon-angle-down"></em></a>
                <ul>
                  <li><a href="{{url('procedure')}}">{{ trans('translate.procedure') }}</a></li>
                  @php
                  if($currentUser){
                    @endphp 
                  <li><a href="{{ url('pengujian')}}">{{ trans('translate.examination') }}</a></li>
                  @php   
                  }else{
                    @endphp
                  <li><a href="{{ url('login')}}">{{ trans('translate.examination') }}</a></li>
                  @php
                  }
                  @endphp
                  <li><a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('translate.menu_ref') }}</a>
                      <ul>
                          <li><a href="{{url('STELclient')}}">{{ trans('translate.stel') }}</a></li>
                          <li><a href="{{url('STSELclient')}}">{{ trans('translate.stels') }}</a></li>
                      </ul>
                  </li>
                  <li><a href="{{url('process')}}">{{ trans('translate.process') }}</a></li>
                  <li><a href="{{url('Chargeclient')}}">{{ trans('translate.charge') }}</a></li>
                </ul>
              </li> 
              <li class=" {{ (!empty($page) && $page == 'Devclient') ?'current':''}}">
                  <a href="{{url('Devclient')}}">{{ trans('translate.devic_test_passed') }}</a>
              </li>

              <li class=" {{ (!empty($page) && ($page == 'payment_status' || $page == 'products' || $page == 'checkout')) ?'current':''}}">
                  <a href="#">Stel <em class="icon-angle-down"></em></a>
                   <ul>
                    <li>
                      <a href="{{url('/products')}}">{{ trans('translate.see_product') }}</a>
                    </li>
                      <li>
                      <a href="{{url('/payment_status')}}">{{ trans('translate.payment_status') }}</a>
                    </li>
                  </ul>
              </li> 
               @php
              if($currentUser){
                @endphp 
                <li><a href="#">@php echo $currentUserAttr['name'];@endphp  <em class="icon-angle-down"></em></a>
                  <ul>
                    <li><a href="{{url('/client/profile')}}">{{ trans('translate.profile') }}</a></li>
                    <li><a href="{{url('/client/logout')}}">{{ trans('translate.logout') }}</a></li>
                  </ul> 
                </li>
              @php   
              }else{
                @endphp
                <li><a href="{{ url('login')}}">{{ trans('translate.login') }}</a></li>
              @php
              }
              @endphp 
                 @if( Config::get('app.locale') == 'in')
                <li> <a href="#" >INA <em class="icon-angle-down"></em></a>
                <ul >
                  <li><a href="{!! url('language') !!}/en">ENG</a></li>
                </ul>
                </li>
              @else
                <li> <a href="#">ENG  <em class="icon-angle-down"></em></a>
                <ul >
                  <li><a href="{!! url('language') !!}/in">INA</a></li>
                </ul></li>
              @endif  
             
              <div id="top-cart" style="float:left">
                <a href="#" id="top-cart-trigger"><em class="icon-shopping-cart"></em><span>{{Cart::count()}}</span></a>
                 @if(Cart::count() >= 1)
                <div class="top-cart-content">
                    <div class="top-cart-title">
                      <h4>{{ trans('translate.shopping_cart') }}</h4>
                    </div>
                   
                    @foreach(Cart::content() as $row)
                    <div class="top-cart-items">
                      <div class="top-cart-item clearfix"> 
                        <div class="top-cart-item-desc">
                          <a href="#">{{$row->name}}</a>
                          <span class="top-cart-item-price">Rp. {{$row->price}}</span>
                          <span class="top-cart-item-quantity">x {{$row->qty}}</span>
                        </div>
                      </div>
                    </div>
                    @endforeach
                    
                    <div class="top-cart-action clearfix">
                      <span class="fleft top-checkout-price">Rp. {{Cart::subtotal()}}</span>
                      <a class="button button-3d button-small nomargin fright" href="{{url('products')}}">{{ trans('translate.view_cart') }}</a>
                    </div>
                </div>
                @endif
              </div>
              <li><a href="#" id="top-cart-trigger"><em class="icon-bell"></em></a></li>
            
            </ul>

        </nav><!-- #primary-menu end -->

      </div>

    </div>
    <input type="hidden" id="user_id" value="@php echo (empty($currentUser))?0:$currentUserAttr['id'];@endphp">
  </header><!-- #header mobile end -->