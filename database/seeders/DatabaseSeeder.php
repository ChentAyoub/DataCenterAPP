<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Resource;
use App\Models\Notification;
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

        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'internal_user',
        ]);

  
        $cat1 = Category::create(['name' => 'Servers']);
        $cat2 = Category::create(['name' => 'Laptops']);
        $cat3 = Category::create(['name' => 'Networking']);
        $cat4 = Category::create(['name' => 'Cabling']);
    

        for ($i = 1; $i <= 25; $i++) {
            Resource::create([
                'name' => 'Lab Resource ' . $i,
                'category_id' => rand($cat1->id, $cat4->id),
                'specifications' => 'High Performance Config (16GB RAM, 512GB SSD)',
                'description' => 'Standard equipment for lab use.',
                'state' => 'available',
                'manager_id' => $manager->id,
            ]);
        }


        Notification::create([
            'user_id' => $student->id,
            'message' => 'Welcome to DataCenter.io! Your account is active.',
            'type' => 'info',
            'is_read' => false,
        ]);
        
        Notification::create([
            'user_id' => $student->id,
            'message' => 'Your reservation for "Lab Resource 5" was APPROVED.',
            'type' => 'success',
            'is_read' => false,
        ]);
    }
}