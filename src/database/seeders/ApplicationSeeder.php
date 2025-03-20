<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'rest_id' => '2',
            'correction_id' => '1',
            'start' => '13:00:00',
            'end' => '14:00:00',
            'created_at' => '2025-02-28 12:00:00',
            'updated_at' => '2025-02-28 12:00:00'
        ];
        Application::create($param);

        $param = [
            'rest_id' => '4',
            'correction_id' => '2',
            'start' => '13:00:00',
            'end' => '14:00:00',
            'created_at' => '2025-02-28 13:00:00',
            'updated_at' => '2025-02-28 13:00:00'
        ];
        Application::create($param);
    }
}