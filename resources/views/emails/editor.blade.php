<!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Telkom Test House</title>
    <style>
        .header{
            margin-top:2%;
            margin-bottom:2%;
            
            }
        .content{
            width:80%;
            min-height:450px;
            background-color:rgba(255,255,255,1.00);
            border: 3px #7bd4f8 solid;
            border-radius:15px;
            position:relative;
            margin-left:auto;
            margin-right:auto;
            padding-left:25px;
            padding-right:25px;
            padding-top:5px;
            padding-bottom:5px;
            
            }
        
        @font-face{
            font-family:font-bold;
            src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
        }
        @font-face{
            font-family:font-regular;
            src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
        }
    </style>
    </head>
    <body>
    <div class="header" style="margin-top:2%;margin-bottom:2%;">
        
    </div>
    <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
        <div style="text-align:right;">
            <img style="width:15%;" src="{{ \Storage::disk('minio')->url('logo/'.$logo) }}" alt="logo telkom">
        </div>
        <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
        {!! $content !!}
        <br><br>
        {!! $signature !!}
    </div>        
    </body>
</html>