<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@palmtrade.demo'],
            [
                'name' => 'PalmTrade Demo Admin',
                'password' => 'password',
                'role' => 'admin',
                'company_name' => 'PalmTrade Demo',
                'country' => 'Indonesia',
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'buyer@palmtrade.demo'],
            [
                'name' => 'Demo Buyer',
                'password' => 'password',
                'role' => 'buyer',
                'company_name' => 'Demo Import Company',
                'country' => 'Singapore',
            ],
        );

        foreach ([
            [
                'name' => 'Crude Palm Oil',
                'sku' => 'PT-CPO-001',
                'category' => 'Crude Palm Oil',
                'description' => 'Demo catalogue entry for crude palm oil supplied for commercial inquiry. Final product parameters and trade terms are subject to quotation.',
                'specifications' => ['product_type' => 'Crude palm oil', 'colour' => 'Subject to agreed specification', 'packing' => 'Bulk or as agreed'],
                'image' => 'https://images.unsplash.com/photo-1600932713394-7e335f8b9061?auto=format&fit=crop&w=900&q=85',
                'indicative_price' => 890.00,
                'currency' => 'USD',
                'unit' => 'MT',
                'moq' => 100.00,
                'stock_capacity' => null,
                'origin' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Refined Palm Oil',
                'sku' => 'PT-RPO-001',
                'category' => 'Refined Palm Oil',
                'description' => 'Demo catalogue entry for refined palm oil for buyers seeking a structured quotation process.',
                'specifications' => ['product_type' => 'Refined palm oil', 'packing' => 'Bulk or as agreed', 'documentation' => 'Confirmed during quotation'],
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=900&q=85',
                'indicative_price' => 945.00,
                'currency' => 'USD',
                'unit' => 'MT',
                'moq' => 100.00,
                'stock_capacity' => null,
                'origin' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Palm Kernel',
                'sku' => 'PT-PK-001',
                'category' => 'Palm Kernel',
                'description' => 'Demo catalogue entry for palm kernel. Supply availability and final specification are discussed with the export team.',
                'specifications' => ['product_type' => 'Palm kernel', 'packing' => 'Bulk or as agreed', 'origin' => 'Indonesia'],
                'image' => 'https://images.unsplash.com/photo-1515823662972-da6cf2d2d6e4?auto=format&fit=crop&w=900&q=85',
                'indicative_price' => null,
                'currency' => 'USD',
                'unit' => 'MT',
                'moq' => 50.00,
                'stock_capacity' => null,
                'origin' => 'Indonesia',
                'is_active' => true,
            ],
        ] as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
