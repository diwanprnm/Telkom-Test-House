<!DOCTYPE html>
<html lang="en">
<head>
	<script type="text/javascript">
		try {
			if (top.location.hostname != self.location.hostname) throw 1;
		} 
		catch (e) {
			top.location.href = self.location.href;
		}
	</script>
    <!-- META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TELKOM DIGITAL SERVICE</title>

    <!-- Fonts -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <link href={{ asset("vendor/bootstrap/css/bootstrap.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/fontawesome/css/font-awesome.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/themify-icons/themify-icons.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/animate.css/animate.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/switchery/switchery.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("assets/css/styles.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/bootstrap-colorpicker.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/plugins.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/themes/theme-1.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/chosen.min.css") }} rel="stylesheet" type="text/css">
	
    <link href={{ asset("assets/css/jquery-ui-1_12_1.css") }} rel="stylesheet" type="text/css">
	<script src={{ asset("assets/js/jquery-1.12.4.js") }}></script>
	<script src={{ asset("assets/js/jquery-ui-1_12_1.js") }}></script>
    <style type="text/css">
        .notification-count{
            margin-top: -24px;
            position: absolute;
            background: rgb(214, 0, 0);
            color: white;
            padding: 2px;
            border-radius: 14px;
            font-family: 'Raleway', sans-serif;
            font-size: 12px;
        }
       
    </style>
</head>

<body>
    <div id="app">
        <!-- sidebar -->
        <div class="sidebar app-aside" id="sidebar">
            <div class="sidebar-container perfect-scrollbar">
                <nav>
                    <!-- start: MAIN NAVIGATION MENU -->
                    <div class="navbar-title">
                        <span>Navigation</span>
                    </div>
                   <?php
                    $segment_1 = Request::segment(1);
                    $segment_2 = Request::segment(2);
                    $html = '<ul id="main-navigation-menu">';
                    foreach ($tree_menus as $key => $value) {
                        if(isset($value[0]['children'])) {  
                            $found = false;
                            foreach ($value[0]['children'] as $child) { 
                                if($segment_2==$child['url']){
                                    $found = true;
                                }
                            } 
                        $is_active = ($found) ? 'class="active"' : '' ;
                            $html .= '<li '.$is_active.'>  <a >
                                <div class="item-content">
                                    <div class="item-media">
                                        <i class="'.$value[0]['icon'].' "></i>
                                    </div>
                                    <div class="item-inner">
                                        <span class="title"> '.$value[0]['name'].' </span><i class="icon-arrow"></i>
                                    </div>
                                </div>
                            </a>';
                            $html .= '<ul class="sub-menu">';
                           
                            foreach ($value[0]['children'] as $child) { 
                                $is_active = ($segment_2==$child['url']) ? 'class="active"' : '' ;
                                $html .= '<li '.$is_active.'>  <a href="'.url("admin/".$child['url']).'">
                                                    <span class="title"> '.$child['name'].' </span> 
                                        </a></li>'; 
                            }
                            $html .= '</ul></li>';
                        }else{
                          $is_active = ($segment_2==$value[0]['url']) ? 'class="active"' : '' ;
                          $html .= '<li '.$is_active.'> <a href="'.url("admin/".$value[0]['url']).'">
                                    <div class="item-content">
                                        <div class="item-media">
                                            <i class="'.$value[0]['icon'].' "></i>
                                        </div>
                                        <div class="item-inner">
                                            <span class="title"> '.$value[0]['name'].' </span>
                                        </div>
                                    </div>
                                </a></li>';
                        }
                    }
                    $html .= '</ul>';

                    echo $html;
                 ?> 
                </nav>
            </div>
        </div>
        <!-- / sidebar -->
        <div class="app-content">
            <!-- start: TOP NAVBAR -->
            <header class="navbar navbar-default navbar-static-top">
                <!-- start: NAVBAR HEADER -->
                <div class="navbar-header">
                    <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
                        <i class="ti-align-justify"></i>
                    </a>
                    <a class="navbar-brand" href="{{URL::to('/admin')}}">
                        <img src='{{ asset("assets/images/dds.png") }}' width="120"/>
                    </a>
                    <a href="#" class="sidebar-toggler pull-right visible-md visible-lg" data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
                        <i class="ti-align-justify"></i>
                    </a>
                    <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="ti-view-grid"></i>
                    </a>
                </div>
                <!-- end: NAVBAR HEADER -->
                <!-- start: NAVBAR COLLAPSE -->
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-right">
                      
                       <li class="dropdown notification">
                          <a href class="dropdown-toggle" data-toggle="dropdown">
                                <span class="dot-badge partition-red notification-count"><?php echo $notification_count;?></span> 
                                <i class="ti-comment"></i> 
                                <span translate="topbar.messages.MAIN" class="ng-scope ">Notification</span>
                            </a>
                            <ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large"> 
                                
                            </ul>
                        </li>
                        <li class="dropdown current-user">
                            <a href class="dropdown-toggle" data-toggle="dropdown">
                                <img src='{{ asset("media/user/".Auth::user()->id."/".Auth::user()->picture) }}' alt="{{ Auth::user()->name }}" width="32" height="32"> <span class="username">{{ Auth::user()->name }} <i class="ti-angle-down"></i></i></span>
                            </a>
                            <ul class="dropdown-menu dropdown-dark">
								<li>
                                    <a href="{{ url('/admin/downloadUsman') }}">
                                        Download User Manual
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/user/'.Auth::user()->id) }}">
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/logout') }}">
                                        Log Out
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end: USER OPTIONS DROPDOWN -->
                    </ul>
                </div>
            </header>
            <!-- end: TOP NAVBAR -->
            @yield('content')
        </div>

        <!-- start: FOOTER -->
        <footer>
            <div class="footer-inner">
                <div class="pull-left">
                    &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> TELKOM DDS</span>. <span>All rights reserved</span>
                </div>
                <div class="pull-right">
                    <span class="go-top"><i class="ti-angle-up"></i></span>
                </div>
            </div>
        </footer>
        <!-- end: FOOTER -->
    </div>
   
    <!-- start: MAIN JAVASCRIPTS -->
    <!-- <script src={{ asset("vendor/jquery/jquery.min.js") }}></script> -->
    <script src={{ asset("vendor/bootstrap/js/bootstrap.min.js") }}></script>
    <script src={{ asset("vendor/modernizr/modernizr.js") }}></script>
    <script src={{ asset("vendor/jquery-cookie/jquery.cookie.js") }}></script>
    <script src={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.js") }}></script>
    <script src={{ asset("vendor/switchery/switchery.min.js") }}></script>
    <script src={{ asset("assets/js/main.js") }}></script>
    <script src={{ asset("assets/js/chosen.jquery.min.js") }}></script>
	<script src={{ asset("assets/js/accounting.min.js") }}></script>
	<script src={{ asset("assets/js/jquery.price_format.min.js") }}></script> 
    @yield('content_js')
    <script>
        jQuery(document).ready(function() {
            Main.init();
        });
    </script>
    <?php if(config('app.IS_ENABLED_NOTIFICATION')){?>
     <script src="{{url('vendor/socket/socket.io.js')}}"></script>
      <script>
        //var socket = io('http://localhost:3000');
        var socket = io('http://37.72.172.144:3000');
        socket.on("notification-channel:App\\Events\\Notification", function(message){ 
            var userId = $("#user_id").val();
            console.log(message.data);
            if(message.data.to === "admin"){ 
                var notificationCount = parseInt($(".notification-count").html());
                 var html =  '<li>'+
                                    '<a data-url="'+message.data.url+'" data-id="'+message.data.id+'" class="notifData">'+ message.data.message
                                   '</a>'+
                                '</li>';
                $(".dropdown-messages").append(html);
                $(".notification-count").html(notificationCount+1);
                initClickNotif();
            }
           
        });
        initClickNotif();
        function initClickNotif(){
            $(".notifData").on("click",function(){ 
                var notifID = $(this).attr("data-id");
                var notifURL = $(this).attr("data-url");
                $.ajax({ 
                    type: "POST",
                    url : "<?php echo URL::to('/'); ?>/updateNotif", 
                    data:{
                        "notif_id":notifID
                    }, 
                    success: function(data){ 
                        console.log(data);
                        $(".notification-count").html(data); 
                        window.location.href = "<?php echo URL::to('/'); ?>/admin/"+notifURL; 
                    }
                }); 
            });
        }
        
    </script>
    <?php }?>
    <!-- end: MAIN JAVASCRIPTS -->
</body>
</html>
