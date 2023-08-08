<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    private array $brandNames = [
        'Electrolux',
        'Brastemp',
        'Fischer',
        'Samsung',
        'LG',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::factory()->createMany(array_map(fn($brand) => ['name' => $brand], $this->brandNames));
    }
}
