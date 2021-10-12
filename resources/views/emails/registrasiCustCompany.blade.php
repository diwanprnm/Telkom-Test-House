<!doctype html>
<html lang="en" translate="no">
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
		<strong>== PEMBERITAHUAN ==</strong>
		<br><br>
	</p>
	<p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Kastamer atas nama {!! $user_name !!} dan email {!! $user_email !!}, mengajukan permohonan aktivasi akun baru beserta data perusahaan baru <strong>(belum terdaftar pada aplikasi)</strong>.
		<br>
		Perusahaan bernama {!! $comp_name !!}, beralamat di {!! $comp_address !!}. Dengan nomor telepon {!! $comp_phone !!} dan email {!! $comp_email !!} 
		<br><br>
		Silakan lakukan konfirmasi terhadap Perusahaan dan Kastamer di atas. Data selengkapnya, dapat dilihat pada aplikasi. 
		<br>
		*Notes: Lakukan Aktivasi terhadap Perusahaan terlebih dahulu, lalu lakukan aktivasi terhadap akun kastamer.
		<br><br>
		Salam hangat,
		<br>
		---
		<br><br>
	</p>
	<p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau <strong>cstelkomtesthouse@gmail.com.</strong>
	</p>
</div>

</body>
</html>