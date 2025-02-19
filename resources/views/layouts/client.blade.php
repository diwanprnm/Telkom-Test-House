<!DOCTYPE html>
<html dir="ltr" lang="en-US" translate="no">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />
    <title>Telkom Test House</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link href={{ asset("vendor/fontawesome/css/font-awesome.min.css") }} rel="stylesheet" type="text/css">
    <!-- Stylesheets
    ============================================= -->
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet"> 
    <link rel="stylesheet" href="{{url('new-layout/css/bootstrap.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/style.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/responsive.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/swiper.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/dark.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/font-icons.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/animate.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/magnific-popup.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/components/bs-datatable.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/responsive.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/products.css')}}" type="text/css" />

    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{url('new-layout/css/material-form.css')}}" type="text/css" />

	<link href={{ asset("assets/css/chosen.min.css") }} rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style>
        #header.sticky-style-2.navbar-in-header { height: 151px; }

        #header.sticky-style-2.navbar-in-header #header-wrap,
        #header.sticky-style-2.navbar-in-header.sticky-header:not(.static-sticky) #header-wrap { height: 50px; }

        #header.sticky-style-2.navbar-in-header.sticky-header:not(.static-sticky) { height: 151px; }

        @media (max-width: 991px) {
            #header.sticky-style-2.navbar-in-header #header-wrap { min-height: 50px; }
        }
        #overlay {
          z-index: 1000;
          display:none;
          position: fixed;
          top: 0px;
          left: 0px;
          width: 100%;
          height: 100%;
          background: rgba(4, 10, 30, 0.8);
        }
        #tengah{
            width: 250px;
            height: 30px;
            position: absolute;
            top:0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            display: block;
    		text-align: -webkit-center;
        }
    </style>
    @include('client.includes.favicon') 
</head>

