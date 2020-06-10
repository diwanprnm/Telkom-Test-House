<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UserinControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * [testIndexPage render index userin page]
     *
     * @return void
     */
    public function testIndexPage()
    {
        // 1. Create user and set role_id = 1 is admin
        $admin = factory(User::class)->create([
            'role_id' => '1',
            'name' => 'admin'
        ]);
        // 2. Render page product
        $response = $this->actingAs($admin)->get(route('userin.index'));
        // 3. Verify Response 200 = successfully
        $response->assertStatusCode(200);
        $response->assertViewIs('admin.userin.index');
    }
}
