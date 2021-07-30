<?php

use Illuminate\Database\Seeder;

class FaqTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faq')->insert([
            [
                'id' => '1', 
                'question' => 'Berapa lama waktu yang dibutuhkan untuk aktivasi akun baru?', 
                'answer' => '<p>Setelah pelanggan melakukan registrasi akun, Admin akan melakukan approval terlebih dahulu paling lama 1 hari. Kemudian Admin akan menghubungi pelanggan bahwa akun sudah aktif dan dapat digunakan.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:50:08',
                'updated_at' => '2021-07-20 10:50:08',
                'category' => '1'
            ],[
                'id' => '2', 
                'question' => 'Apa saja dokumen yang diperlukan pada saat registrasi untuk perusahaan baru?', 
                'answer' => '<p>Dokumen yang dibutuhkan:<br>1. File NPWP<br>2. File SIUP<br>3. File Sertifikat Sistem Manajemen Mutu/CIQS 2000</p><p>Jika perusahaan ingin uji TA, sama dengan ketentuan di atas, ditambah data;<br>1. Nomor Induk Berusaha (NIB)<br>2. Pelanggan ID (PLG_ID) dari SDPPI<br>3. Jika perusahaan belum memiliki Sertifikat Manajemen Mutu (Contoh Sertifikat ISO), harus melampirkan Sertifikat Manajemen Mutu pabrikan dan Principal Letter</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:50:54',
                'updated_at' => '2021-07-20 10:50:54',
                'category' => '1'
            ],[
                'id' => '3', 
                'question' => 'Jika perusahaan saya pindah ke lokasi yang baru apakah saya perlu mengganti alamat di website TTH? ', 
                'answer' => '<p>Tidak perlu. Karena alamat yang dicantumkan di data perusahaan adalah alamat yang tertera pada NPWP.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:52:04',
                'updated_at' => '2021-07-20 10:52:04',
                'category' => '1'
            ],[
                'id' => '4', 
                'question' => 'Bagaimana cara mengganti data-data perusahaan?', 
                'answer' => '<figure class="table"><table><tbody><tr><td>1. Pelanggan login ke Website TelkomTestHouse</td></tr><tr><td>2. Pada Profil Pelanggan pilih menu Company</td></tr><tr><td>3. Pelanggan mengisi dan atau mengupload data terbaru</td></tr><tr><td>4. Pelanggan menunggu Admin untuk verifikasi data maksimal 1x 24 jam</td></tr><tr><td>5. Admin verifikasi data</td></tr><tr><td>6. Data perusahaan akan berubah</td></tr></tbody></table></figure>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:52:35',
                'updated_at' => '2021-07-20 10:52:35',
                'category' => '1'
            ],[
                'id' => '5', 
                'question' => 'Apakah sertifikat uji mutu bisa memakai sertifikat SNI? ', 
                'answer' => '<p>Sertifikat SNI tidak dapat digunakan menggantikan dokumen sertifikat manajemen mutu perusahaan. Karena SNI mengeluarkan sertifikat untuk produk yang dihasilkan, bukan untuk sistem manajemen perusahaan.&nbsp;</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:53:23',
                'updated_at' => '2021-07-20 10:53:23',
                'category' => '1'
            ],[
                'id' => '6', 
                'question' => 'Apakah perbedaan dari pengujian QA, VT, dan TA?', 
                'answer' => '<figure class="table"><table><tbody><tr><td><strong>Quality Assurance (QA)</strong> merupakan pengujian perangkat telekomunikasi dengan menggunakan referensi Spesifikasi Telekomunikasi (STEL). Perangkat yang lulus uji akan mendapatkan sertifikat QA.</td></tr><tr><td><strong>Type Approval (TA) </strong>merupakan pengujian yang menggunakan referensi sesuai yang dipersyaratkan oleh SDPPI Kominfo. Rekapitulasi Hasil Uji TA akan digunakan untuk pengajuan sertifikasi ke SDPPI Kominfo.</td></tr><tr><td><strong>Voluntary Test (VT)</strong> merupakan pengujian berdasarkan permintaan dari internal Telkom Group.</td></tr></tbody></table></figure>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:53:56',
                'updated_at' => '2021-07-20 10:53:56',
                'category' => '2'
            ],[
                'id' => '7', 
                'question' => 'Bagaimana apabila masa berlaku sertifikat QA sudah habis?', 
                'answer' => '<p>Pelanggan harus memperbaharui sertifikat QA dengan mengajukan kembali uji QA perangkat tersebut.</p>', 
                'is_active' => '0', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:54:22',
                'updated_at' => '2021-07-20 10:54:22',
                'category' => '2'
            ],[
                'id' => '8', 
                'question' => 'Apa itu referensi uji?', 
                'answer' => '<p>Referensi uji adalah standar acuan yang digunakan untuk menguji perangkat telekomunikasi.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:54:46',
                'updated_at' => '2021-07-20 10:54:46',
                'category' => '2'
            ],[
                'id' => '9', 
                'question' => 'Berapa biaya yang dikenakan untuk penggunaan layanan Telkom Test House?', 
                'answer' => '<p><strong>BIAYA PENGUJIAN</strong></p><figure class="table"><table><tbody><tr><td>1. Dapat dilihat pada menu <strong>Pengujian</strong> -&gt; <strong>Tarif</strong></td></tr><tr><td>2. Pelanggan dapat melihat biaya yang diperlukan untuk setiap pengujian berdasarkan perangkat yang ingin diujikan serta pengujian yang ingin dilakukan.</td></tr><tr><td>3. Biaya pengujian dikenakan untuk satu kali uji per tipe perangkat</td></tr></tbody></table></figure><p>&nbsp;</p><p><strong>BIAYA DOKUMEN STEL</strong></p><figure class="table"><table><tbody><tr><td>1. Dapat dilihat pada menu <strong>STEL</strong> -&gt; <strong>Refrens</strong>i -&gt; <strong>STEL</strong></td></tr><tr><td>2. Pelanggan dapat mengecek biaya dokumen STEL sesuai dengan dokumen yang dibeli untuk dijadikan refrensi uji</td></tr></tbody></table></figure>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:58:05',
                'updated_at' => '2021-07-20 10:58:05',
                'category' => '2'
            ],[
                'id' => '10', 
                'question' => 'Bagaimana apabila saya sudah membeli Dokumen STEL pada periode sebelumnya dan dokumen STEL tersebut tidak terlampir pada pilihan refrensi uji?', 
                'answer' => '<p>1. Pelanggan mengirimkan bukti pembelian STEL berupa bukti pembayaran dan atau cover depan dokumen STEL dengan watermark nama perusahaan.<br>2. Admin akan memverifikasi bukti pembelian STEL.&nbsp;<br>3. Apabila bukti pembelian STEL lulus verifikasi, Admin akan memproses penambahan STEL ke dalam akun perusahaan.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:59:06',
                'updated_at' => '2021-07-20 10:59:06',
                'category' => '2'
            ],[
                'id' => '11', 
                'question' => 'Apakah saya perlu membeli kembali Dokumen STEL dengan versi terbaru apabila saya sudah membeli dokumen STEL pada periode sebelumnya? ', 
                'answer' => '<p>1. Jika tanggal pembelian STEL kurang dari 1 tahun tanggal penerbitan STEL terbaru maka pelanggan akan mendapatkan STEL terbaru secara gratis.<br>2. Jika melebihi 1 tahun pelanggan harus membeli kembali.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 10:59:23',
                'updated_at' => '2021-07-20 10:59:23',
                'category' => '2'
            ],[
                'id' => '12', 
                'question' => 'Bagaimana cara melakukan pembelian STEL dan melakukan pendaftaran pengujian?', 
                'answer' => '<p><strong>PEMBELIAN STEL</strong></p><figure class="table"><table><tbody><tr><td>1. Dapat dilakukan pada menu <strong>STEL</strong> -&gt; <strong>Beli STEL</strong></td></tr><tr><td>2. Langkah-langkah <strong>pembelian STEL</strong> dapat diakses pada link:&nbsp;<br><a href="https://bit.ly/PembelianDokumenSTEL"><strong>bit.ly/PembelianDokumenSTEL</strong></a></td></tr></tbody></table></figure><p><strong>PENDAFTARAN PENGUJIAN</strong></p><figure class="table"><table><tbody><tr><td>1. Dapat dilakukan pada menu <strong>Proses</strong> -&gt; <strong>Memilih pengujian yang diinginkan (QA, TA, VT)</strong></td></tr><tr><td>2. Langkah-langkah<strong> pendaftaran</strong> <strong>pengujian QA</strong> dapat diakses pada link: <a href="https://youtu.be/4sL5-d9yxl8"><strong>bit.ly/DaftarPengujianQA</strong></a></td></tr><tr><td>3. Langkah-langkah <strong>pendaftaran</strong> <strong>pengujian TA</strong> dapat diakses pada link: <a href="http://bit.ly/DaftarPengujiaTA"><strong>bit.ly/DaftarPengujiaTA</strong></a></td></tr><tr><td>4. Langkah-langkah <strong>pendaftaran</strong> <strong>pengujian VT</strong> dapat diakses pada link:<strong> </strong><a href="http://bit.ly/DaftarPengujianVT"><strong>bit.ly/DaftarPengujianVT</strong></a></td></tr></tbody></table></figure>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:00:14',
                'updated_at' => '2021-07-20 11:00:14',
                'category' => '2'
            ],[
                'id' => '13', 
                'question' => 'Bagaimana cara mengganti pilihan referensi uji setelah pendaftaran pengujian?', 
                'answer' => '<figure class="table"><table><tbody><tr><td>1. Dapat dilakukan sebelum pendaftaran pengujian perangkat disetujui oleh admin pada menu <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; <strong>Ubah/Edit</strong></td></tr><tr><td>2. Jika pada button status pengujian sudah berada pada uji fungsi, maka pelanggan harus menghubungi Admin untuk mengubah status pengujian pada registrasi dan pelanggan dapat melakukan proses diatas</td></tr><tr><td>3. Jika button status pengujian sudah lewat dari uji fungsi, maka pelanggan harus menghubungi Admin dan mengirimkan surat permohonan penggantian referensi uji. Jika permohonan diterima, Admin akan mengubah refrensi uji. Jika ditolak, proses pengujian perangkat dapat dihentikan, dan pelanggan mengulang dari awal kembali.</td></tr></tbody></table></figure>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:18:37',
                'updated_at' => '2021-07-20 11:18:37',
                'category' => '2'
            ],[
                'id' => '14', 
                'question' => 'Bagaimana cara mendapatkan dokumen STEL? ', 
                'answer' => '<p>Dokumen STEL dapat didownload pada menu <strong>STEL</strong> -&gt; Pilih <strong>STEL</strong> -&gt; <strong>Rincian</strong></p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:18:58',
                'updated_at' => '2021-07-20 11:18:58',
                'category' => '2'
            ],[
                'id' => '15', 
                'question' => ' Bagaimana cara mengetahui STEL yang akan digunakan sebagai refrensi untuk pengujian QA? ', 
                'answer' => '<p>Untuk mengetahui STEL yang digunakan, pelangga dapat mengunjungi link STEL dapat disesuaikan dengan perangkat yang akan diuji, untuk daftar STEL dapat dilihat pada link berikut: <a href="http://bit.ly/DaftarSTELAkiff"><strong>bit.ly/DaftarSTELAkiff</strong></a></p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:19:14',
                'updated_at' => '2021-07-20 11:19:14',
                'category' => '2'
            ],[
                'id' => '16', 
                'question' => 'Bagaimana cara menginput tanggal Uji Fungsi (UF) setelah saya berhasil melakukan pendaftaran pengujian? ', 
                'answer' => '<p>Dapat dilakukan pada menu <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; <strong>Pilih No. Registrasi</strong> -&gt;<strong> Uji Fungsi -&gt; Input Tanggal. </strong>Untuk mengetahui balasan tanggal Uji Fungsi (UF) yang diusulkan, pelanggan dapat melakukan pengecekan notifikasi melalui website TTH.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:19:33',
                'updated_at' => '2021-07-20 11:19:33',
                'category' => '3'
            ],[
                'id' => '17', 
                'question' => 'Bagaimana cara mengubah tanggal Uji Fungsi (UF) yang telah diusulkan sebelumnya?', 
                'answer' => '<p>1. Jika pelanggan sudah input tanggal UF dan Test Engineer blm membalas, pelanggan dapat mengubah tanggal UF pada menu <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; pilih <strong>No Registrasi</strong> -&gt; pilih <strong>Uji Fungsi</strong> -&gt; ubah tgl nya<br>2. Jika Test Engineer sudah setuju dengan tanggal UF, pelanggan wajib memberikan konfirmasi dan alasan perlu mengubah tanggal UF. Selanjutnya, Admin akan berkordinasi dengan Test Engineer untuk mengupayakan perubahan tanggal UF.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:19:49',
                'updated_at' => '2021-07-20 11:19:49',
                'category' => '3'
            ],[
                'id' => '18', 
                'question' => 'Bagaimana cara melihat hasil Uji Fungsi (UF)?', 
                'answer' => '<p>Pelanggan dapat mengunjungi menu <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; pilih <strong>Rincian</strong> dari pendaftaran pengujian yang dilakukan Uji Fungsi -&gt; klik Laporan Uji Fungsi.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:20:05',
                'updated_at' => '2021-07-20 11:20:05',
                'category' => '3'
            ],[
                'id' => '19', 
                'question' => 'Apakah saya datang untuk Uji Fungsi (UF) sesuai dengan tanggal yang diinputkan?', 
                'answer' => '<p>Setelah input tanggal UF, pelanggan menunggu balasan dari Test Engineer terlebih dulu. Terdapat 3 jenis balasan yang diberikan oleh Test Engineer, antara lain:<br>1. Test Engineer menyetujui tanggal yang diajukan pelanggan. Pelanggan diwajibkan untuk datang pada tanggal tersebut dengan membawa sampel uji dan perlengkapan yang dibutuhkan.<br>2. Test Engineer menolak tanggal yang diajukan pelanggan. Pelanggan harus mengajukan kembali tanggal UF.<br>3. Setelah pelanggan mengajukan tanggal UF pada langkah 2, Test Engineer dapat menyetujui atau mengajukan tanggal lain yang otomatis menjadi tanggal UF dilakukan.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:20:20',
                'updated_at' => '2021-07-20 11:20:20',
                'category' => '3'
            ],[
                'id' => '20', 
                'question' => 'Bagaimana langkah selanjutnya jika  hasil Uji Fungsi (UF) tidak memenuhi?', 
                'answer' => '<p>1. Jika baru satu kali hasil UF tidak memenuhi, maka pelanggan dapat mengajukan kembali UF seperti biasa.<br>2. Jika sudah dua kali hasil UF tidak memenuhi maka pelanggan harus menunggu 2 bulan untuk mengajukan kembali pendaftaran ulang pengujian perangkat tersebut.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:20:36',
                'updated_at' => '2021-07-20 11:20:36',
                'category' => '3'
            ],[
                'id' => '21', 
                'question' => 'Apa saja yang diperlukan agar Uji Fungsi (UF) dapat berjalan dengan baik?', 
                'answer' => '<p>1. Pelanggan datang pada tanggal yang telah disepakati<br>2. Pelanggan sudah mempelajari ketentuan perangkat uji dengan STEL atau refrensi yang digunakan<br>3. Menyertakan Test Engineer (TE) atau perwakilan yang paham mengenai perangkat uji<br>4. Membawa dua buah sampel perangkat uji atau sesuai ketentuan dengan merek dan kapasitas yang sama<br>5. Membawa Data Sheet sesuai perangkat uji</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:20:51',
                'updated_at' => '2021-07-20 11:20:51',
                'category' => '3'
            ],[
                'id' => '22', 
                'question' => 'Bagaimana cara untuk melihat SPB?', 
                'answer' => '<p>Dapat dilihat pada menu <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; pilih <strong>Rincian</strong></p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:21:15',
                'updated_at' => '2021-07-20 11:21:15',
                'category' => '4'
            ],[
                'id' => '23', 
                'question' => 'Berapa lama masa berlaku SPB?', 
                'answer' => '<p>Masa berlaku SPB adalah 14 (empat belas) hari sejak tanggal terbit SPB.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:21:30',
                'updated_at' => '2021-07-20 11:21:30',
                'category' => '4'
            ],[
                'id' => '24', 
                'question' => 'Bank apa saja yang dapat digunakan untuk melakukan pembayaran melalui Virtual Account?', 
                'answer' => '<p>Saat ini pembayaran dapat dilakukan dengan menggunakan Virtual Account dari Bank Mandiri, Bank BRI, Bank BNI, Bank Permata, Bank CIMB, Bank Maybank, Bank Danamon</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:21:47',
                'updated_at' => '2021-07-20 11:21:47',
                'category' => '4'
            ],[
                'id' => '25', 
                'question' => 'Bagaimana cara untuk memperoleh nomor Virtual Account?', 
                'answer' => '<p>Virtual Account dapat diperoleh pada website TTH dengan dua cara berikut ini.<br><br>Melalui website:<br>1. Pilih "Pengujian", kemudian pilih "Status Pengujian"<br>2. Pada pengujian yang ingin dibayarkan pilih "Proses Pembayaran"<br>3. Pada halaman Konfirmasi Pembayaran, pilih VA Bank yang ingin digunakan<br>4. Klik "Konfirmasi Pembayaran" untuk mendapatkan nomor VA<br>5. Nomor VA sudah tersedia. Panduan pembayan VA akan dikirimkan melalui email user yang terdaftar di TTH.<br><br>Melalui link yang ada di e-mail:<br>1. Klik link pada email pemberitahuan penerbitan SPB<br>2. Pada halaman Konfirmasi Pembayaran, pilih VA Bank yang ingin digunakan<br>3. Klik "Konfirmasi Pembayaran" untuk mendapatkan nomor VA<br>4. Nomor VA sudah tersedia. Panduan pembayan VA akan dikirimkan melalui email user yang terdaftar di TTH.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:22:01',
                'updated_at' => '2021-07-20 11:22:01',
                'category' => '4'
            ],[
                'id' => '26', 
                'question' => 'Bagaimana cara pembayaran melalui Virtual Account?', 
                'answer' => '<figure class="table"><table><tbody><tr><td>Pembayaran virtual account dapat dilakukan dengan cara sebagai berikut:<br>&nbsp;</td></tr><tr><td>• Bank Mandiri: <a href="http://bit.ly/PembayaranBankMandiri"><strong>bit.ly/PembayaranBankMandiri</strong></a></td></tr><tr><td>• Bank BRI: <a href="https://drive.google.com/file/d/19nsSAVr8u94_Lcdvgb9gi-kPVqBcswnU/view"><strong>bit.ly/PembayaranBankBRI</strong></a></td></tr><tr><td>• Bank BNI: <a href="https://drive.google.com/file/d/1tU0dsiOgdJDxw-RezadKMk63y9WBfh5V/view"><strong>bit.ly/PembayaranBankBNI</strong></a></td></tr><tr><td>• Bank Permata: <a href="https://drive.google.com/file/d/15szKSbhOXhktoznBUGJVYc5mEF8JGmNC/view"><strong>bit.ly/PembayaranBankPermata</strong></a></td></tr><tr><td>• Bank CIMB Niaga: <a href="https://drive.google.com/file/d/1BmbV_S4xRFp6mE3YtjhkhBpoQFquAqaK/view"><strong>bit.ly/PembayaranBankCIMBNiaga</strong></a></td></tr><tr><td>• Bank Maybank:<strong> </strong><a href="https://drive.google.com/file/d/1rBf3Js-4o6g8BISKkP9-QHvOjC8EC7RY/view"><strong>bit.ly/PembayaranBankMaybank</strong></a></td></tr><tr><td>• Bank Danamon: <a href="https://drive.google.com/file/d/1u7JhRnobsf_F1Nqcf3VP6EKdTkzXOJZk/view"><strong>bit.ly/PembayaraBankDanamon</strong></a></td></tr></tbody></table></figure>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:22:27',
                'updated_at' => '2021-07-20 11:22:27',
                'category' => '4'
            ],[
                'id' => '27', 
                'question' => 'Bagaimana jika pada saat ini bank perusahaan saya belum diakomodasi di pembayaran Virtual Account ini?', 
                'answer' => '<p>Pelanggan tetap dapat melakukan pembayaran dengan memilih salah satu VA Bank yang tersedia dan dapat melakukan pembayan melalui teller bank terpilih. Pelanggan diwajibkan untuk melakukan pembayaran VA sesuai dengan Bank yang dipilih agar transaksi tidak ditolak.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:22:46',
                'updated_at' => '2021-07-20 11:22:46',
                'category' => '4'
            ],[
                'id' => '28', 
                'question' => 'Bagaimana cara mendapatkan faktur pajak dan invoice dari pembayaran untuk pembelian STEL atau pengujian Perangkat?', 
                'answer' => '<p>Untuk saat ini, invoice dan e-faktur secara otomatis sudah tersedia di website TTH maksimal H+7 dari tanggal pembayaran.&nbsp;<br>1. Untuk STEL, dapat mengunjungi <strong>STEL -&gt; Riwayat Pembelian -&gt; </strong>pilih <strong>Rincian </strong>dari STEL yang dibeli.<br>2. Untuk Pengujian, dapat mengunjungi <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; pilih <strong>Rincian </strong>dari pengujian yang dibayar.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:23:00',
                'updated_at' => '2021-07-20 11:23:00',
                'category' => '4'
            ],[
                'id' => '29', 
                'question' => 'Bagaimana cara mengetahui nomor SPK pengujian perangkat saya?', 
                'answer' => '<p>Nomor SPK dapat dilihat pada bagian Detail Pengujian ketika status pembayaran pelanggan sudah berubah menjadi hijau. Dapat dilihat pada Website Telkom Test House pada menu <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; <strong>Rincian</strong></p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:23:25',
                'updated_at' => '2021-07-20 11:23:25',
                'category' => '5'
            ],[
                'id' => '30', 
                'question' => 'Bagaimana progres pengujian saya?', 
                'answer' => '<p>Pelanggan dapat menghubungi Customer Service TTH pada nomor (+62) 812-2483-7500 untuk mengetahui progress pengujian. Apabila pengujian telah selesai maka Customer Service TTH akan menghubungi pelanggan</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:24:15',
                'updated_at' => '2021-07-20 11:24:15',
                'category' => '5'
            ],[
                'id' => '31', 
                'question' => 'Apa saja layanan dari Telkom Test House?', 
                'answer' => '<figure class="table"><table><tbody><tr><td>1. Pengujian perangkat telekomunikasi (QA, TA, dan VT)</td></tr><tr><td>2. Penjualan STEL</td></tr></tbody></table></figure>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:24:49',
                'updated_at' => '2021-07-20 11:24:49',
                'category' => '6'
            ],[
                'id' => '32', 
                'question' => 'Apakah pengujian di TTH sudah mendapatkan akreditasi?', 
                'answer' => '<p>Telkom Test House sudah mendapatkan akreditasi sebagai lab pengujian dari Komite Akreditasi Nasional (KAN) mengacu pada ISO/IEC 17025:2017. Telkom Test House sudah ditetapkan sebagai Balai Uji Dalam Negeri oleh Direktorat Jenderal Sumber Daya dan Perangkat Pos dan Informatika (SDPPI).</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:25:04',
                'updated_at' => '2021-07-20 11:25:04',
                'category' => '6'
            ],[
                'id' => '33', 
                'question' => ' Pengujian apa sajakah yang telah terakreditasi oleh KAN?', 
                'answer' => '<p>Pengujian yang terakreditasi KAN dapat dilihat pada link berikut: <a href="http://bit.ly/AkreditasiKAN"><strong>bit.ly/AkreditasiKAN</strong></a></p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:25:18',
                'updated_at' => '2021-07-20 11:25:18',
                'category' => '6'
            ],[
                'id' => '34', 
                'question' => 'Mengapa terdapat pengujian yang tidak terakreditasi KAN?', 
                'answer' => '<p>Karena alat ukur yang digunakan serta keseluruhan environtment tidak memenuhi Akreditasi minimum dari KAN, yaitu sebesar 60%</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:25:35',
                'updated_at' => '2021-07-20 11:25:35',
                'category' => '6'
            ],[
                'id' => '35', 
                'question' => 'Apakah bisa melakukan kalibrasi alat ukur?', 
                'answer' => '<p>Telkom Test House belum melayani kalibrasi alat ukur dari eksternal.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:25:51',
                'updated_at' => '2021-07-20 11:25:51',
                'category' => '6'
            ],[
                'id' => '36', 
                'question' => 'Apakah yang perlu dilakukan sebelum mengambil Rekapitulasi Hasil Uji (RHU), Laporan Hasil Uji (LHU), sertifikat, dan sampel uji perangkat?', 
                'answer' => '<p>Pelanggan harus menjadwalkan waktu pengambilan melalui Customer Service TTH. Pelanggan harus menyiapkan surat Rapid Test yang masih berlaku pada hari pengambilan. Pada hari pengambilan pelanggan mengambil sampel uji perangkat terlebih dahulu untuk mendapatkan Bukti Penerimaan dan Pengeluaran Perangkat Uji (BPPPU) bagi yang melakukan uji di lab.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:26:18',
                'updated_at' => '2021-07-20 11:26:18',
                'category' => '7'
            ],[
                'id' => '37', 
                'question' => 'Apa saja yang perlu dibawa pada saat pengambilan Rekapitulasi Hasil Uji (RHU), Laporan Hasil Uji (LHU), dan sertifikat?', 
                'answer' => '<p>Pelanggan hanya perlu membawa Bukti Penerimaan dan Pengeluaran Perangkat Uji (BPPPU) bagi yang melakukan uji di lab dan menghubungi resepsionis di Gedung Digital Business untuk pengambilan RHU, LHU dan sertifikat.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:26:32',
                'updated_at' => '2021-07-20 11:26:32',
                'category' => '7'
            ],[
                'id' => '38', 
                'question' => 'Apakah Rekapitulasi Hasil Uji (RHU), Laporan Hasil Uji (LHU), dan sertifikat dapat dikirimkan melalui ekspedisi?', 
                'answer' => '<p>Pengambilan RHU, LHU dan sertifikat tidak dapat dikirimkan melalui ekspedisi karena dibutuhkannya tanda terima sebagai bukti pengambilan.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:26:47',
                'updated_at' => '2021-07-20 11:26:47',
                'category' => '7'
            ],[
                'id' => '39', 
                'question' => 'Dimana saya dapat melihat Tanda Terima Hasil Pengujian (TTHP), Rekapitulasi Hasil Uji (RHU), Laporan Hasil Uji (LHU), dan sertifikat dalam bentuk softfile?', 
                'answer' => '<p>Softfile Tanda Terima Hasil Pengujian, RHU, LHU, dan sertifikat dapat dilihat di Website Telkom Test House pada menu <strong>Pengujian</strong> -&gt; <strong>Status Pengujian</strong> -&gt; <strong>Rincian</strong></p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:27:02',
                'updated_at' => '2021-07-20 11:27:02',
                'category' => '7'
            ],[
                'id' => '40', 
                'question' => 'Bagaimana apabila pada Rekapitulasi Hasil Uji (RHU), Laporan Hasil Uji (LHU), dan sertifikat terdapat data yang salah?', 
                'answer' => '<p>Terdapat 2 kasus pada proses pengambilan RHU, LHU, dan sertifikat, antara lain:<br>1.Apabila terdapat data yang salah pada saat pengambilan RHU, LHU, dan sertifikat.<br>Pelanggan dapat langsung mengajukan pengaduan mengenai perbaikan pada RHU, LHU maupun sertifikat secara langsung pada saat pengambilan pelaporan, kemudian Customer Service akan menghubungi kembali jika perbaikan pada RHU, LHU atau sertifikat telah selesai dilakukan perbaikan.<br>2.Apabila terdapat data yang salah pada RHU, LHU, dan sertifikat yang sudah diambil.<br>Pelanggan dapat menghubungi Customer Service untuk membuat permohonan perbaikan dengan menjelaskan perubahan yang ingin dilakukan, kemudian pelanggan akan dihubungi kembali jika perbaikan pada RHU, LHU maupun sertifikat telah selesai dilakukan.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:27:22',
                'updated_at' => '2021-07-20 11:27:22',
                'category' => '7'
            ],[
                'id' => '41', 
                'question' => 'Berapa lama masa berlaku dari sertifikat QA?', 
                'answer' => '<p>Masa berlaku dari sertifikat QA selama 3 tahun dan dapat diperpanjang apabila dibutuhkan. Pelanggan harus memperbaharui sertifikat QA dengan mengajukan kembali uji QA perangkat tersebut.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:27:35',
                'updated_at' => '2021-07-20 11:27:35',
                'category' => '7'
            ],[
                'id' => '42', 
                'question' => 'Mengapa pada sertfikat terdapat periode waktu yang kurang dari 3 tahun (seperti 1 tahun dan 6 bulan)', 
                'answer' => '<p>Karena masih adanya persyaratan yang masih dikembangkan dan akan ditinjau kembali selama periode waktu yang tertera pada sertifikat. Hal ini juga sesuai dengan ketentuan yang ditetapkan oleh pihak Internal Telkom.</p>', 
                'is_active' => '1', 
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => '2021-07-20 11:27:49',
                'updated_at' => '2021-07-20 11:27:49',
                'category' => '7'
            ]
        ]);
    }
}
