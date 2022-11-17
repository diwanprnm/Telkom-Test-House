<?php

use Illuminate\Database\Seeder;

class AuthentikasiEditorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authentikasi_editor')->truncate();

        DB::table('authentikasi_editor')->insert([
            'id' => 1,
            'name' => 'Sertifikat',
            'dir_name' => 'authentikasi.sertifikat',
            'logo' => 'images/TTHMain.png',
            'content' => '<p><i>Kepada Yth.&nbsp;</i><br><i>Bapak/Ibu @user_name&nbsp;</i><br><br>&nbsp;</p><p>Dokumen pengajuan Uji @exam_type (@exam_type_desc) Anda telah diperiksa oleh staff User Relation Telkom Test House (TTH). Proses selanjutnya adalah Uji Fungsi (pre-test) perangkat di Lab TTH dengan alamat sbb:&nbsp;<br><br>Lab TTH&nbsp;<br>PT. Telkom Indonesia, Tbk&nbsp;<br>Jl. Gegerkalong Hilir No. 47 Sukasari Bandung&nbsp;<br>45012&nbsp;<br><br>Mohon untuk melakukan <strong>koordinasi terlebih dahulu</strong> terkait slot waktu uji fungsi dan kelengkapan perangkat dengan menghubungi staff User Relation di <i><strong>0812 2483 7500</strong></i> atau dengan mengirimkan email ke cstth@telkom.co.id</p>',
            'signature' => '<p>Salam hangat,<br>Telkom Test House - PT. Telekomunikasi Indonesia, Tbk.&nbsp;<br><br>---&nbsp;<br><br>&nbsp;</p><p>&nbsp;</p><p><i>Email ini dihasilkan secara otomatis oleh sistem dan mohon untuk tidak membalas email ini. Informasi lebih lanjut hubungi User Relation di <strong>0812 2483 7500</strong> (Telepon &amp; WA) atau <strong>cstth@telkom.co.id</strong></i></p>',
            'sign_by' => '["1","0d0085e1-2020-4074-a890-53bb4d302ead"]',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
