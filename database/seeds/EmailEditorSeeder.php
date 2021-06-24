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
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.
                        <br>
                        Bapak/Ibu @user_name
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Dokumen pengajuan Uji @exam_type (@exam_type_desc) Anda telah diperiksa oleh staff User Relation Lab Infrastructure Assurance DDB Telkom. Proses selanjutnya adalah Uji Fungsi (pre-test) perangkat	di Lab IAS dengan alamat sbb:
                        <br><br>
                        Lab Pengujian Infrastructure Assurance DDB
                        <br>
                        PT. Telekomunikasi Indonesia, Tbk
                        <br>
                        Jl. Gegerkalong Hilir No. 47 Sukasari Bandung
                        <br>
                        45012
                        <br><br>
                        Mohon untuk melakukan <strong>koordinasi terlebih dahulu</strong> terkait slot waktu uji fungsi dan kelengkapan perangkat dengan menghubungi staff User Relation di (022) 4571145 atau dengan mengirimkan email ke urelddstelkom@gmail.com .
                        <br><br>
                        Salam hangat,
                        <br>
                        Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.
                        <br><br>
                        ---
                        <br><br>
                    </p>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau <strong>cstelkomtesthouse@gmail.com.</strong>
                    </p>
                </div>
                
                </body>
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '2', 
                'name' => 'Admin Revisi', 
                'subject' => 'Revisi Data Permohonan Uji', 
                'dir_name' => 'emails.revisi', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.
                        <br>
                        Bapak/Ibu @user_name
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Dokumen pengajuan Uji @exam_type (@exam_type_desc) Anda telah diperiksa oleh staff User Relation Lab Infrastructure Assurance DDB Telkom. 
                        Namun, ada beberapa isian yang harus kami revisi. Revisi yang kami lakukan adalah sbb:
                        <br><br>
                        <strong>1. Nama Perangkat</strong>
                        <br>
                            &nbsp;&nbsp;&nbsp; @perangkat1 menjadi @perangkat2
                        <br>
                        <strong>2. Merk Perangkat</strong>
                        <br>
                            &nbsp;&nbsp;&nbsp; @merk_perangkat1 menjadi @merk_perangkat2
                        <br>
                        <strong>3. Kapasitas/Kecepatan Perangkat</strong>
                        <br>
                            &nbsp;&nbsp;&nbsp; @kapasitas_perangkat1 menjadi @kapasitas_perangkat2
                        <br>
                        <strong>4. Negara Pembuat</strong>
                        <br>
                            &nbsp;&nbsp;&nbsp; @pembuat_perangkat1 menjadi @pembuat_perangkat2
                        <br>
                        <strong>5. Model Perangkat</strong>
                        <br>
                            &nbsp;&nbsp;&nbsp; @model_perangkat1 menjadi @model_perangkat2
                        <br>
                        <strong>6. Referensi Uji</strong>
                        <br>
                            &nbsp;&nbsp;&nbsp; @ref_perangkat1 menjadi @ref_perangkat2
                        <br>
                        <strong>7. Nomor Serial Perangkat</strong>
                        <br>
                            &nbsp;&nbsp;&nbsp; @sn_perangkat1 menjadi @sn_perangkat2
                        <br><br>
                        Mohon untuk melakukan <strong>koordinasi terlebih dahulu</strong> terkait revisi perangkat dengan menghubungi staff User Relation di (022) 4571145 atau dengan mengirimkan email ke urelddstelkom@gmail.com .
                        <br><br>
                        Salam hangat,
                        <br>
                        Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.
                        <br><br>
                        ---
                        <br><br>
                    </p>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau <strong>cstelkomtesthouse@gmail.com.</strong>
                    </p>
                </div>
                
                </body>
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '3', 
                'name' => 'Step SPB', 
                'subject' => 'Penerbitan Surat Pemberitahuan Biaya (SPB) untuk [no_registrasi]', 
                'dir_name' => 'emails.spb', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img alt="telkom" style="width:15%;" src="{{ url("images/logo_telkom.png") }}">
                    </div>
                    <h3 style="font-family:Arial; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.
                        <br>
                        Bapak/Ibu @user_name
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Berkenaan dengan pendaftaran uji @exam_type (@exam_type_desc) perangkat Bapak/Ibu yang sudah memenuhi proses uji fungsi, maka SPB dengan nomor <strong>@spb_number telah terbit dan dapat mengunduhnya di web </strong> <a href="https://www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a> atau lampiran email ini.
                        <br><br>
                        Kami sampaikan juga <strong>pembayaran SPB</strong> dilakukan melalui <strong>Virtual Account</strong> dengan pilihan sebagai berikut :
                        @payment_method_list
                    </p>
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Silakan klik tautan di bawah ini untuk melakukan proses pembayaran.
                        <br>
                        <a href="@link"><p style="text-align:center">@link</p></a>
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
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '4', 
                'name' => 'Step SPB (Revisi)', 
                'subject' => 'Revisi Surat Pemberitahuan Biaya (SPB) untuk [no_registrasi]', 
                'dir_name' => 'emails.spbRevision', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                    <img alt="telkom" style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom test house">
                    </div>
                    <h3 style="font-family:Arial; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.<br>
                        Bapak/Ibu @user_name<br><br>
                    </p>
                    
                
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kami memohon maaf atas kejadian ini. 
                    </p>
                
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kami memberitahukan bahwa terjadi perubahan harga biaya uji yang sebelum nya mengacu pada SPB 
                        @spb_number, oleh karena itu kami menerbitkan revisi SPB dengan nomor @spbRevisionNumber yang <strong>sudah terbit</strong> dan
                        Bapak/Ibu <strong>dapat mengunduhnya di web <a href="www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a></strong> atau lampiran email ini.
                        Mohon untuk <strong>mengabaikan email sebelumnya.</strong>
                    </p>
                
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Silakan klik tautan di bawah ini untuk memilih cara pembayaran yang dikehendaki.
                        <a href="@link">@link</a><!--#ini#-->
                    </p>
                
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Mohon periksa kembali biaya dan ketentuan-ketentuan yang berlaku demi kelancaran proses pembayaran.
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
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '5', 
                'name' => 'Step SPB (Reminder)', 
                'subject' => 'Tersisa [sisa_waktu] hari lagi untuk membayar SPB [no_spb]', 
                'dir_name' => 'emails.reminderSPB', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                    <img alt="telkom" style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom test house">
                    </div>
                    <h3 style="font-family:Arial; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.<br>
                        Bapak/Ibu @user_name<br><br>
                    </p>
                
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kami memberitahukan kembali bahwa Surat Pemberitahuan Biaya (SPB) @spb_number
                        sudah terbit di <strong><a href="www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a></strong>
                        dan Bapak/Ibu mempunyai sisa waktu @remainingDay hari untuk pembayaran.
                    </p>
                
                    <p style="text-align:center; font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00);">
                        Pada tanggal: @dueDate <br>
                        Jam: @dueHour
                    </p>
                
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Metode pembayaran yang telah dipilih adalah @paymentMethod dengan nominal
                        @price @includePPH
                        Apabila Bapak/Ibu tidak melakukan pembayaran hingga tenggat waktu, Virtual Account akan otomatis tidak berlaku.
                    </p>
                
                    <p style="font-family:Helvetica; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Mohon ikuti ketentuan-ketentuan yang berlaku demi kelancaran proses pembayaran.
                    </p>
                
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
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '6', 
                'name' => 'Step Pembayaran', 
                'subject' => 'ACC Pembayaran', 
                'dir_name' => 'emails.pembayaran', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.
                        <br>
                        Bapak/Ibu @user_name
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Terima kasih telah melakukan pembayaran pengujian sesuai dengan SPB (Surat Pemberitahuan Biaya). Proses penerbitan SPK (Surat Perintah Kerja) untuk test engineer akan segera kami proses.
                        <br>
                        Terlampir kami sampaikan kuitansi atau faktur pajak bukti pembayaran pengujian.
                        <br><br>
                        Salam hangat,
                        <br>
                        Lab Uji IAS PT. Telekomunikasi Indonesia, Tbk.
                        <br><br>
                        ---
                        <br><br>
                    </p>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.88em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau <strong>cstelkomtesthouse@gmail.com.</strong>
                    </p>
                </div>
                
                </body>
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '7', 
                'name' => 'Step Sertifikat', 
                'subject' => 'Penerbitan Sertifikat QA [device_name | device_mark | device_model | device_capacity]', 
                'dir_name' => 'emails.sertifikat', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.
                        <br>
                        Bapak/Ibu @user_name
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Permohonan uji perangkat anda di Telkom Test House <strong>sudah selesai</strong>. @text1 mengunduh Laporan Hasil Uji (LHU) dan Sertifikat di <a href="www.telkomtesthouse.co.id">www.telkomtesthouse.co.id</a> @text2 dengan cara <strong><i>masuk/login</i></strong> terlebih dahulu, lalu pilih menu <strong><i>pengujian/testing > status pengujian/progress</i></strong> dan tekan tombol <strong>unduh laporan & unduh sertifikat/<i>download report & download certificate</i></strong> yang terletak <strong>di kanan bawah</strong> dari permohonan uji anda.
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
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '8', 
                'name' => 'Step Not Completed', 
                'subject' => 'Konfirmasi Pembatalan Pengujian', 
                'dir_name' => 'emails.fail', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kepada Yth.
                        <br>
                        Bapak/Ibu @user_name
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Dokumen pengajuan Uji @exam_type (@exam_type_desc) Anda telah diperiksa oleh staff User Relation Lab Infrastructure Assurance DDB Telkom. 
                        Namun, pengujian terhenti pada tahap @tahap, <strong>dengan alasan @keterangan.</strong>
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
                        Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon & WA) atau <strong>cstelkomtesthouse@gmail.com.</strong>
                    </p>
                </div>
                
                </body>
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '9', 
                'name' => 'Akun Baru', 
                'subject' => 'Permintaan Aktivasi Data Akun Baru', 
                'dir_name' => 'emails.registrasiCust', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        <strong>== PEMBERITAHUAN ==</strong>
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kastamer atas nama @user_name dan email @user_email, mengajukan permohonan aktivasi akun baru.
                        <br><br>
                        Silakan lakukan konfirmasi terhadap nama di atas. Data selengkapnya, dapat dilihat pada aplikasi. 
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
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '10', 
                'name' => 'Perusahaan dan Akun Baru', 
                'subject' => 'Permintaan Aktivasi Data Perusahaan dan Akun Baru', 
                'dir_name' => 'emails.registrasiCustCompany', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        <strong>== PEMBERITAHUAN ==</strong>
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kastamer atas nama @user_name dan email @user_email, mengajukan permohonan aktivasi akun baru beserta data perusahaan baru <strong>(belum terdaftar pada aplikasi)</strong>.
                        <br>
                        Perusahaan bernama @comp_name, beralamat di @comp_address. Dengan nomor telepon @comp_phone dan email @comp_email 
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
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],[
                'id' => '11', 
                'name' => 'Permohonan Edit Perusahaan', 
                'subject' => 'Permintaan Edit Data Perusahaan', 
                'dir_name' => 'emails.editCompany', 
                'content' => '<!doctype html>
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
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Bold.otf");
                    }
                    @font-face{
                        font-family:font-regular;
                        src:url("http://37.72.172.144/ficlip/asset_mail/HVD%20Fonts%20-%20BrandonText-Regular.otf");
                    }
                </style>
                </head>
                <body>
                <div class="header" style="margin-top:2%;margin-bottom:2%;">
                    
                </div>
                <div class="content" style="width:75%;background-color:rgba(255,255,255,1.00);border: 3px #ff3e41 solid;border-radius:15px;position:relative;margin-left:auto;margin-right:auto;padding-left:25px;padding-right:25px;padding-top:5px;padding-bottom:5px;">
                    <div style="text-align:right;">
                        <img style="width:15%;" src="{{ url("images/logo_telkom.png") }}" alt="logo telkom">
                    </div>
                    <h3 style="font-family:Arial, serif; font-size:1.2em; color:rgba(110,110,110,1.00);"></h3>
                    <p style="font-style:italic; font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        <strong>== PEMBERITAHUAN ==</strong>
                        <br><br>
                    </p>
                    <p style="font-family:Helvetica, sans-serif; font-size:0.98em; color:rgba(146,146,146,1.00); margin-top:-7px;">
                        Kastamer atas nama @user_name dan email @user_email, mengajukan permohonan edit Data Perusahaan sebagai berikut:
                        <br><br>
                        <strong>@desc</strong>
                        <br><br>
                        Silakan lakukan konfirmasi terhadap nama di atas. Permintaan data perusahaan yang diedit, dapat dilihat selengkapnya pada aplikasi. 
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
                </html>',
                'signature' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]);
    }
}
