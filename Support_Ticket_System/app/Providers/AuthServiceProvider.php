<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */

    protected $policies = [
        // ...
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();


            Gate::define('viewStaffOrClientContent', function ($user) {
                return $user->hasRole('staff') || $user->hasRole('client');
            });
            // Gate::define('edit-ticket', function ($user) {
            //     return $user->hasRole('staff');
            // });

            Gate::define('edit-ticket', function ($user) {
                return $user->hasRole(['admin', 'staff']);
            });
            

        // // Define the 'viewStaffOrClientContent' permission
        // Gate::define('viewStaffOrClientContent', function ($user) {
        //     return $user->hasRole('staff') || $user->hasRole('client');
        // });
    }
}
