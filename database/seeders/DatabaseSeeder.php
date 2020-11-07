<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create();

        \App\Models\User::factory(9)->create([
            'tenant_id' => 1
        ]);

        \App\Models\User::factory(10)->create();
    }
}
