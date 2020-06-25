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
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <link href={{ asset("vendor/bootstrap/css/bootstrap.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/fontawesome/css/font-awesome.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/themify-icons/themify-icons.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/animate.css/animate.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/switchery/switchery.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("assets/css/styles.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/plugins.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/themes/theme-1.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.css") }} rel="stylesheet" type="text/css">
</head>

<body>
    <div id="app">
        @yield('content')
    </div>

    <!-- start: MAIN JAVASCRIPTS -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.js"></script>

    <script src={{ asset("vendor/jquery/jquery.min.js") }}></script>
    <script src={{ asset("vendor/bootstrap/js/bootstrap.min.js") }}></script>
    <script src={{ asset("vendor/modernizr/modernizr.js") }}></script>
    <script src={{ asset("vendor/jquery-cookie/jquery.cookie.js") }}></script>
    <script src={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.js") }}></script>
    <script src={{ asset("vendor/switchery/switchery.min.js") }}></script>
    <script src={{ asset("assets/js/main.js") }}></script>
    @yield('content_js')
    <script>
        jQuery(document).ready(function() {
            Main.init();
        });

        $("#password").password('toggle');
    </script>
    <!-- end: MAIN JAVASCRIPTS -->
</body>
</html>
