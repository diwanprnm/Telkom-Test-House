<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />
<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" integrity="sha384-dNpIIXE8U05kAbPhy3G1cz+yZmTzA6CY8Vg/u2L9xRnHjJiAK76m2BIEaSEV+/aU" crossorigin="anonymous">
    <!-- Stylesheets
    ============================================= -->
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet"> 
    <link rel="stylesheet" href="{{url('new-layout/css/bootstrap.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/style.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/swiper.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/dark.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/font-icons.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/animate.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/magnific-popup.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/components/bs-datatable.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/responsive.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('new-layout/css/products.css')}}" type="text/css" />
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
    </style>
    @include('client.includes.favicon') 
</head>

<body class="stretched">

    <!-- Document Wrapper
    ============================================= -->
    <div id="wrapper" class="clearfix">

        <!-- Header-->
            @include('layouts.partials.header')
        <!-- #header end -->
            @yield('content')
       
           @include('layouts.partials.footer')
      

    </div><!-- #wrapper end -->

    <div id="myModal" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Survey Kepuasan Kastamer Eksternal</h4>
          </div>
          <div class="modal-body">
            <form>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" name="tanggal" placeholder="DD/MM/YYYY" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" placeholder="John Doe" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <input type="text" name="company" placeholder="PT. ABCD" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="no_tlp" placeholder="0812345678" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Jenis Pengujian</label>
                            <input type="text" name="tanggal" placeholder="Nama Pengujian" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" name="nama" placeholder="user@mail.com" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" name="company" placeholder="0812345678" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h2>Harap isikan penilaian anda terhadap Layanan QA/TA/VT</h2>
                    <p>Kustomer diharapkan dapat untuk beberapa kriteria yang diajukan. Nilai tersebut merupakan nilai kustomer  berikan mengenai ekspetasi setuju dan PT. Telkom.

                    Skala pemberian nilai adalah 1 - 7 dengan nilai 7 adalah penilaian Sangat Tidak Baik atau Tenaga dengan
                    </p>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

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
    $('#text-carousel').carousel({
        interval: false
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
            if(payment_method == "cc"){
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
        
    </script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

    <script src="{{url('vendor/socket/socket.io.js')}}"></script>
    <script>
        //var socket = io('http://localhost:3000');
        var socket = io('http://localhost:3000');
        socket.on("notification-channel:App\\Events\\Notification", function(message){ 
            var userId = $("#user_id").val();
            console.log(message.data);
            if(message.data.to === userId){ 
                var notificationCount = parseInt($("#notification-count").html());
                 var html = '<div class="top-notification-items">'+
                    '<div class="top-notification-item clearfix"> '+
                     ' <div class="top-notification-item-desc">'+
                        '<a href="'+message.data.action+'">'+message.data.message+'</a> '+
                      '</div>'+
                    '</div>'+
                  '</div>';
                $("#notification-item").append(html);
                $("#notification-count").html(notificationCount+1); 
            }
           
        });
    </script>
     @yield('content_js')
</body>
</html>