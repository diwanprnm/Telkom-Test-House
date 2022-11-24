<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Approval;

class AuthentikasiControllerTest extends TestCase
{
    public function testIndex()
    {
        $approval = factory(App\Approval::class)->make();
        $this->call('GET','approval/'.$approval->id);
        
        $this->assertResponseStatus(200);
    }
}