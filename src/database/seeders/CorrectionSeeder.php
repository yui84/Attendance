<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Correction;

class CorrectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'work_id' => '2',
            'start' => '10:00:00',
            'end' => '19:00:00',
            'note' => '遅延のため',
            'status' => 'pending',
            'created_at' => '2025-02-28 12:00:00',
            'updated_at' => '2025-02-28 12:00:00'
        ];
        Correction::create($param);

        $param = [
            'work_id' => '4',
            'start' => '10:00:00',
            'end' => '19:00:00',
            'note' => '遅延のため',
            'status' => 'approved',
            'created_at' => '2025-02-28 13:00:00',
            'updated_at' => '2025-02-28 13:00:00'
        ];
        Correction::create($param);
    }
}