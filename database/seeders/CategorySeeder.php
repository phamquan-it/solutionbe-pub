<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Full setup',
                'created_at' => Carbon::parse('2025-03-07 00:32:33'),
                'updated_at' => Carbon::parse('2025-03-07 00:34:18'),
            ],
            [
                'id' => 16,
                'name' => 'Module',
                'created_at' => Carbon::parse('2025-03-07 00:34:30'),
                'updated_at' => Carbon::parse('2025-03-07 00:34:30'),
            ],
            [
                'id' => 17,
                'name' => 'Source only',
                'created_at' => Carbon::parse('2025-03-07 00:36:13'),
                'updated_at' => Carbon::parse('2025-03-07 00:36:13'),
            ],
        ]);
    }
}
