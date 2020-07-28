<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class SalesControllerTest extends TestCase
{

    public function delete_soon(){
        $this->assertTrue(true);
    }

    // public function testIndexWithoutData()
    // {
    //     // //truncate data
    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     App\STELSalesDetail::truncate();
    //     App\STEL::truncate();
    //     App\STELSalesAttach::truncate();
    //     App\STELSales::truncate();
    //     App\Logs::truncate();
    //     App\Logs_administrator::truncate();
    //     App\User::where('id','!=', '1')->delete();
    //     App\Company::where('id','!=', '1')->delete();
    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET','admin/sales');

    //     //Status sukses dan judul Rekap Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Rekap Pembelian STEL</h1>')
    //         ->see('Data not found')
    //     ;

        
    // }

    // public function testIndexWithFilter()
    // {
    //     $stelsSales = factory(App\STELSales::class)->create(['id'=>'00000000-aaaa-aaaa-aaaa-000000000000']);
    //     $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
    //     $stels = App\STEL::find($stelSalesDetail->stels_id);
    //     factory(App\STELSalesAttach::class)->create(['stel_sales_id'=>$stelsSales->id]);

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/sales?before_date=2100-01-01&after_date=2020-01-01&search=$stels->name");

    //     //Status sukses dan judul Rekap Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Rekap Pembelian STEL</h1>')
    //         ->see($stels->name)
    //         ->see($stelsSales->created_at)
    //     ;
    // }


    // public function testCreate()
    // {
    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET','admin/sales/create');

    //     //Status sukses dan judul Tambah Data Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Tambah Data Pembelian STEL</h1>')
    //     ;
    // }

    // public function testStore()
    // {
    //     //makes STELS & USER
    //     $stel = factory(App\STEL::class)->create();
    //     $user = factory(App\User::class)->create(['role_id'=>2]);

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('POST','admin/sales',[
    //         'user_id' => $user->id,
    //         'is_tax' => 1,
    //         'stels' => [0 => "$stel->id-myToken-$stel->price"]
    //     ]);

    //     //Status sukses dan message Sales successfully created
    //     $this->assertRedirectedTo('admin/sales', ['message' => 'Sales successfully created']);

    // }

    // public function testShow()
    // {
    //     //get data
    //     $stelsSalesDetail = App\STELSalesDetail::latest()->first();
    //     $stel = App\STELSalesDetail::find($stelsSalesDetail->id);
        
    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->stels_sales_id");

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Detail Pembelian STEL</h1>')
    //         ->see($stel->name)
    //     ;
    // }
    // public function sales_detail()
    // {
    //     // this for sales_detail ************************************************************************
    // }
    

    // public function testExcel()
    // {
    //     //get data
    //     $stels = App\STEL::latest()->first();

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($admin)->call('GET',"sales/excel?before_date=2100-01-01&after_date=2020-01-01&search=$stels->name");

    //     //response ok, header download sesuai
    //     $this->assertResponseStatus(200);
    //     $this->assertTrue($response->headers->get('content-type') == 'Application/Spreadsheet');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename=Data Sales.xlsx');
    // }

    // public function testEdit()
    // {
    //     //get data
    //     $stelsSalesDetail = App\STELSalesDetail::latest()->first();
        
    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->stels_sales_id/edit");

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Update Status Sales</h1>')
    //     ;
    // }

    // public function testUpdate()
    // {
    //     // this for update()     ************************************************************************
    // }

    // public function api_upload()
    // {
    //     // this for api_upload() ************************************************************************
    // }
    
    // public function api_invoice()
    // {
    //     // this for api_invoice()************************************************************************
    // }
    
    // public function testUpload()
    // {
    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/sales/2/upload");

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Update Dokumen STEL</h1>')
    //     ;
    // }

    // public function testUploadUnidentified()
    // {
    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/sales/1/upload");

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertRedirectedTo('admin/sales', ['error' => "Can't upload attachment. Undefined INVOICE_ID!"]);
    // }

    // public function testViewMedia()
    // {
    //     //save file to minio
    //     $file = \Storage::disk('local_public')->get("images/testing.jpg");
    //     \Storage::disk('minio')->put("stel/1/testing.jpg", $file);

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($admin)->call('GET',"admin/downloadbukti/1");

    //     //response ok, header download dari minio
    //     $this->assertResponseStatus(200);
    //     $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename=testing.jpg');

    //     // Delete file from minio
    //     \Storage::disk('minio')->delete("stel/1/testing.jpg");
    // }

    // public function testWaterMark()
    // {
    //     $stelSalesDetail = App\STELSalesDetail::where('stels_sales_id', '=' ,1)->first();

    //     //save file to minio
    //     $file = \Storage::disk('local_public')->get("images/testing.jpg");
    //     \Storage::disk('minio')->put("stelAttach/$stelSalesDetail->id/$stelSalesDetail->attachment", $file);

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($admin)->call('GET',"admin/downloadstelwatermark/$stelSalesDetail->id");

    //     //response ok, header download dari minio
    //     $this->assertResponseStatus(200);
    //     $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == "attachment; filename=$stelSalesDetail->attachment");

    //     // Delete file from minio
    //     \Storage::disk('minio')->delete("stelAttach/$stelSalesDetail->id/testing.jpg");
    // }

    // public function testDownloadKuitansiStel()
    // {
    //     //get data
    //     $stelSales = App\STELSales::find(1);

    //     //save file to minio
    //     $file = \Storage::disk('local_public')->get("images/testing.jpg");
    //     \Storage::disk('minio')->put("stel/$stelSales->id/$stelSales->id_kuitansi", $file);

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($admin)->call('GET',"admin/downloadkuitansistel/$stelSales->id_kuitansi");

    //     //response ok, header download dari minio
    //     $this->assertResponseStatus(200);
    //     $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == "attachment; filename=$stelSales->id_kuitansi");

    //     // Delete file from minio
    //     \Storage::disk('minio')->delete("stel/$stelSales->id/$stelSales->id_kuitansi");
    // }

    // public function testDownloadFaktur()
    // {
    //     //get data
    //     $stelSales = App\STELSales::find(1);

    //     //save file to minio
    //     $file = \Storage::disk('local_public')->get("images/testing.jpg");
    //     \Storage::disk('minio')->put("stel/$stelSales->id/$stelSales->faktur_file", $file);

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($admin)->call('GET',"admin/downloadfakturstel/1");

    //     //response ok, header download dari minio
    //     $this->assertResponseStatus(200);
    //     $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == "attachment; filename=$stelSales->faktur_file");

    //     // Delete file from minio
    //     \Storage::disk('minio')->delete("stel/$stelSales->id/$stelSales->faktur_file");
    // }

    // public function testDeleteProduct()
    // {
    //     //get data
    //     $stelsSalesDetail = App\STELSalesDetail::latest()->first();
    //     $alasan = 'test_alasan_delete';

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/sales/$stelsSalesDetail->id/$alasan/deleteProduct");

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertRedirectedTo(
    //         "admin/sales/$stelsSalesDetail->stels_sales_id",
    //         ['message' => 'Successfully Delete Data'])
    //     ;
    // }

    // public function testDeleteProductThatNotExsist()
    // {
    //     //get data
    //     $alasan = 'test_alasan_delete';

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/sales/ProductThatNotExsist/$alasan/deleteProduct");

    //     //Status sukses dan judul Detail Pembelian STEL
    //     $this->assertRedirectedTo(
    //         "admin/sales/",
    //         ['error' => 'Undefined Data'])
    //     ;

    //     // //truncate data
    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     App\STELSalesDetail::truncate();
    //     App\STEL::truncate();
    //     App\STELSalesAttach::truncate();
    //     App\STELSales::truncate();
    //     App\Logs::truncate();
    //     App\Logs_administrator::truncate();
    //     App\User::where('id','!=', '1')->delete();
    //     App\Company::where('id','!=', '1')->delete();
    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    // }


}
