<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rest;

class RestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'work_id' => '1',
            'start' => '2025-02-01 12:00:00',
            'end' => '2025-02-01 13:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '2',
            'start' => '2025-02-01 13:00:00',
            'end' => '2025-02-01 14:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '3',
            'start' => '2025-02-02 12:00:00',
            'end' => '2025-02-02 13:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '4',
            'start' => '2025-02-02 13:00:00',
            'end' => '2025-02-02 14:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '5',
            'start' => '2025-03-01 12:00:00',
            'end' => '2025-03-01 13:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '6',
            'start' => '2025-03-01 13:00:00',
            'end' => '2025-03-01 14:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '7',
            'start' => '2025-03-02 12:00:00',
            'end' => '2025-03-02 13:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '8',
            'start' => '2025-03-02 13:00:00',
            'end' => '2025-03-02 14:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '9',
            'start' => '2025-03-03 12:00:00',
            'end' => '2025-03-03 13:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '10',
            'start' => '2025-03-03 13:00:00',
            'end' => '2025-03-03 14:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '11',
            'start' => '2025-04-01 12:00:00',
            'end' => '2025-04-01 13:00:00',
        ];
        Rest::create($param);

        $param = [
            'work_id' => '12',
            'start' => '2025-04-01 13:00:00',
            'end' => '2025-04-01 14:00:00',
        ];
        Rest::create($param);
    }
}