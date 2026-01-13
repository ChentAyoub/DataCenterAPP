<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Ayoub Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Tech Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
        User::create([
            'name' => 'Student User',
            'email' => 'student@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'internal_user',
        ]);

        $catServer = Category::create(['name' => 'Serveurs Physiques', 'description' => 'High performance servers']);
        $catNetwork = Category::create(['name' => 'Réseau', 'description' => 'Switches and Routers']);
        $catStorage = Category::create(['name' => 'Stockage', 'description' => 'NAS and SAN Bays']);

        Resource::create([
            'name' => 'Dell PowerEdge R740',
            'category_id' => $catServer->id,
            'manager_id' => $manager->id,
            'state' => 'available',
            'specifications' => json_encode(['cpu' => '64 Cores', 'ram' => '128GB']),
        ]);

        Resource::create([
            'name' => 'Switch Cisco 2960',
            'category_id' => $catNetwork->id,
            'manager_id' => $manager->id,
            'state' => 'available',
            'specifications' => json_encode(['ports' => '24', 'speed' => '1Gbps']),
        ]);
    }
}