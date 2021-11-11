<!doctype html>
<html lang="en">
<style>
	* {
		font-family: "Lato", sans-serif;
	}
</style>
<head>
	<meta charset="UTF-8">
	<title>Autentikasi Dokumen</title>
</head>
<body>
<div class="header" style="margin-top:2%;margin-bottom:2%;">
	
</div>
<div style="text-align:center;">
<img style="width:15%;" src="{{ \Storage::disk('minio')->url('logo/download.png') }}" alt="logo telkom">
	<br>
	<strong>Autentikasi dokumen elektronik</strong>
	<br><br>
	Telkom Test House menyatakan bahwa
</div>
<br>
<div class="content" style="width:75%;background-color:rgba(255,255,255,1.00); border-radius:15px; box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.2); position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
	<p style="margin-left:3%;margin-top:3%;">
		<strong>Jenis Dokumen:</strong>
		<br>
		{!! $data['name'] !!}
		<br><br>
		<strong>Nama Dokumen:</strong>
		<br>
		{!! $data['attachment'] !!}
		<br><br>
		<strong>Kode Dokumen:</strong>
		<br>
		{!! $data['document_code'] !!}
		<br><br>
		<strong>Nama Perusahaan:</strong>
		<br>
		{!! $data['company_name'] !!}
		<br><br>
		<strong>Nama Perangkat:</strong>
		<br>
		{!! $data['device_name'] !!}
		<br><br>
		<strong>Merk:</strong>
		<br>
		{!! $data['mark'] !!}
		<br><br>
		<strong>Tipe:</strong>
		<br>
		{!! $data['model'] !!}
		<br><br>
		<strong>Kapasitas:</strong>
		<br>
		{!! $data['capacity'] !!}
		<br><br>
		<strong>Nomor Seri Perangkat:</strong>
		<br>
		{!! $data['serial_number'] !!}
		<br><br>
		<strong>Tanggal Kedaluwarsa:</strong>
		<br>
		{!! $data['valid_thru'] !!}
		<br><br>
	</p>
	<hr style="border-top: 0.02px solid grey; margin-left:3%;margin-right:3%;">
	<p style="margin-left:3%;">
		Telah ditandatangi oleh
		<br><br>
		@foreach($data['approveBy'] as $approveBy)
		@if($approveBy->user->email != '1' && $approveBy->user->email != 'admin@mail.com')
		<strong>
			{!! $approveBy->user->name !!}
			<br>
			{!! $approveBy->user->role->name !!}
			<br>
		</strong>
		pada {!! $approveBy->approve_date !!}
		<br><br><br>
		@endif
		@endforeach
		<h3 style="margin-left:3%;">Adalah benar sertifikat QA yang diterbitkan oleh Telkom Test House.</h3>
	</p>
	<p style="font-family:Lato, sans-serif; font-size:0.88em;margin-left:3%;margin-top:1%;margin-bottom:3%;">
		Untuk memastikan bahwa anda mengakses halaman autentikasi yang benar, pastikan URL pada browser anda adalah https://www.telkomtesthouse.co.id
	</p>
</div>

</body>
</html>