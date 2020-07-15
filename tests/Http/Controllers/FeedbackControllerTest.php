<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Feedback;

class FeedbackControllerTest extends TestCase
{

    public function testIndexAsNonAdmin()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user)->call('GET','admin/feedback');
        //status sukses, tapi ada bacaaan you dont have permission
        $this->assertResponseStatus(200)
        ->see("Unautorizhed. You do not have permission to access this page.");
    }
    

    public function testIndexAsAdmin()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedback');
        //Status sukses dan judul "QUESTIONS AND ANSWERS (QNA)"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">QUESTIONS AND ANSWERS (QNA)</h1>');
    }

    public function testIndexWithSearchFilter()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedback?search=cari');
        //Status sukses dan judul "QUESTIONS AND ANSWERS (QNA)"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">QUESTIONS AND ANSWERS (QNA)</h1>');
    }

    public function testIndexWithStatusFilter()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedback?status=1');
        //Status sukses dan judul "QUESTIONS AND ANSWERS (QNA)"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">QUESTIONS AND ANSWERS (QNA)</h1>');
    }

    public function testIndexWithDateFilter()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedback?before_date=2020-07-19&after_date=2020-07-12');
        //Status sukses dan judul "QUESTIONS AND ANSWERS (QNA)"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">QUESTIONS AND ANSWERS (QNA)</h1>');
    }

    public function testRepy()
    {
        Feedback::truncate();
        $feedback = factory(App\Feedback::class)->create();
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedback/'.$feedback->id.'/reply');
        //Status sukses dan judul "BALAS FEEDBACK"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">BALAS FEEDBACK</h1>');   
    }

    public function testSendEmailReplyFeedback()
    {
        $feedback = Feedback::latest()->first();
        $user = User::where('id', '=', '1')->first();

        $response = $this->actingAs($user)
            ->visit('admin/feedback/'.$feedback->id.'/reply')
            ->see('<h1 class="mainTitle">BALAS FEEDBACK</h1>')
            ->type('This is reply feedback test', 'description')
            ->press('submit');

        //Response status redirect to feedback.index
        $this->assertResponseStatus(200)
            ->seePageIs('admin/feedback')
            ->see('<h1 class="mainTitle">QUESTIONS AND ANSWERS (QNA)</h1>');

        //description masuk ke log
        $this->seeInDatabase('logs', [
            'action' => 'Reply Feedback',
            'data' => '{"data":"This is reply feedback test"}'
        ]);
    }

    public function testDestroy()
    {
        $feedback = Feedback::latest()->first();
        $user = User::where('id', '=', '1')->first();
        //visit index, delete a feedback
        $this->actingAs($user)->call('POST','admin/feedback/'.$feedback->id.'/destroy');
        //Response status redirect to feedback.index
        $this->assertRedirectedTo('/admin/feedback', ['message' => 'Feedback successfully deleted']);
        //delete remaining feedback(s) when test is done
        Feedback::truncate();
    }

    public function testIndexWithoutDataDound()
    {
        Feedback::truncate();
        //TODO truncate data first
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedback');
        //Status sukses dan judul "QUESTIONS AND ANSWERS (QNA)"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">QUESTIONS AND ANSWERS (QNA)</h1>')
            ->see('Data not found');
    }
}
