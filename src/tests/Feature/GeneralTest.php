<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Models\Correction;
use App\Models\Application;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Tests\TestCase;

class GeneralTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    //勤怠一覧情報取得
    public function test_get_list()
    {
        $user = User::find(2);

        $currentDate = Carbon::now()->format('Y/m');

        $response = $this->actingAs($user)->get('/attendance/list', [
            'currentDate' => $currentDate,
        ]);

        $response->assertStatus(200);
        $response->assertSee($currentDate);
    }

    //勤怠詳細情報取得機能
    public function test_work_detail()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get('/attendance/5');
        $work = Work::find(5);
        $rest = Rest::where('work_id', $work->id)->first();
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $user->name,
            \Carbon\Carbon::parse($work->date)->format('Y年 n月j日'),
            \Carbon\Carbon::parse($work->start)->format('H:i'),
            \Carbon\Carbon::parse($work->end)->format('H:i'),
            \Carbon\Carbon::parse($rest->start)->format('H:i'),
            \Carbon\Carbon::parse($rest->end)->format('H:i'),
        ]);
    }

    //勤怠詳細修正情報送信機能
    public function test_change_work()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->post('/attendance/correct', [
            'work_id' => 5,
            'start_time' => '10:00',
            'end_time' => '19:00',
            'rest_start' => ['0' => '12:00'],
            'rest_end' => ['0' => '13:00'],
            'rest_id' => ['0' => 5],
            'note' => '遅延のため',
            'status' => 'pending'
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas(Correction::class, [
            'work_id' => 5,
            'start' => '10:00:00',
            'end' => '19:00:00',
            'note' => '遅延のため',
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas(Application::class, [
            'rest_id' => 5,
            'start' => '12:00:00',
            'end' => '13:00:00',
        ]);
    }

    //申請一覧
    public function test_get_request()
    {
        $user = User::find(2);

        $expected_data = [
            'status' => 'pending',
            'name' => '一般ユーザー',
            'date' => '2025/02/01',
            'note' => '遅延のため',
            'created_at' => '2025/02/28',
            'work_id' => 2,
        ];

        $response = $this->actingAs($user)->get('/stamp_correction_request/list');
        $response->assertStatus(200);
        $response->assertViewHas('corrections', function ($corrections) use ($expected_data) {
            return $corrections[0]->status === $expected_data['status'] &&
                $corrections[0]->work->user->name === $expected_data['name'] &&
                Carbon::parse($corrections[0]->work->date)->format('Y/m/d') === $expected_data['date'] &&
                $corrections[0]->note === $expected_data['note'] &&
                $corrections[0]->created_at->format('Y/m/d') === $expected_data['created_at'] &&
                $corrections[0]->work_id === $expected_data['work_id'];
        });
    }

    //承認済み取得
    public function test_get_approved()
    {
        $user = User::find(2);

        $expected_data = [
            'status' => 'approved',
            'name' => '一般ユーザー',
            'date' => '2025/02/02',
            'note' => '遅延のため',
            'created_at' => '2025/02/28',
            'work_id' => 4,
        ];

        $response = $this->actingAs($user)->get('/stamp_correction_request/list?tab=check');
        $response->assertStatus(200);
        $response->assertViewHas('corrections', function ($corrections) use ($expected_data) {
            return $corrections[0]->status === $expected_data['status'] &&
                $corrections[0]->work->user->name === $expected_data['name'] &&
                Carbon::parse($corrections[0]->work->date)->format('Y/m/d') === $expected_data['date'] &&
                $corrections[0]->note === $expected_data['note'] &&
                $corrections[0]->created_at->format('Y/m/d') === $expected_data['created_at'] &&
                $corrections[0]->work_id === $expected_data['work_id'];
        });
    }
}
