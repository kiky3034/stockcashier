<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Food',
            'Drink',
            'Household',
            'Personal Care',
            'Stationery',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category],
                [
                    'slug' => Str::slug($category),
                    'is_active' => true,
                ]
            );
        }

        $units = [
            ['name' => 'Piece', 'abbreviation' => 'pcs'],
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Liter', 'abbreviation' => 'l'],
            ['name' => 'Milliliter', 'abbreviation' => 'ml'],
            ['name' => 'Box', 'abbreviation' => 'box'],
            ['name' => 'Pack', 'abbreviation' => 'pack'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['abbreviation' => $unit['abbreviation']],
                [
                    'name' => $unit['name'],
                    'is_active' => true,
                ]
            );
        }

        Supplier::firstOrCreate(
            ['name' => 'Default Supplier'],
            [
                'phone' => null,
                'email' => null,
                'address' => null,
                'is_active' => true,
            ]
        );

        Warehouse::firstOrCreate(
            ['code' => 'MAIN'],
            [
                'name' => 'Main Warehouse',
                'address' => null,
                'is_default' => true,
                'is_active' => true,
            ]
        );

        Warehouse::firstOrCreate(
            ['code' => 'STORE'],
            [
                'name' => 'Store Front',
                'address' => null,
                'is_default' => false,
                'is_active' => true,
            ]
        );
    }
}