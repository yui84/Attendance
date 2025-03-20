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

class AdminTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    
}
