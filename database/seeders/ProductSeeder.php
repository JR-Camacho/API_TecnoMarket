<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'Iphone 13',
            'description' => 'Mobile Phone Apple',
            'price' => 980
        ]);
        DB::table('products')->insert([
            'name' => 'Ipad Pro 11',
            'description' => 'Tablet Apple',
            'price' => 850
        ]);
        DB::table('products')->insert([
            'name' => 'Galaxi S9',
            'description' => 'Mobile Phone Samsung',
            'price' => 250
        ]);
    }
}
