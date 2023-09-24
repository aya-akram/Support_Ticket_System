<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // $role = Role::create(['name' => 'admin']);
        // $role = Role::create(['name' => 'staff']);
        // $role = Role::create(['name' => 'client']);

        // $permission = Permission::create(['name' => 'view tickets']);
        // $permission = Permission::create(['name' => 'edit tickets']);
    }
}
