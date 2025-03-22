<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::create([
            'name' => "admin",
            "email" => "admin@gmail.com",
            "password" => "Admin123@"
        ]);
        User::create([
            'name' => "user",
            "email" => "user@gmail.com",
            "password" => "User123@"
        ]);
        $this->call([
            BankSeeder::class,
            FAQSeeder::class,
            LogSeeder::class,
            CategorySeeder::class,
            CashflowSeeder::class,
            ContactSeeder::class,
            FAQSeeder::class,
            ITCategorySeeder::class,
            LanguageSeeder::class,
            SettingSeeder::class,
            TechnologySeeder::class,
            RoleSeeder::class,
            SyncRolesSeeder::class,
            PostSeeder::class,
            ServiceSeeder::class
        ]);
    }
}
