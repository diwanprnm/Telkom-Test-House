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
    <img alt="telkom" style="width:15%;" src={{ asset('images/logo_telkom.png') }} alt="logo telkom test house">
	</div>
	<h3 style="font-family:Arial; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
	<p style="font-style:italic; font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Kepada Yth.<br>
		Bapak/Ibu {!! $data['customerName'] !!}<br><br>
    </p>
    

    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
        Kami memohon maaf atas kejadian ini. 
    </p>

    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
        Kami memberitahukan bahwa terjadi perubahan harga biaya uji yang sebelum nya mengacu pada SPB 
        [No. SPB]<!--#ini#-->, oleh karena itu kami menerbitkan revisi SPB dengan nomor [No. SPB Revisi]<!--#ini#--> yang <strong>sudah terbit</strong> dan
        Bapak/Ibu <strong>dapat mengunduhnya di web <a href="www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a></strong> atau lampiran email ini.
        Mohon untuk <strong>mengabaikan email sebelumnya.</strong>
    </p>


    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
        Kami sampaikan juga <strong>pembayaran SPB</strong> dapat dilakukan dengan <strong>dua cara pembayaran</strong>, yaitu:
        <ul>
            <li>Pembayaran dengan <strong>Bank Transfer</strong></li>
            <li>Pembayaran dengan <strong>Virtual Account Bank Mandiri</strong></li>
        </ul>
    </p>

    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
        Silakan klik tautan di bawah ini untuk memilih cara pembayaran yang dikehendaki.
        <a href="">[tautan ke halaman pemilihan pembayaran]</a><!--#ini#-->
    </p>

    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
        Mohon periksa kembali biaya dan ketentuan-ketentuan yang berlaku demi kelancaran proses pembayaran.
    </p>

    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		Tata cara pembayaran dengan : {{ $data['paymentMethod']  }}<!--#ini#-->
		<ul style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00);"><!--#ini#-->
			@for ($i = 0; $i < count($data['howToPay']->data->VA); $i++)
			    <li>{{ $data['howToPay']->data->VA[$i]->productName }}</li>
			@endfor
		</ul>
    </p>
    <br><br>


	<p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
		<br>
		Salam hangat,<br>
		Telkom Test House - Laboratorium Quality Assurance – DDB<br>
		PT. Telekomunikasi Indonesia, Tbk.<br>
		Jl. Gegerkalong Hilir No. 47 Sukasari Bandung<br>
        45012<br><br>
		---
	</p>
	<p style="font-style:italic; font-family:Helvetica; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
        Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini.
        Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau
        <strong>cstelkomtesthouse@gmail.com.</strong>
	</p>
</div>

</body>
</html>