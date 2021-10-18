<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Autentikasi Dokumen - Telkom Test House</title>
</head>
<body>
<div class="header" style="margin-top:2%;margin-bottom:2%;">
	
</div>
<div style="text-align:center;">
<img style="width:15%;" src="{{ \Storage::disk('minio')->url('logo/'.$logo) }}" alt="logo telkom">
	<br>
	<strong>Autentikasi dokumen elektronik</strong>
	<br><br>
	Telkom Test House menyatakan bahwa
</div>
<br>
<div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
    {!! $content !!}
	<hr style="border-top: 1px solid red;">
	<p>
		Telah ditandatangi oleh
		{!! $assign_by !!}
		<br><br><br><br>
		<strong>Adalah benar {!! $jenis_dokumen !!} yang diterbitkan oleh Telkom Test House.</strong>
	</p>
	<p>
        {!! $signature !!}
	</p>
</div>

</body>
</html>