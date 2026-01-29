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
  public function run(): void
{
    $admin = \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@datacenter.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_approved' => true,
    ]);
    $cats = [
        'Rack Servers' => \App\Models\Category::create(['name' => 'Rack Servers']),
        'Networking'   => \App\Models\Category::create(['name' => 'Networking']),
        'Storage'      => \App\Models\Category::create(['name' => 'Storage']),
        'Security'     => \App\Models\Category::create(['name' => 'Security']),
        'Workstations' => \App\Models\Category::create(['name' => 'Workstations']),
    ];

    $brands = [
        'Rack Servers' => [
            ['name' => 'Dell PowerEdge R', 'specs' => 'Intel Xeon, DDR4 ECC, IDRAC9'],
            ['name' => 'HPE ProLiant DL', 'specs' => 'AMD EPYC, 128GB RAM, SmartArray'],
            ['name' => 'Lenovo ThinkSystem SR', 'specs' => 'Dual Intel Xeon, NVMe Hot-swap']
        ],
    ];

    foreach ($brands as $categoryName => $models) {
        $catId = $cats[$categoryName]->id;
        
        for ($i = 1; $i <= 14; $i++) {
            $template = $models[array_rand($models)];
            $modelNumber = 100 + ($i * 10); 
            
            \App\Models\Resource::create([
                'category_id'    => $catId,
                'manager_id'     => $admin->id,
                'name'           => $template['name'] . $modelNumber,
                'specifications' => $template['specs'] . ", Unit #" . $i,
                'description'    => "Professional enterprise-grade hardware for high-demand $categoryName workloads.",
                'state'          => (rand(1, 10) > 2) ? 'available' : 'maintenance',
                'image'          => null,
            ]);
        }
    }
}
}