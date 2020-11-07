<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class TenantScopeTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_model_has_a_tenent_id_on_the_migration()
    {

        $now = now();

        Artisan::call('make:model Test -m');

        $filepath = '';

        try {

            $filename = $now->format('Y_m_d_His') . '_create_tests_table.php';

            $filepath = database_path('migrations/' . $filename);
            $this->assertTrue(File::exists($filepath));

            $this->assertStringContainsString('$table->unsignedBigInteger(\'tenant_id\')->index();',
                File::get($filepath));
        }catch (\Exception $e){
            $this->fail($e->getMessage());
        } finally {
            File::delete($filepath);
            File::delete(app_path('Models/Test.php'));
        }

    }

    public function test_a_user_can_only_see_users_in_the_same_tenant()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();

        $users = User::factory()->count(10)->create([
            'tenant_id' => $tenant1
        ]);

        User::factory()->count(10)->create([
            'tenant_id' => $tenant2
        ]);

        $this->assertCount(20, User::all());

        auth()->login($users->first());

        $this->assertCount(10, User::all());
    }

    public function test_a_user_can_only_create_a_user_in_his_tenant()
    {
        $tenant1 = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant1
        ]);

        auth()->login($user);

        $user2 = User::factory()->create();

        $this->assertTrue($user->tenant_id === $user2->tenant_id);
    }

    public function test_a_user_can_only_create_a_user_in_his_tenant_even_other_tenent_is_provided()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant1
        ]);

        auth()->login($user);

        $user2 = User::factory()->make([
            'tenant_id' => $tenant2
        ]);

        $this->assertTrue($user2->tenant_id === $tenant2->id);

        $user2->save();

        $this->assertTrue($user2->tenant_id === $tenant1->id);
    }
}
