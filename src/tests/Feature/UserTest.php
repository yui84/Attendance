<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    //会員登録
    public function test_register_user()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas(User::class, [
            'name' => 'テストユーザー',
            'email' => 'test@gmail.com'
        ]);
    }

    //一般ユーザーのログイン機能
    public function test_login_user()
    {
        $user = User::find(2);

        $response = $this->post('/login', [
            'email' => 'general1@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/attendance');
        $this->assertAuthenticatedAs($user);
    }

    //管理者のログイン機能
    public function test_login_admin()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'admin1234',
        ]);

        $response->assertRedirect('/admin/attendance/list');
        $this->assertAuthenticatedAs($user);
    }
}
