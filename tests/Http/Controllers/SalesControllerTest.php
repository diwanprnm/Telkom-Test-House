<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class SalesControllerTest extends TestCase
{

    public function testDeleteSoon(){
        $this->assertTrue(true);
        // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); //-
        App\STELSalesDetail::truncate();
        App\STEL::truncate();
        App\STELSalesAttach::truncate();
        App\STELSales::truncate();
        App\Logs::truncate();
        App\LogsAdministrator::truncate();
        App\User::where('id','!=', '1')->delete();
        App\Company::where('id','!=', '1')->delete();
        // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    // public function testIndexWithoutData()
    // {
    //     /*
    //      * Error query raw yang dipakai untuk mysql
    //      * tapi testing pakai sqlite
    //      */

    //     // truncate data
    //     // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); //-
    //     App\STELSalesDetail::truncate();
    //     App\STEL::truncate();
    //     App\STELSalesAttach::truncate();
    //     App\STELSales::truncate();
    //     App\Logs::truncate();
    //     App\LogsAdministrator::truncate();
    //     App\User::where('id','!=', '1')->delete();
    //     App\Company::where('id','!=', '1')->delete();
    //     // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    //     //make request
    //     $admin = User::find(1);
    //     $this->actingAs($admin)->call('GET','admin/sales');

    //     //Status sukses dan judul Rekap Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Rekap Pembelian STEL</h1>')
    //         ->see('Data not found')
    //     ;
    // }

    // public function testIndexWithFilter()
    // {
    //     /*
    //      * Error query raw yang dipakai untuk mysql
    //      * tapi testing pakai sqlite
    //      */

    //     $stelsSales = factory(App\STELSales::class)->create(['id'=>'00000000-aaaa-aaaa-aaaa-000000000000']);
    //     $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
    //     $stels = App\STEL::find($stelSalesDetail->stels_id);
    //     factory(App\STELSalesAttach::class)->create(['stel_sales_id'=>$stelsSales->id]);

    //     //make request
    //     $admin = User::find(1);
    //     $this->actingAs($admin)->call('GET',"admin/sales?before_date=2100-01-01&after_date=2020-01-01&search=$stels->name");

    //     //Status sukses dan judul Rekap Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Rekap Pembelian STEL</h1>')
    //         ->see($stels->name)
    //         ->see($stelsSales->created_at)
    //     ;
    // }


    public function testCreate()
    {
        //make request
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/sales/create');

        //Status sukses dan judul Tambah Data Pembelian STEL
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Tambah Data Pembelian STEL</h1>')
        ;
    }

    public function testStore()
    {
        //makes STELS & USER
        $stel = factory(App\STEL::class)->create();
        $user = factory(App\User::class)->create(['role_id'=>2]);

        //make request
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST','admin/sales',[
            'user_id' => $user->id,
            'is_tax' => 1,
            'stels' => [0 => "$stel->id-myToken-$stel->price"]
        ]);

        //Status sukses dan message Sales successfully created
        $this->assertRedirectedTo('/admin/sales', ['message' => 'Sales successfully created']);

    }

    public function testShow()
    {
        //get data
        $stelsSalesDetail = App\STELSalesDetail::latest()->first();
        $stel = App\STELSalesDetail::find($stelsSalesDetail->id);
        
        //make request
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->stels_sales_id");

        //Status sukses dan judul Detail Pembelian STEL
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Detail Pembelian STEL</h1>')
            ->see($stel->name)
        ;
    }

    public function testSalesDetail()
    {
        /*
         * Sales_detail tidak ada dalam routes
         * 
         */
        $this->assertTrue(true);
    }

    public function testGenerateKuitansi()
    {
        //get data
        $stelsSales = App\STELSales::latest()->first();

        //make request
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST',"admin/sales/$stelsSales->id/generateKuitansi", ['id' => $stelsSales->id]);

        //Response status 200
        $this->assertResponseStatus(200);
    }

    // public function testGenerateFaktur()
    // {
    //     /*
    //      * Error query raw yang dipakai untuk mysql
    //      * tapi testing pakai sqlite
    //      * SQLSTATE[HY000]: General error: 1 near "SEPARATOR"
    //      */
        
    //     //get data
    //     $stelsSales = App\STELSales::latest()->first();

    //     //make request
    //     $admin = User::find(1);
    //     dd($this->actingAs($admin)->call('POST',"admin/sales/$stelsSales->id/generateTaxInvoice", ['id' => $stelsSales->id]));

    //     //Response status 200
    //     $this->assertResponseStatus(200);
    // }

    // public function testExcel()
    // {
    //     /*
    //      * Error query raw yang dipakai untuk mysql
    //      * tapi testing pakai sqlite
    //      */

    //     //get data
    //     $stels = App\STEL::latest()->first();

    //     //make request
    //     $admin = User::find(1);
    //     dd($response = $this->actingAs($admin)->call('GET',"sales/excel?before_date=2100-01-01&after_date=2020-01-01&search=$stels->name"));

    //     //response ok, header download sesuai
    //     $this->assertResponseStatus(200);
    //     $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Sales.xlsx"');
    // }

    // public function testEdit()
    // {

    //     /*
    //      * Error query raw yang dipakai untuk mysql
    //      * tapi testing pakai sqlite
    //      * General error: 1 RIGHT and FULL OUTER JOINs are not currently supported
    //      */
        
    //     //get data
    //     $stelsSalesDetail = App\STELSalesDetail::latest()->first();
        
    //     //make request
    //     $admin = User::find(1);
    //     dd($this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->stels_sales_id/edit"));

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Update Status Sales</h1>')
    //     ;
    // }

    public function testUpdate()
    {
        // this for update()     ************************************************************************
    }

    public function api_upload()
    {
        /*
         * api_upload tidak ada dalam routes
         */
        $this->assertTrue(true);
    }
    
    public function api_invoice()
    {
        /*
         * api_invoice tidak ada dalam routes
         */
        $this->assertTrue(true);
    }
    
    // public function testUpload()
    // {

    //     /*
    //      * Error query raw yang dipakai untuk mysql
    //      * tapi testing pakai sqlite
    //      * General error: 1 RIGHT and FULL OUTER JOINs are not currently supported
    //      */

    //     //make request
    //     $admin = User::find(1);
    //     $this->actingAs($admin)->call('GET',"admin/sales/2/upload");

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Update Dokumen STEL</h1>')
    //     ;
    // }

    // public function testUploadUnidentified()
    // {
    //     /*
    //      * Error query raw yang dipakai untuk mysql
    //      * tapi testing pakai sqlite
    //      * General error: 1 RIGHT and FULL OUTER JOINs are not currently supported
    //      */

    //     //make request
    //     $admin = User::find(1);
    //     dd($this->actingAs($admin)->call('GET',"admin/sales/1/upload"));

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertRedirectedTo('admin/sales', ['error' => "Can't upload attachment. Undefined INVOICE_ID!"]);
    // }

    public function testViewMedia()
    {
        // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); //-
        App\STELSalesDetail::truncate();
        App\STEL::truncate();
        App\STELSalesAttach::truncate();
        App\STELSales::truncate();
        App\Logs::truncate();
        App\LogsAdministrator::truncate();
        App\User::where('id','!=', '1')->delete();
        App\Company::where('id','!=', '1')->delete();
        // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $stelsSales = factory(App\STELSales::class)->create();
        factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
        $attach = factory(App\STELSalesAttach::class)->create(['stel_sales_id'=>$stelsSales->id]);
        $stelAttach = App\STELSalesAttach::where("stel_sales_id",$attach->stel_sales_id)->first();

        //save file to minio
        $file = \Storage::disk('local_public')->get("images/testing.jpg");
        \Storage::disk('minio')->put("stel/$stelsSales->id/$stelAttach->attachment", $file);

        //make request
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('GET',"admin/downloadbukti/$stelsSales->id");

        //response ok, header download dari minio
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'image/*');

        // Delete file from minio
        \Storage::disk('minio')->delete("stel/$stelsSales->id/$attach->attachment");
    }

    public function testWaterMark()
    {
        $stelSalesDetail = App\STELSalesDetail::latest()->first();

        //dd("stelAttach/$stelSalesDetail->id/$stelSalesDetail->attachment");
        //save file to minio
        $file = \Storage::disk('local_public')->get("images/testing.jpg");
        \Storage::disk('minio')->put("stelAttach/$stelSalesDetail->id/$stelSalesDetail->attachment", $file);

        //make request
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('GET',"admin/downloadstelwatermark/$stelSalesDetail->id");

        //response ok, header download dari minio
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'image/*');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="'.$stelSalesDetail->attachment.'"');

        // Delete file from minio
        \Storage::disk('minio')->delete("stelAttach/$stelSalesDetail->id/$stelSalesDetail->attachment");
    }

    public function testDownloadKuitansiStel()
    {
        //get data
        $stelSales = App\STELSales::latest()->first();

        //save file to minio
        $file = \Storage::disk('local_public')->get("images/testing.jpg");
        \Storage::disk('minio')->put("stel/$stelSales->id/$stelSales->id_kuitansi", $file);

        //make request
        $admin = User::find(1);
        $id_kuitansi = preg_replace('/\\.[^.\\s]{3,4}$/', '', $stelSales->id_kuitansi);
        $response = $this->actingAs($admin)->call('GET',"admin/downloadkuitansistel/$id_kuitansi");

        //response ok, header download dari minio
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'image/*');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="'.$stelSales->id_kuitansi.'"');

        // Delete file from minio
        \Storage::disk('minio')->delete("stel/$stelSales->id/$stelSales->id_kuitansi");
    }

    public function testDownloadFaktur()
    {
        //get data
        $stelSales = App\STELSales::latest()->first();

        //save file to minio
        $file = \Storage::disk('local_public')->get("images/testing.jpg");
        \Storage::disk('minio')->put("stel/$stelSales->id/$stelSales->faktur_file", $file);

        //make request
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('GET',"admin/downloadfakturstel/$stelSales->id");

        //response ok, header download dari minio
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'image/*');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="'.$stelSales->faktur_file.'"');

        // Delete file from minio
        \Storage::disk('minio')->delete("stel/$stelSales->id/$stelSales->faktur_file");
    }

    public function testDeleteProduct()
    {
        //get data
        $stelsSalesDetail = App\STELSalesDetail::latest()->first();
        $alasan = 'test_alasan_delete';

        //make request
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->id/$alasan/deleteProduct");

        //Status sukses dan judul Detail Pembelian STEL
        $this->assertRedirectedTo(
            "admin/sales/$stelsSalesDetail->stels_sales_id",
            ['message' => 'Successfully Delete Data'])
        ;
    }

    public function testDeleteProductThatNotExsist()
    {
        //get data
        $alasan = 'test_alasan_delete';

        //make request
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/sales/ProductThatNotExsist/$alasan/deleteProduct");

        //Status sukses dan judul Detail Pembelian STEL
        $this->assertRedirectedTo(
            "admin/sales/",
            ['error' => 'Undefined Data'])
        ;

        // //truncate data
        // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\STELSalesDetail::truncate();
        App\STEL::truncate();
        App\STELSalesAttach::truncate();
        App\STELSales::truncate();
        App\Logs::truncate();
        App\LogsAdministrator::truncate();
        App\User::where('id','!=', '1')->delete();
        App\Company::where('id','!=', '1')->delete();
        // for mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }


}
