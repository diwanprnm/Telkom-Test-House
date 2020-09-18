<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class FunctionTestControllerTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testIndex()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/functiontest'); 
        $this->assertResponseStatus(200)->see('Data Uji Fungsi');
    }

    public function testExcel()
    {
        factory(App\Examination::class)->create(['function_status' => 0]);
        factory(App\Examination::class)->create(['function_status' => 0, 'function_date' => null]);
        factory(App\Examination::class)->create(['function_status' => 0, 'function_test_TE' => 1]);
        factory(App\Examination::class)->create(['function_status' => 0, 'function_test_TE' => 2]);
        factory(App\Examination::class)->create(['function_status' => 0, 'function_test_TE' => 3]);
        factory(App\Examination::class)->create(['function_status' => 0, 'function_test_TE' => 4]);

        $response = $this->actingAs(User::find('1'))->call('GET', 'functiontest/excel'); 
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Uji Fungsi.xlsx"');
    }



    
}
