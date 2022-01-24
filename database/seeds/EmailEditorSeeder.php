<?php

use Illuminate\Database\Seeder;

class EmailEditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_editors')->insert([
            [
                'id' => '1', 
                'name' => 'Step Registrasi', 
                'subject' => 'ACC Registrasi', 
                'dir_name' => 'emails.registrasi', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Dokumen pengajuan Uji @exam_type (@exam_type_desc) Anda telah diperiksa oleh staff User Relation Telkom Test House (TTH). Proses selanjutnya adalah Uji Fungsi (pre-test) perangkat di Lab TTH dengan alamat sbb:&nbsp;<br><br>Lab TTH&nbsp;<br>PT. Telkom Indonesia, Tbk&nbsp;<br>Jl. Gegerkalong Hilir No. 47 Sukasari Bandung&nbsp;<br>45012&nbsp;<br><br>Mohon untuk melakukan <strong>koordinasi terlebih dahulu</strong> terkait slot waktu uji fungsi dan kelengkapan perangkat dengan menghubungi staff User Relation di <i><strong>0812 2483 7500</strong></i> atau dengan mengirimkan email ke cstth@telkom.co.id</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '2', 
                'name' => 'Admin Revisi', 
                'subject' => 'Revisi Data Permohonan Uji', 
                'dir_name' => 'emails.revisi', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Dokumen pengajuan Uji @exam_type (@exam_type_desc) Anda telah diperiksa oleh staff User Relation Telkom Test House (TTH). Namun, ada beberapa isian yang harus kami revisi. Revisi yang kami lakukan adalah sbb:&nbsp;<br><br><strong>1. Nama Perangkat</strong>&nbsp;<br>&nbsp;&nbsp;&nbsp; @perangkat1 menjadi @perangkat2&nbsp;<br><strong>2. Merk Perangkat</strong>&nbsp;<br>&nbsp;&nbsp;&nbsp; @merk_perangkat1 menjadi @merk_perangkat2&nbsp;<br><strong>3. Kapasitas/Kecepatan Perangkat</strong>&nbsp;<br>&nbsp;&nbsp;&nbsp; @kapasitas_perangkat1 menjadi @kapasitas_perangkat2&nbsp;<br><strong>4. Negara Pembuat</strong>&nbsp;<br>&nbsp;&nbsp;&nbsp; @pembuat_perangkat1 menjadi @pembuat_perangkat2&nbsp;<br><strong>5. Model Perangkat</strong>&nbsp;<br>&nbsp;&nbsp;&nbsp; @model_perangkat1 menjadi @model_perangkat2&nbsp;<br><strong>6. Referensi Uji</strong>&nbsp;<br>&nbsp;&nbsp;&nbsp; @ref_perangkat1 menjadi @ref_perangkat2&nbsp;<br><strong>7. Nomor Serial Perangkat</strong>&nbsp;<br>&nbsp;&nbsp;&nbsp; @sn_perangkat1 menjadi @sn_perangkat2&nbsp;<br><br>Mohon untuk melakukan <strong>koordinasi terlebih dahulu</strong> terkait revisi perangkat dengan menghubungi staff User Relation di <i><strong>0812 2483 7500</strong></i> atau dengan mengirimkan email ke cstth@telkom.co.id.&nbsp;</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '3', 
                'name' => 'Step SPB', 
                'subject' => 'Penerbitan Surat Pemberitahuan Biaya (SPB) untuk @no_registrasi', 
                'dir_name' => 'emails.spb', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Berkenaan dengan pendaftaran uji @exam_type (@exam_type_desc) perangkat Bapak/Ibu yang sudah memenuhi proses uji fungsi, maka SPB dengan nomor <strong>@spb_number telah terbit dan dapat mengunduhnya di web </strong><a href="https://www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a> atau lampiran email ini.&nbsp;<br><br>Kami sampaikan juga <strong>pembayaran SPB</strong> dilakukan melalui <strong>Virtual Account</strong> dengan pilihan sebagai berikut : @payment_method_list</p><p>Silakan klik tautan di bawah ini untuk melakukan proses pembayaran.&nbsp;<br>&nbsp;</p><p><a href="@link">@link</a></p><p>&nbsp;</p><p>Mohon periksa kembali biaya dan ketentuan-ketentuan yang berlaku demi kelancaran proses pembayaran.&nbsp;</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '4', 
                'name' => 'Step SPB (Revisi)', 
                'subject' => 'Revisi Surat Pemberitahuan Biaya (SPB) untuk @no_registrasi', 
                'dir_name' => 'emails.spbRevision', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.</i><br><i>Bapak/Ibu @user_name</i><br><br>&nbsp;</p><p>Kami memohon maaf atas kejadian ini.</p><p>Kami memberitahukan bahwa terjadi perubahan harga biaya uji yang sebelum nya mengacu pada SPB @spb_number, oleh karena itu kami menerbitkan revisi SPB dengan nomor @spbRevisionNumber yang <strong>sudah terbit</strong> dan Bapak/Ibu <strong>dapat mengunduhnya di web </strong><a href="www.telkomtesthouse.co.id"><strong>www.telkomtesthouse.co.id</strong></a> atau lampiran email ini. Mohon untuk <strong>mengabaikan email sebelumnya.</strong></p><p>Silakan klik tautan di bawah ini untuk memilih cara pembayaran yang dikehendaki. <a href="@link">@link</a></p><p>Mohon periksa kembali biaya dan ketentuan-ketentuan yang berlaku demi kelancaran proses pembayaran.</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '5', 
                'name' => 'Step SPB (Reminder)', 
                'subject' => 'Tersisa @sisa_waktu hari lagi untuk membayar SPB @no_spb', 
                'dir_name' => 'emails.reminderSPB', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.</i><br><i>Bapak/Ibu @user_name</i><br><br>&nbsp;</p><p>Kami memberitahukan kembali bahwa Surat Pemberitahuan Biaya (SPB) @spb_number sudah terbit di <a href="www.telkomtesthouse.co.id"><strong>www.telkomtesthouse.co.id</strong></a> dan Bapak/Ibu mempunyai sisa waktu @remainingDay hari untuk pembayaran.</p><p>Pada tanggal: @dueDate&nbsp;<br>Jam: @dueHour</p><p>Metode pembayaran yang telah dipilih adalah @paymentMethod dengan nominal @price @includePPH Apabila Bapak/Ibu tidak melakukan pembayaran hingga tenggat waktu, Virtual Account akan otomatis tidak berlaku.</p><p>Mohon ikuti ketentuan-ketentuan yang berlaku demi kelancaran proses pembayaran.</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '6', 
                'name' => 'Step Pembayaran', 
                'subject' => 'ACC Pembayaran', 
                'dir_name' => 'emails.pembayaran', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Terima kasih telah melakukan pembayaran pengujian sesuai dengan SPB (Surat Pemberitahuan Biaya). Proses penerbitan SPK (Surat Perintah Kerja) untuk test engineer akan segera kami proses.&nbsp;<br>Terlampir kami sampaikan kuitansi atau faktur pajak bukti pembayaran pengujian.&nbsp;</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '7', 
                'name' => 'Step Sertifikat', 
                'subject' => 'Penerbitan Sertifikat QA [@device_name | @device_mark | @device_model | @device_capacity]', 
                'dir_name' => 'emails.sertifikat', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Permohonan uji perangkat anda di Telkom Test House <strong>sudah selesai</strong>. @text1 mengunduh Laporan Hasil Uji (LHU) dan Sertifikat di <a href="www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a> @text2 dengan cara <i><strong>masuk/login</strong></i> terlebih dahulu, lalu pilih menu <i><strong>pengujian/testing &gt; status pengujian/progress</strong></i> dan tekan tombol <strong>unduh laporan &amp; unduh sertifikat/</strong><i><strong>download report &amp; download certificate</strong></i> yang terletak <strong>di kanan bawah</strong> dari permohonan uji anda.&nbsp;<br><br>Terimakasih atas kerjasama anda.&nbsp;<br>Untuk info lebih lanjut silakan hubungi kami di <strong>+62 812 2483 7500</strong></p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '8', 
                'name' => 'Step Not Completed', 
                'subject' => 'Konfirmasi Pembatalan Pengujian', 
                'dir_name' => 'emails.fail', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Dokumen pengajuan Uji @exam_type (@exam_type_desc) Anda telah diperiksa oleh staff User Relation Telkom Test House (TTH). Namun, pengujian terhenti pada tahap @tahap, <strong>dengan alasan @keterangan.</strong>&nbsp;<br><br>Mohon untuk melakukan <strong>koordinasi terlebih dahulu</strong> dengan menghubungi staff User Relation di <i><strong>0812 2483 7500</strong></i> atau dengan mengirimkan email ke cstth@telkom.co.id.</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '9', 
                'name' => 'Akun Baru', 
                'subject' => 'Permintaan Aktivasi Data Akun Baru', 
                'dir_name' => 'emails.registrasiCust', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i><strong>== PEMBERITAHUAN ==</strong>&nbsp;</i><br><br>&nbsp;</p><p>Kastamer atas nama @user_name dan email @user_email, mengajukan permohonan aktivasi akun baru.&nbsp;<br><br>Silakan lakukan konfirmasi terhadap nama di atas. Data selengkapnya, dapat dilihat pada aplikasi.&nbsp;</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '10', 
                'name' => 'Perusahaan dan Akun Baru', 
                'subject' => 'Permintaan Aktivasi Data Perusahaan dan Akun Baru', 
                'dir_name' => 'emails.registrasiCustCompany', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i><strong>== PEMBERITAHUAN ==</strong>&nbsp;</i><br><br>&nbsp;</p><p>Kastamer atas nama @user_name dan email @user_email, mengajukan permohonan aktivasi akun baru beserta data perusahaan baru <strong>(belum terdaftar pada aplikasi)</strong>.&nbsp;<br>Perusahaan bernama @comp_name, beralamat di @comp_address. Dengan nomor telepon @comp_phone dan email @comp_email&nbsp;<br><br>Silakan lakukan konfirmasi terhadap Perusahaan dan Kastamer di atas. Data selengkapnya, dapat dilihat pada aplikasi.&nbsp;<br>*Notes: Lakukan Aktivasi terhadap Perusahaan terlebih dahulu, lalu lakukan aktivasi terhadap akun kastamer.&nbsp;</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '11', 
                'name' => 'Permohonan Edit Perusahaan', 
                'subject' => 'Permintaan Edit Data Perusahaan', 
                'dir_name' => 'emails.editCompany', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i><strong>== PEMBERITAHUAN ==</strong>&nbsp;</i><br><br>&nbsp;</p><p>Kastamer atas nama @user_name dan email @user_email, mengajukan permohonan edit Data Perusahaan sebagai berikut:&nbsp;<br><br><strong>@desc</strong>&nbsp;<br><br>Silakan lakukan konfirmasi terhadap nama di atas. Permintaan data perusahaan yang diedit, dapat dilihat selengkapnya pada aplikasi.&nbsp;</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '12', 
                'name' => 'Update Referensi Uji', 
                'subject' => 'Update Referensi Uji', 
                'dir_name' => 'emails.updateSTEL', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Dokumen @stel_code telah diperbarui.&nbsp;<br>Silakan klik tautan di bawah ini untuk informasi lebih lanjut.&nbsp;</p><p><a href="@link">@link</a> pada tab (Paid)</p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '13', 
                'name' => 'Step Sidang QA', 
                'subject' => 'Pemberitahuan Hasil Pengujian Perangkat [@device_name | @device_mark | @device_model | @device_capacity]', 
                'dir_name' => 'emails.qa', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Kami Laboratorium Telkom Test House memberitahukan kepada @company_name bahwa pengujian perangkat:<br>Nama Perangkat: @device_name&nbsp;<br>Merek: @device_mark<br>Jenis/Tipe: @device_model<br>Kapasitas: @device_capacity</p><p>Telah&nbsp;menyelesaikan serangkaian proses uji menggunakan referensi @test_reference, dan kami menyatakan bahwa perangkat tersebut @qa_passed.</p><p>Anda bisa mengunduh Laporan Hasil Uji (LHU)@cert1 di <a href="www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a> dengan cara masuk/login terlebih dahulu, lalu pilih menu pengujian/testing &gt; status pengujian/progress dan tekan tombol unduh laporan/download report@cert2 yang terletak di kanan bawah dari permohonan uji.<br>&nbsp;<br>Terimakasih atas kerjasama anda.&nbsp;<br>Untuk info lebih lanjut silakan hubungi kami di <strong>+62 812 2483 7500</strong></p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '14', 
                'name' => 'Step Sidang QA (Pending)', 
                'subject' => 'Pemberitahuan Hasil Pengujian Perangkat [@device_name | @device_mark | @device_model | @device_capacity]', 
                'dir_name' => 'emails.qaPending', 
                'logo' => 'images/TTHMain.png',
                'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Kami Laboratorium Telkom Test House memberitahukan kepada @company_name bahwa pengujian perangkat:<br>Nama Perangkat: @device_name&nbsp;<br>Merek: @device_mark<br>Jenis/Tipe: @device_model<br>Kapasitas: @device_capacity</p><p>Telah&nbsp;menyelesaikan serangkaian proses uji menggunakan referensi @test_reference, dan kami menyatakan bahwa hasil perangkat <strong>DITUNDA</strong>@catatan. Hasil keputusan akan ditentukan kembali pada Sidang Komite QA berikutnya.<br>&nbsp;<br>Terimakasih atas kerjasama anda.&nbsp;<br>Untuk info lebih lanjut silakan hubungi kami di <strong>+62 812 2483 7500</strong></p>',
                'signature' => 'Salam hangat,&nbsp;<br>Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstelkomtesthouse@gmail.com.</strong></i>',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]);
    }
}
