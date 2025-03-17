<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => '1',
            'date' => '2025-02-01',
            'start' => '2025-02-01 09:00:00',
            'end' => '2025-02-01 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '2',
            'date' => '2025-02-01',
            'start' => '2025-02-01 09:00:00',
            'end' => '2025-02-01 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '1',
            'date' => '2025-02-02',
            'start' => '2025-02-02 09:00:00',
            'end' => '2025-02-02 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '2',
            'date' => '2025-02-02',
            'start' => '2025-02-02 09:00:00',
            'end' => '2025-02-02 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '1',
            'date' => '2025-03-01',
            'start' => '2025-03-01 09:00:00',
            'end' => '2025-03-01 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '2',
            'date' => '2025-03-01',
            'start' => '2025-03-01 09:00:00',
            'end' => '2025-03-01 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '1',
            'date' => '2025-03-02',
            'start' => '2025-03-02 09:00:00',
            'end' => '2025-03-02 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '2',
            'date' => '2025-03-02',
            'start' => '2025-03-02 09:00:00',
            'end' => '2025-03-02 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '1',
            'date' => '2025-03-03',
            'start' => '2025-03-03 09:00:00',
            'end' => '2025-03-03 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '2',
            'date' => '2025-03-03',
            'start' => '2025-03-03 09:00:00',
            'end' => '2025-03-03 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '1',
            'date' => '2025-04-01',
            'start' => '2025-04-01 09:00:00',
            'end' => '2025-04-01 18:00:00',
        ];
        Work::create($param);

        $param = [
            'user_id' => '2',
            'date' => '2025-04-01',
            'start' => '2025-04-01 09:00:00',
            'end' => '2025-04-01 18:00:00',
        ];
        Work::create($param);
    }
}
