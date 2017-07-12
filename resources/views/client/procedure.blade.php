@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.procedure') }} - Telkom DDS</title>
@section('content')
 <!-- Page Title
	============================================= -->
	<section id="page-title">

		<div class="container clearfix">
			<h1>{{ trans('translate.menu_testing') }}</h1>
			
			<ol class="breadcrumb">
				<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
				<li class="active">{{ trans('translate.procedure') }}</li>
			</ol>
		</div>

	</section><!-- #page-title end -->

	<!-- Content
	============================================= -->
	<section id="content">

		<div class="content-wrap">


			<div class="container clearfix">

				<div class="tabs clearfix" id="tab-1">

						<ul class="tab-nav clearfix">
							<li><a href="#tabs-1">{{ trans('translate.service') }}</a></li>
							<li><a href="#tabs-2">{{ trans('translate.buy_stel') }}</a></li>
						</ul>

						<div class="tab-container">

							<div class="tab-content clearfix" id="tabs-1">
								<div class="col_full">

									<div id="text-carousel" class="carousel slide">
									    <!-- Wrapper for slides -->
									    <div class="row">
									        <div class="carousel-inner">
									                <div class="item active">
									                    <div class="carousel-content">
									                        <div id="tp_step1" class="col_full">
																<div  class="col_half">
																	<h2>A. Registrasi Pengujian</h2>
																	<p>Customer melakukan beberapa kegiatan sebagai berikut</p>
																	<ul class="numbering-main-procedure">
																		<li>Melakukan pengisian Form Uji</li>
																		<li>Melengkapi Persyaratan, dengan dokumen uji sebagai berikut;
																			<ol class="numbering-second-procedure">
																				<li>Copy SIUP</li>
																				<li>Copy NPWP</li>
																				<li>Suran Penunjukan dari Principal (agen distributor/pabrikan)</li>
																				<li>Data Sheet Perangkat</li>
																			</ol>
																		</li>
																	</ul>
																	<p>Pengujian yang dilakukan</p>
																	<ol class="numbering-second-procedure">
																		<li>Quality Assurance</li>
																		<li>Voluntary Test</li>
																		<li>Test Approval</li>
																	</ol>
																</div>
																
																<div class="col_half col_last right">
																	<img src="images/procedure/tp_1.png">
																</div>
															</div>
									                    </div>
									                </div>
									                <div class="item">
									                    <div class="carousel-content">
									                        <div id="tp_step2" class="col_full">
																<div  class="col_half">
																	<h2>B. Uji Fungsi</h2>
																	<p>Test Eng. Lab dan Customer melakukan beberapa kegiatan sebagai berikut;</p>
																	<ul>
																		<li>Penyediaan perangkat uji dan data sheet</li>
																		<li>Perangkat masuk Gudang</li>
																		<li>Penyediaan Engineer</li>
																		<li>Pelaksanaan Uji Fungsi</li>
																	</ul>
																	<br>
																	<h2>C. Contract Review</h2>
																	<p>UREL dan Customer melakukan beberapa kegiatan sebagai berikut;</p>
																	<ul>
																		<li>Pengisian Form Contract Review</li>
																	</ul>
																</div>
																
																<div class="col_half col_last right">
																	<img src="images/procedure/tp_2.png">
																</div>
															</div>
									                    </div>
									                </div>
									                <div class="item">
									                    <div class="carousel-content">
									                        <div id="tp_step3" class="col_full">
																<div  class="col_half">
																	<h2>D. SPB</h2>
																	<p>UREL melakukan beberapa kegiatan sebagai berikut</p>
																	<ul>
																		<li>Perhitungan Biaya Uji</li>
																		<li>Surat Pemberitahuan Biaya Uji</li>
																	</ul>

																	<h2>E. Pembayaran</h2>
																	<p>Prosedur dilakukan Customer, yang mana pembayaran ini dilakukan via transfer.</p>

																	<h2>F. Pembuatan SPK</h2>
																	<p>Prosedur ini dilakukan oleh UREL</p>
																	<ul>
																		<li>Cek Kelengkapan Uji</li>
																		<li>Penentuan Target Waktu</li>
																		<li>Penerbitan SPK</li>
																	</ul>
																	
																</div>
																
																<div class="col_half col_last right">
																	<img src="images/procedure/tp_3.png">
																</div>
															</div>
									                    </div>
									                </div>
									                <div class="item">
									                    <div class="carousel-content">
									                        <div id="tp_step4" class="col_full">
																<div  class="col_half">
																	<h2>G. Pelaksanaan Uji</h2>
																	<p>Prosedur ini dilakukan oleh Lab, dengan ketentuan yang di uji sebagai berikut;</p>
																	<ul>
																		<li>Kesanggupan/Tidak</li>
																		<li>Pelaksanaan Uji</li>
																	</ul>
																	<br>
																	<h2>H. Laporan Uji</h2>
																	<p>Laporan Uji dilakukan di Lab, pada tahap ini, untuk Proses Uji VT telah selesai, sedangkan Proses UJI TA harus dilakukan pengunggahan data ke Web SDPPI (TA). Sedangkan untuk Proses QA, harus melakukan 2 tahap selanjutnya.</p>
																	<br>
																	<h2>I. Sidang QA</h2>
																	<p>Prosedur ini ditentukan oleh dua tahap sebagai berikut;</p>
																	<ul>
																		<li>Penjadwalan Sidang QA</li>
																		<li>Pelaksanaan Sidang QA</li>
																	</ul>
																	
																</div>
																
																<div class="col_half col_last right">
																	<img src="images/procedure/tp_4.png">
																</div>
															</div>
									                    </div>
									                </div>
									                <div class="item">
									                    <div class="carousel-content">
									                        <div id="tp_step5" class="col_full">
																<div  class="col_half">
																	<h2>J. Penerbitan Sertifikat</h2>
																	<p>Prosedur ini merupakan prosedur terakhir yang dilakukan UREL. Proses ini adalah proses QA terkahir, dengan beberapa point yang akan dilakukan sebagai berikut;</p>
																	<ul>
																		<li>Cetak Laporan Uji</li>
																		<li>Penerbitan Sertifikat</li>
																		<li>Pemberitahuan Pengambilan Sertifikat</li>
																	</ul>
																	
																</div>
																
																<div class="col_half col_last right">
																	<img src="images/procedure/tp_5.png">
																</div>
															</div>
									                    </div>
									                </div>
									                
									            </div>
									    </div>
								  
								   
									</div>

									<div class="col_full">
										<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
											<a type="button" name="previous" class="carousel-control button button-3d nomargin btn-sky" href="#text-carousel" data-slide="prev">Prev</a>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6 right">
											<a type="button" name="next" class="carousel-control button button-3d nomargin btn-sky" href="#text-carousel" data-slide="next">Next</a>
										</div>
									</div>

								</div>

							</div>
							<div class="tab-content clearfix" id="tabs-2">
								<div class="col_full">

									<div id="pdstel1" class="col_full">
										<div class="col_half">
											<h2>A. Pengajuan Pembelian</h2>
											<p>Prosedur ini dilakukan oleh customer yang dilakukan via email dengan melampirkan Judul/Perangkat Uji</p>
											
											<h2>B. Feedback</h2>
											<p>Setelah mendapatkan email pengaduan dari Customer, UREL melakukan Feedback dengan melakukan point sebagai berikut</p>
											<ol class="numbering-second-procedure">
												<li>Melakukan konfirmasi  nama, kode, versi, dan harga dokumen</li>
												<li>Pemberitahuan Rekening Telkom</li>
											</ol>

											<h2>C. Pembayaran</h2>
											<p>Prosedur lanjutan ini dilakukannya pembayaran oleh Customer via transfer, dengan melampirkan tanda bukti transfer yang dikirim melalui email.</p>
										</div>
										<div class="col_half col_last right">
											<img src="images/procedure/pdstel_1.png">
										</div>
									</div>

									<div id="pdstel2" class="col_full">
										<div class="col_half">
											<h2>D. Menyiapkan Dokumen yang dibeli</h2>
											<p>Setelah dilakukannya pembayaran dan konfirmasinya melalui email. UREL menyiapkan dokumen yang dibeli dengan mebubuhkan watermark.</p>
											
											<h2>E. Pengiriman Dokumen</h2>
											<p>Setelah dokuymen yang diminta telah disiapkan, file-file dokumen pun dukurum oleh UREL melalui email.</p>

											<h2>F. Konfirmasi Penerimaan dokumen</h2>
											<p>Setelah semua tahap sesuai, dan menerima dokumen yang telah disiapkan Customer memeberikan konfirmasi bahwa dokumen telah diterima.</p>
										</div>
										<div class="col_half col_last right">
											<img src="images/procedure/pdstel_2.png">
										</div>
									</div>
								</div>
							</div>

						</div>

					</div>

			</div>



			</div>

		</div>

	</section><!-- #content end -->

@endsection
 
