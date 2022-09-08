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
		Kepada Yth.
		<br>
		Bapak/Ibu {!! $user_name !!}
		<br><br>
	</p>
	<p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Permohonan uji perangkat anda di Telkom Test House <strong>sudah selesai</strong>. @if($is_loc_test == 0)Perangkat sampel uji agar segera diambil kembali sebagai syarat untuk @else Anda dapat @endif mengunduh Laporan Hasil Uji (LHU) dan Sertifikat di <a href="www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a> @if($is_loc_test == 0) .
		<br>
		Dokumen tersebut nanti dapat anda unduh @endif dengan cara <strong><em>masuk/login</em></strong> terlebih dahulu, lalu pilih menu <strong><em>pengujian/testing > status pengujian/progress</em></strong> dan tekan tombol <strong>unduh laporan & unduh sertifikat/<em>download report & download certificate</em></strong> yang terletak <strong>di kanan bawah</strong> dari permohonan uji anda.
		<br><br>
		Terimakasih atas kerjasama anda.
		<br>
		Untuk info lebih lanjut silakan hubungi kami di <strong>+62 812 2483 7500</strong>
		<br><br>
		Salam hangat
		<br>
		Telkom Test House, PT. Telekomunikasi Indonesia, Tbk
		<br>
		Jl. Gegerkalong Hilir No. 47 Sukasari Bandung
		<br>
		45012
		<br><br>
	</p>
	<p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau <strong>cstelkomtesthouse@gmail.com.</strong>
	</p>
</div>

</body>
</html>