<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class QuestionprivControllerTest extends TestCase
{

    public function testIndexWithotDataFound()
    {
        // truncate data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\Logs::truncate();
        App\Company::where('id','!=', '1')->delete();
        App\User::where('id','!=', '1')->delete();
        App\Question::truncate();
        App\Questionpriv::truncate();  
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/questionpriv");

        //redirect, go to admin/nogudang, and message "Nomor Gudang"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Question Privilege</h1>')
            ->see('Data Not Found')
        ;
    }

    public function testIndexAndCreateData()
    {
        //create Data
        $question = factory(App\Question::class)->create();
        $questionpriv = factory(App\Questionpriv::class)->create(['question_id' => $question->id]);
        $user = User::find($questionpriv->user_id);

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/questionpriv?search=$question->name");

        //assert status ok, title benar, dan ada data yang dicari
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Question Privilege</h1>')
            ->see($user->name)
        ;
    }


    public function testCreate()
    {
        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/questionpriv/create");

        //assert status ok, title benar, dan ada data yang dicari
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Tambah Question Privilege Baru</h1>')
        ;
    }


    public function testStoreWithExsistingData()
    {
        $questionpriv = App\Questionpriv::latest()->first();
        $question = App\Question::find($questionpriv->question_id);

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('POST',"admin/questionpriv",[
            'user_id' => $questionpriv->user_id,
            'check-privilege' => array(
                $question->id,
            )
        ]);

        //assert status ok, title benar, dan ada data yang dicari
        $this->assertRedirectedTo('admin/questionpriv/create',['error' => 'User Existing or No Privilege selected']);
    }

    public function testStore()
    {
        //create Data
        $user = factory(App\User::class)->create();
        $question = App\Question::latest()->first();

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('POST','admin/questionpriv',[
            'user_id' => $user->id,
            'check-privilege' => array(
                0 => $question->id,
            )
        ]);

        //assert status ok, title benar, dan ada data yang dicari
        $this->assertRedirectedTo('admin/questionpriv',['message' => 'User successfully created']);
    }


    public function testEdit()
    {
        $questionpriv = App\Questionpriv::latest()->first();

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/questionpriv/$questionpriv->user_id/edit");

        //assert status ok, title benar, dan ada data yang dicari
            $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Edit Privilege</h1>')
        ;
    }


    public function testUpdate()
    {
        $questionpriv = App\Questionpriv::latest()->first();

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('PATCH',"admin/questionpriv/$questionpriv->user_id",[
            'check-privilege' => array(
                0 => $questionpriv->question_id,
            )
        ]);

        //assert status ok, title benar, dan ada data yang dicari
        $this->assertRedirectedTo('admin/questionpriv',['message' => 'User successfully updated']);
        
    }

    public function testUpdateButNothingChecked()
    {
        $questionpriv = App\Questionpriv::latest()->first();

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('PATCH',"admin/questionpriv/$questionpriv->user_id",[
            'check-privilege' => array(
            )
        ]);

        //assert status ok, title benar, dan ada data yang dicari
        $this->assertRedirectedTo("admin/questionpriv/$questionpriv->user_id/edit", ['error' => 'No Privilege selected']);
        
    }

    public function testDestroy()
    {
        $questionpriv = App\Questionpriv::latest()->first();

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('DELETE',"admin/questionpriv/$questionpriv->user_id");

        //assert status ok, title benar, dan ada data yang dicari
        $this->assertRedirectedTo("admin/questionpriv", ['message' => 'Privilege successfully deleted']);

        // truncate data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\Logs::truncate();
        App\Company::where('id','!=', '1')->delete();
        App\User::where('id','!=', '1')->delete();
        App\Question::truncate();
        App\Questionpriv::truncate();  
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    


}
