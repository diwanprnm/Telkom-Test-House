<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Faq;
use App\User;

class FaqControllerTest extends TestCase
{
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
        $this->actingAs(User::find(1))->call('GET', 'admin/faq');
        $this->assertResponseStatus(200)->see('Faq');
    }

    public function testIndexWithSearch()
    {
        $this->actingAs(User::find(1))->call('GET', 'admin/faq?search=cari');
        $this->assertResponseStatus(200)->see('Faq');
    }

    public function testCreate()
    {
        $this->actingAs(User::find(1))->call('GET', 'admin/faq/create');
        $this->assertResponseStatus(200)->see('Tambah FAQ Baru');
    }

    public function testStore()
    {
        $this->actingAs(User::find(1))->call('POST', 'admin/faq', [
            'question' => 'saya mau bertanya pak',
            'answer' => 'ya silahkan, langsung saja'
        ]);
        $this->assertRedirectedTo('admin/faq', ['message' => 'FAQ successfully created']);
    }

    public function testShow()
    {
        $this->actingAs(User::find(1))->call('GET', 'admin/faq/id');
        $this->assertResponseStatus(200);
    }

    public function testEdit()
    {
        $faq = Faq::latest()->first();
        $this->actingAs(User::find(1))->call('GET', "admin/faq/$faq->id/edit");
        $this->assertResponseStatus(200)->see('Edit FAQ');
    }

    public function testUpdate()
    {
        $faq = Faq::latest()->first();
        $this->actingAs(User::find(1))->call('PATCH', "admin/faq/$faq->id", [
            'question' => 'Jadi gini pak 1 + 1 berapa ya?',
            'answer' => 'ya 2 dong!'
        ]);
        $this->assertRedirectedTo('admin/faq', ['message' => 'FAQ successfully updated']);
    }

    public function testDelete()
    {
        $faq = Faq::latest()->first();
        $this->actingAs(User::find(1))->call( 'DELETE', "admin/faq/$faq->id" );
        $this->assertRedirectedTo('admin/faq', ['message' => 'FAQ successfully deleted']);
    }

    public function testDeleteNotFound()
    {
        $this->actingAs(User::find(1))->call( 'DELETE', "admin/faq/dataNotFound" );
        $this->assertRedirectedTo('admin/faq', ['error' => 'FAQ Not Found']);
    }
}
