<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DDB</title>
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
		<img style="width:15%;" src="http://37.72.172.144/telkom-dds-web/public/assets/images/Telkom-Indonesia-Corporate-Logo1.jpg">
	</div>
	<h3 style="font-family:Arial; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
	<p style="font-style:italic; font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Kepada Yth.
		<br>
		Bapak/Ibu {!! $user_name !!}
		<br><br>
	</p>
	<p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Berkenaan dengan pendaftaran uji {!! $exam_type !!} ({!! $exam_type_desc !!}) perangkat Bapak/Ibu yang sudah memenuhi proses uji fungsi, maka SPB dengan nomor <strong>{!! $spb_number !!} telah terbit dan dapat mengunduhnya di web </strong> <a href="https://www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a> atau lampiran email ini.
		<br><br>
		Kami sampaikan juga <strong>pembayaran SPB</strong> dilakukan melalui <strong>Virtual Account</strong> dengan pilihan sebagai berikut :
		<ul style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00);">
			@for ($i = 0; $i < count($payment_method->data->VA); $i++)
			    <li>{{ $payment_method->data->VA[$i]->productName }}</li>
			@endfor
		</ul>
	</p>
	<p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Silakan klik tautan di bawah ini untuk melakukan proses pembayaran.
		<br>
		<a href="{{ $link = url('pengujian/'.$id.'/pembayaran') }}"><p style="text-align:center">{{ $link }}</p></a>
	</p>
	<p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Mohon periksa kembali biaya dan ketentuan-ketentuan yang berlaku demi kelancaran proses pembayaran.
		<br><br>
		Salam hangat,
		<br>
		Telkom Test House - Laboratorium Quality Assurance – DDB
		<br>
		PT. Telekomunikasi Indonesia, Tbk.
		<br>
		Jl. Gegerkalong Hilir No. 47 Sukasari Bandung
		<br>
		45012
		<br><br>
		---
		<br><br>
	</p>
	<p style="font-style:italic; font-family:Helvetica; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau <strong>cstelkomtesthouse@gmail.com.</strong>
	</p>
</div>

</body>
</html>