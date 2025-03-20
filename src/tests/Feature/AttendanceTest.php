<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    //日時取得機能
    public function test_get_attendance()
    {
        $user = User::find(2);

        $today = now()->format('Y年n月j日');
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee($today);
    }

    //ステータス確認機能
    public function test_get_status_work()
    {
        $user = User::find(2);

        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('勤務外');

        $response = $this->actingAs($user)->post('/work', [
            'start_work' => true,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas(User::class, [
            'id' => 2,
            'status' => 1,
        ]);
        $response->assertRedirect('/attendance');
        $response->assertSessionHas('status', 1);

        $response = $this->actingAs($user)->post('/work', [
            'start_rest' => true,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas(User::class, [
            'id' => 2,
            'status' => 2,
        ]);
        $response->assertRedirect('/attendance');
        $response->assertSessionHas('status', 2);

        $response = $this->actingAs($user)->post('/work', [
            'end_work' => true,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas(User::class, [
            'id' => 2,
            'status' => 3,
        ]);
        $response->assertRedirect('/attendance');
        $response->assertSessionHas('status', 3);
    }
}
