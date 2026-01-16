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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Resource::truncate();
        Category::truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $manager = User::firstOrCreate(
            ['email' => 'manager@datacenter.com'],
            [
                'name' => 'Mr. Manager',
                'password' => Hash::make('password'),
                'role' => 'manager'
            ]
        );

        $categories = ['Server', 'Router', 'Switch', 'Cabling'];

        foreach ($categories as $catName) {
            
            $category = Category::create([
                'name' => $catName,
                'description' => "All available $catName equipment."
            ]);
            for ($i = 1; $i <= 5; $i++) {
                Resource::create([
                    'category_id' => $category->id,
                    'manager_id' => $manager->id,
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