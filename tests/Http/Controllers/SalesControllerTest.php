<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class SalesControllerTest extends TestCase
{ 

    public function testIndexWithoutData()
    {
       
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('GET','admin/sales'); 
        $this->assertEquals(200, $response->status());
    } 

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

    public function testCreateOtherAdmin()
    {
        //make request
        $otherAdmin = factory(App\User::class)->create(['role_id'=>1]);
        $this->actingAs($otherAdmin)->call('GET','admin/sales/create');

        //Status sukses dan judul Tambah Data Pembelian STEL
        $this->assertResponseStatus(200);
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
        $response = $this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->stels_sales_id");

        //Status sukses dan judul Detail Pembelian STEL
        $this->assertEquals(200, $response->status());
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

    public function testGenerateFaktur()
    {  
        $users = factory(App\User::class)->create();
        $stelsSales = factory(App\STELSales::class)->create(['created_by'=>$users->id]);
        $STELSalesAttach = factory(App\STELSalesAttach::class)->create(['stel_sales_id'=>$stelsSales->id]);
        $stelsSalesDetail = factory(App\STELSalesDetail::class)->create(["stels_sales_id"=>$stelsSales->id]);
        
        //make request
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('POST',"admin/sales/$stelsSales->id/generateTaxInvoice", ['id' => $stelsSales->id]);

        //Response status 200
        $this->assertEquals(200, $response->status());
    }

    public function testExcel()
    {
        
        $stels = App\STEL::latest()->first();

        //make request
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('GET',"sales/excel?before_date=2100-01-01&after_date=2020-01-01&search=$stels->name");

        //response ok, header download sesuai
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Sales.xlsx"');
    }

    public function testEdit()
    {
 
         
        $admin = User::find(1);
        $users = factory(App\User::class)->create(['role_id'=>2]);
        $STELSales = factory(App\STELSales::class)->create(['user_id'=>$users->id]);
        $STELSalesAttach = factory(App\STELSalesAttach::class)->create(['stel_sales_id'=>$STELSales->id]);
        $stelsSalesDetail = factory(App\STELSalesDetail::class)->create(["stels_sales_id"=>$STELSales->id]);
        
        $response = $this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->stels_sales_id/edit");

        
        $this->assertEquals(200, $response->status());
    }

    public function testUpdate()
    {
        $STELSales = factory(App\STELSales::class)->create();  

        $stelsSalesDetail = factory(App\STELSalesDetail::class)->create(["stels_sales_id"=>$STELSales->id]);
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('PUT','admin/sales/'.$STELSales->id,
            [
                "payment_status"=>1,
                "stels_sales_detail_id"=>[$stelsSalesDetail->id],
                "stels_sales_attachment"=>["1.jpg"],
                "stel_file"=>["1.jpg"],
            ]);
        $this->assertEquals(302, $response->status());

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
