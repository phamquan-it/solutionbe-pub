<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'time_update_service' => '12:00:00',
            'time_update_order' => '12:30:00',
            'time_deny_order' => '13:00:00',
            'time_exchange_rate' => '14:00:00',
            'account_no' => '1234567890',
            'phone' => '0987654321',
            'facebook' => 'https://facebook.com/example',
            'zalo' => 'https://zalo.me/example',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
