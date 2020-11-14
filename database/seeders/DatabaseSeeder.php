<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
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
        $tenants = Tenant::factory(3)->create();

        $tenants->each(function ($tenant){
            User::factory(20)->create([
                'tenant_id' => $tenant->id
            ]);
        });

        User::factory()->create([
            'email' => 'admin@admin.com',
            'tenant_id' => null
        ]);
    }
}
