<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $categories = [
            'Barang Pribadi',
            'Barang Elektronik',
            'Barang Kuliah',
            'Barang Kendaraan',
            'Barang Berharga',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}