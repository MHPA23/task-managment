<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TasksSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Task::factory()->count(50000)->create([
            'user_id' => User::factory()->create(),
        ]);
    }
}
