<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DDS</title>
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
		src:url('http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf');
	}
	@font-face{
		font-family:font-regular;
		src:url('http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf');
	}
</style>
</head>
<body>
<div class="header" style="margin-top:2%;margin-bottom:2%;">
	
</div>
<div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
	<div style="text-align:right;">
		<img style="width:15%;" src="http://37.72.172.144/telkom-dds-web/public/assets/images/Telkom-Indonesia-Corporate-Logo1.jpg" alt="logo telkom">
	</div>
	<h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
	<p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Kepada Yth.
		<br>
		Bapak/Ibu {!! $user_name !!}
		<br><br>
	</p>
	<p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Dokumen pengajuan Uji {!! $exam_type !!} ({!! $exam_type_desc !!}) Anda telah diperiksa oleh staff User Relation Lab Infrastructure Assurance DDS Telkom. 
		Namun, pengujian terhenti pada tahap {!! $tahap !!}, <strong>dengan alasan {!! $keterangan !!}.</strong>
		<br><br>
		Mohon untuk melakukan <strong>koordinasi terlebih dahulu</strong> dengan menghubungi staff User Relation di (022) 4571145 atau dengan mengirimkan email ke urelddstelkom@gmail.com .
		<br><br>
		Salam hangat,
		<br>
		Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.
		<br><br>
		---
		<br><br>
	</p>
	<p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi Urel Care di 022 - 4571145 dari ponsel, atau manfaatkan fasilitas webmail di urelddstelkom@gmail.com untuk menghubungi staff User Relation kami.
	</p>
</div>

</body>
</html>