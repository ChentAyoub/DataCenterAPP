<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        // 1. Clear old data safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Resource::truncate();
        Category::truncate();
        // Optional: clear users if you want a fresh start, otherwise remove the next line
        // User::truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create a Dummy Manager (so we have an ID to use)
        $manager = User::firstOrCreate(
            ['email' => 'manager@datacenter.com'], // Check if exists
            [
                'name' => 'Mr. Manager',
                'password' => Hash::make('password'),
                'role' => 'manager'
            ]
        );

        // 3. Define Categories for Emojis
        $categories = ['Server', 'Router', 'Switch', 'Cabling'];

        foreach ($categories as $catName) {
            
            $category = Category::create([
                'name' => $catName,
                'description' => "All available $catName equipment."
            ]);

            // 4. Create 5 Resources per category
            for ($i = 1; $i <= 5; $i++) {
                Resource::create([
                    'category_id' => $category->id,
                    'manager_id' => $manager->id, // <--- FIXED: Assign the manager ID here
                    'name' => "$catName Model-" . rand(100, 999), 
                    'specifications' => 'Standard Lab Config (8GB RAM, 256GB SSD)',
                    'description' => "This is a dummy description for the $catName. Great for testing layouts.",
                    'state' => rand(0, 1) ? 'available' : 'maintenance',
                    'image' => null, 
                ]);
            }
        }
    }
}