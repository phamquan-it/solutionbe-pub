<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ITCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('i_t_categories')->insert([
            [
                'id' => 1,
                'name' => 'Restful WebServices',
                'created_at' => '2025-03-10 20:40:45',
                'updated_at' => '2025-03-09 02:12:50',
            ],
            [
                'id' => 2,
                'name' => 'Mobile App(Android,IOS)',
                'created_at' => '2025-03-10 00:00:00',
                'updated_at' => '2025-03-09 02:13:16',
            ],
            [
                'id' => 3,
                'name' => 'Web Api',
                'created_at' => '2025-01-31 20:41:21',
                'updated_at' => '2025-03-09 02:13:31',
            ],
            [
                'id' => 4,
                'name' => 'Static Web',
                'created_at' => '2025-01-31 00:00:00',
                'updated_at' => '2025-03-09 02:14:30',
            ],
            [
                'id' => 5,
                'name' => 'Desktop App',
                'created_at' => '2025-01-31 20:41:47',
                'updated_at' => '2025-03-09 02:14:59',
            ],
            [
                'id' => 7,
                'name' => 'Cross-platform',
                'created_at' => '2025-01-31 00:00:00',
                'updated_at' => '2025-03-09 02:15:20',
            ],
            [
                'id' => 8,
                'name' => 'Linux setup',
                'created_at' => '2025-01-31 20:42:20',
                'updated_at' => '2025-03-09 02:16:15',
            ],
            [
                'id' => 9,
                'name' => 'Window setup',
                'created_at' => '2025-01-31 00:00:00',
                'updated_at' => '2025-03-09 02:16:32',
            ],
            [
                'id' => 10,
                'name' => 'CommandLine tools',
                'created_at' => '2025-01-31 20:42:49',
                'updated_at' => '2025-03-09 02:16:50',
            ],
            [
                'id' => 11,
                'name' => 'Graphic Tools',
                'created_at' => '2025-03-05 04:08:11',
                'updated_at' => '2025-03-09 02:17:10',
            ],
            [
                'id' => 12,
                'name' => 'Windows setup',
                'created_at' => '2025-03-05 04:13:18',
                'updated_at' => '2025-03-05 04:13:18',
            ],
            [
                'id' => 13,
                'name' => 'From templates',
                'created_at' => '2025-03-11 13:38:36',
                'updated_at' => '2025-03-11 13:39:02',
            ],
        ]);
    }
}
