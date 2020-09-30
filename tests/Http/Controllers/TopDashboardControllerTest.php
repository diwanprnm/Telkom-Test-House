<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

use App\Http\Controllers\TopDashboardController; 

class TopDashboardControllerTest extends TestCase
{
   

    //SQLSTATE[HY000]: General error: 1 no such function: YEAR
    
    public function testIndex()
    {
        $labKabel = factory(App\ExaminationLab::class)->create(['name' => 'kabel']);
        $labEnergi = factory(App\ExaminationLab::class)->create(['name' => 'energi']);
        $labTransmisi = factory(App\ExaminationLab::class)->create(['name' => 'transmisi']);
        $labCpe = factory(App\ExaminationLab::class)->create(['name' => 'cpe']);
        $labKalibrasi = factory(App\ExaminationLab::class)->create(['name' => 'kalibrasi']);
        $exam = factory(App\Examination::class)->create();
        $income = factory(App\Income::class)->create(["reference_id"=>1,"created_at"=> date('Y-m-d')]);
        $income = factory(App\Income::class)->create(["reference_id"=>2,"created_at"=> date('Y-m-d')]);
        $income = factory(App\Income::class)->create(["reference_id"=>3,"created_at"=> date('Y-m-d')]);
        $income = factory(App\Income::class)->create(["reference_id"=>4,"created_at"=> date('Y-m-d')]); 
        $examnotComp = factory(App\Examination::class)->create(
            [
                'spb_status'=>1,
                'registration_status'=>1,
                'function_status'=>1,
                'contract_status'=>1,
                'payment_status'=>1,
                'spk_status'=>1,
                'examination_status'=>1,
                'resume_status'=>1,
                'qa_status'=>1,
                'certificate_status'=>1,
            ]);
        $examNoLulus = factory(App\Examination::class)->create(
            [
                'qa_passed'=>-1 
            ]);

        factory(App\STELSales::class)->create(['type' => $labKabel->id]);
        factory(App\STELSales::class)->create(['type' => $labEnergi->id]);
        factory(App\STELSales::class)->create(['type' => $labTransmisi->id]);
        factory(App\STELSales::class)->create(['type' => $labCpe->id]);
        factory(App\STELSales::class)->create(['type' => $labKalibrasi->id]);
        factory(App\STELSales::class)->create();

        $this->actingAs(User::find('1'))->call('GET', 'admin/topdashboard');
        $this->assertResponseStatus(200)->see('Top Dashboard');
    }

    public function testSearchGrafikType1()
    { 
       $this->actingAs(User::find('1'))->call('POST', 'admin/topdashboard/searchGrafik', ['type' => 1, 'keyword' => 2020]); 
       $this->assertResponseStatus(200);
    }

    
    // public function testSearchGrafikType2()
    // { 
    //     $this->actingAs(User::find('1'))->call('POST', 'admin/topdashboard/searchGrafik', ['type' => 2, 'keyword' => 2020]);
    //     $this->assertResponseStatus(200);
    // }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function test_sum_stelFunc()
    {  
        $object = app('App\Http\Controllers\TopDashboardController');
        $this->invokeMethod($object, 'sum_stelFunc',[2020,1]);
    }

    
}
