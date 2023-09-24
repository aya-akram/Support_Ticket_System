<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'keywords' => 'Sample Keywords',
            'description' => 'Sample Description',
            'footer_description' => 'Sample Footer Description',
            'client_can_edit' => true,
            'staff_can_edit' => true,
            'ticket_email' => 'sample@ticket.com',
            'admin_email' => 'admin@example.com',
            'copyrights' => '© 2023 Your Company',
            'facebook' => 'https://www.facebook.com/yourcompany',
            'twitter' => 'https://twitter.com/yourcompany',
            'linkedin' => 'https://www.linkedin.com/company/yourcompany',
        ]);
    }
}