<body class="stretched">
    <div id="overlay">
        <div id="tengah"> 
            <br>
            <span style="color:#ffffff">Please wait ....</span>
            <!-- <img src='{{ asset("images/loading.gif") }}' alt="loading image"/>  -->
        </div>
    </div>
    <!-- Document Wrapper
    ============================================= -->
    <div id="wrapper" class="clearfix">

        <!-- Header-->
            @include('layouts.partials.header')
        <!-- #header end -->
            @yield('content')
       
           @include('layouts.partials.footer')
      

    </div><!-- #wrapper end -->

    <!-- Go To Top
    ============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>

    <!-- External JavaScripts
    ============================================= -->
    <script type="text/javascript" src="{{url('new-layout/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('new-layout/js/plugins.js')}}"></script>

    <!-- Footer Scripts
    ============================================= -->
    <script type="text/javascript" src="{{url('new-layout/js/functions.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/search.js')}}"></script>
    <script type="text/javascript" src="{{url('vendor/jquerymaterial/jquery.material.form.js')}}"></script> <!-- JQUERY MATERIAL FORM PLUGIN -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>

    <script src={{ asset("assets/js/chosen.jquery.min.js") }}></script>
    <script type="text/javascript">
    if ($( window ).width() <= 425) {
      if (!$( ".step" ).hasClass("pre-scrollable")) {
        $( ".step" ).addClass( "pre-scrollable" );
      }
    }
    if ($( window ).width() >= 426) {
        $( ".step" ).removeClass( "pre-scrollable" );
    }
      
    $('#text-carousel').carousel({
        interval: false
    });

    var indeks = 0;
    if (indeks == 0) {
      $( '#prevTP' ).hide();
    }

    $('#text-carousel').on('slide.bs.carousel',function(e){
      var slideFrom = $(this).find('.active').index();
      var slideTo = $(e.relatedTarget).index();

      indeks = slideTo;
      if (indeks == 0) {
        $( '#prevTP' ).hide();
      }if (indeks > 0) {
        $( '#prevTP' ).show();
      }
      if (indeks == 4) {
        $( '#nextTP' ).hide();
      }if (indeks < 4) {
        $( '#nextTP' ).show();
      }
    });



    $("#password").password('toggle');
    $("#newPass").password('toggle');
    $("#confnewPass").password('toggle');
    $("#currPass").password('toggle');

    $(document).ready(function() {

        $('form.material').materialForm(); // Apply material
        $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
        $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});
        var payment_method = $('input[type=radio][name=payment_method]').val();
        if(payment_method == "atm"){
            $(".metodb .sm-form-control").prop('required',false);
        }else{
           $(".metodb .sm-form-control").prop('required',true);
        }
        $('input[type=radio][name=payment_method]').change(function(){
            var payment_method = $(this).val();
            console.log(payment_method);
            if(payment_method == "va"){
                $(".metoda").css("display","none");
                $(".metodb").css("display","block");
                 $(".metodb .sm-form-control").prop('required',true);
            }else{
                $(".metodb .sm-form-control").prop('required',false);
                $(".metoda").css("display","block");
                $(".metodb").css("display","none");
            }
        });

        $("#top-notification-trigger").on("click",function(){
            if($("#top-notification").hasClass("top-notification-open")){
                $("#top-notification").removeClass("top-notification-open");
            }else{
                $("#top-notification").addClass("top-notification-open");
            }
        });

        $("#top-cart-trigger").on("click",function(){
           $("#top-notification").removeClass("top-notification-open"); 
        });
    });
        $(window).scroll(function (event) {
          var sc = $(window).scrollTop();
          $( ".linkLang" ).click(function() {
             localStorage.sc = sc;
          });

      });

          $( ".menuUtama" ).click(function() {
             localStorage.sc = "";
          });

          $( ".loginMenu" ).click(function() {
             localStorage.sc = "";
          });

          $( "#logo" ).click(function() {
             localStorage.sc = "";
          });

          $( "#footer" ).click(function() {
             localStorage.sc = "";
          });

          window.onload = function () { 
            $( window ).scrollTop( localStorage.sc );
          }
    </script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   @php if(config('app.IS_ENABLED_NOTIFICATION')){@endphp
    <script src="{{url('vendor/socket/socket.io.js')}}"></script>
    <script>
        var socket = io("{{ config('app.SOCKET_ENDPOINT')}}");
        // var socket = io('http://testhouse-notification-service:3000');
        socket.on("notification-channel:App\\Events\\Notification", function(message){ 
            var userId = $("#user_id").val();
            console.log(message.data);
            console.log(userId+" "+message.data.to);
            if(message.data.to === userId){ 
                var notificationCount = parseInt($("#notification-count").html());
                 var html = '<div class="top-notification-items">'+
                    '<div class="top-notification-item clearfix"> '+
                     ' <div class="top-notification-item-desc">'+
                        '<a data-id="'+message.data.id+'" data-url="'+message.data.url+'" class="notifData">'+message.data.message+'</a> '+
                      '</div>'+
                    '</div>'+
                  '</div>';
                $(".top-notification-content").prepend(html);
                $("#notification-count").html(notificationCount+1); 

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
                    url : "@php echo URL::to('/'); @endphp/updateNotif", 
                    data:{
                        "notif_id":notifID
                    }, 
                    success: function(data){ 
                        console.log(data);
                        $(".notification-count").html(data); 
                       
                        window.open("@php echo URL::to('/'); @endphp/"+notifURL, '_blank', 'toolbar=yes, location=yes, status=yes, menubar=yes, scrollbars=yes');
                    }
                }); 
            });
        }
    </script>

    @php }@endphp
     @yield('content_js')
     <script src="https://rawgit.com/kswedberg/jquery-smooth-scroll/master/jquery.smooth-scroll.js"></script>
      <script type="text/javascript">
        $(document).ready(function() {
            document.addEventListener('contextmenu', event => event.preventDefault());
          var scrollnow = function(e) {
              // if scrollnow()-function was triggered by an event
              if (e) {
                  e.preventDefault();
                  var target = this.hash;
              }
              // else it was called when page with a #hash was loaded
              else {
                  var target = location.hash;
              }

              // same page scroll
              $.smoothScroll({
                  scrollTarget: target
              });
          };

          // if page has a #hash
          if (location.hash) {
              $('html, body').scrollTop(0).show();
              // smooth-scroll to hash
              scrollnow();
          }

          // for each <a>-element that contains a "/" and a "#"
          $('a[href*="/"][href*=\\#]').each(function(){
              // if the pathname of the href references the same page
              if (this.pathname.replace(/^\//,'') == location.pathname.replace(/^\//,'') && this.hostname == location.hostname) {
                  // only keep the hash, i.e. do not keep the pathname
                  $(this).attr("href", this.hash);
              }
          });

          // select all href-elements that start with #
          // including the ones that were stripped by their pathname just above
          $('a[href^=\\#]:not([href=\\#])').click(scrollnow);

        });
      </script>
     <script type="text/javascript">
      /*if(window.location.href.substring(window.location.href.lastIndexOf('/') + 1) == "#tutorial"){
        window.location.hash = "#tutorial";
      }*/
    </script>
</body>
</html>
