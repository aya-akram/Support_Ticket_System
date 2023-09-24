<?php
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class InitRolesAndPermissions extends Command
{
    protected $signature = 'init:roles-permissions';
    protected $description = 'Initialize roles and permissions';

    public function handle()
    {
        // Check if the role 'admin' exists for the 'web' guard
        if (!Role::where('name', 'admin')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'admin']);
        }

        // Check if the role 'staff' exists for the 'web' guard
        if (!Role::where('name', 'staff')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'staff']);
        }

        // Check if the role 'client' exists for the 'web' guard
        if (!Role::where('name', 'client')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'client']);
        }

        $this->info('Roles and permissions initialized successfully.');
    }
}
