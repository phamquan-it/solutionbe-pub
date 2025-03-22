<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'id' => 1,
                'name' => 'Simple profile',
                'category' => null,
                'amount' => 23,
                'price' => 122.00,
                'description' => 'Simple profile to you',
                'is_active' => 1,
                'rate' => 4.50,
                'available_from' => null,
                'created_at' => Carbon::parse('2025-03-04 16:02:42'),
                'updated_at' => Carbon::parse('2025-03-09 06:59:45'),
                'image' => 'uploads/bank.png'
            ],
            [
                'id' => 2,
                'name' => 'Webfullstack',
                'category' => null,
                'amount' => 23,
                'price' => 122.00,
                'description' => 'test',
                'is_active' => 1,
                'rate' => 4.50,
                'available_from' => null,
                'created_at' => Carbon::parse('2025-03-05 06:23:36'),
                'updated_at' => Carbon::parse('2025-03-09 07:06:39'),
                'image' => 'uploads/automation-removebg.png'
            ],
            [
                'id' => 3,
                'name' => 'Desktop app',
                'category' => null,
                'amount' => 11,
                'price' => 100.00,
                'description' => 'test',
                'is_active' => 1,
                'rate' => 4.50,
                'available_from' => null,
                'created_at' => Carbon::parse('2025-03-06 08:17:27'),
                'updated_at' => Carbon::parse('2025-03-09 18:42:08'),
                'image' => 'uploads/NET_Core_Logo.svg.png'
            ],
            [
                'id' => 5,
                'name' => 'Web api',
                'category' => null,
                'amount' => 1,
                'price' => 100.00,
                'description' => 'api',
                'is_active' => 1,
                'rate' => 4.50,
                'available_from' => null,
                'created_at' => Carbon::parse('2025-03-07 04:01:04'),
                'updated_at' => Carbon::parse('2025-03-09 18:41:37'),
                'image' => 'uploads/js.png'
            ],
            [
                'id' => 6,
                'name' => 'Fullstack web',
                'category' => null,
                'amount' => 1,
                'price' => 100.00,
                'description' => 'web fullstack',
                'is_active' => 1,
                'rate' => 4.50,
                'available_from' => null,
                'created_at' => Carbon::parse('2025-03-07 04:01:33'),
                'updated_at' => Carbon::parse('2025-03-09 18:40:54'),
                'image' => 'uploads/automation-removebg-removebg-preview.png'
            ],
            [
                'id' => 8,
                'name' => 'CLI tools',
                'category' => null,
                'amount' => 1,
                'price' => 10.00,
                'description' => 'commandline automation tool',
                'is_active' => 1,
                'rate' => 3.00,
                'available_from' => null,
                'created_at' => Carbon::parse('2025-03-09 04:23:03'),
                'updated_at' => Carbon::parse('2025-03-09 04:23:03'),
                'image' => 'uploads/automation-removebg.png'
            ]
        ];

        // Insert data into the services table
        foreach ($services as $service) {
            Service::updateOrCreate(['id' => $service['id']], $service);
        }
    }
}
