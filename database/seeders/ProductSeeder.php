<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Gulai Ayam',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?q=80&w=800&auto=format&fit=crop',
                'description' => 'Ayam empuk dimasak dengan bumbu gulai khas Minang yang kaya rempah.',
            ],
            [
                'name' => 'Nasi Rendang',
                'price' => 30000,
                'image' => 'https://images.unsplash.com/photo-1626777557440-2767118129f1?q=80&w=800&auto=format&fit=crop',
                'description' => 'Menu andalan Rendang daging sapi pilihan dengan bumbu yang meresap sempurna.',
            ],
            [
                'name' => 'Sate Padang',
                'price' => 30000,
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800&auto=format&fit=crop',
                'description' => 'Sate lidah sapi dengan kuah kental kuning yang pedas dan gurih.',
            ],
            [
                'name' => 'Sayur Singkong',
                'price' => 8000,
                'image' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?q=80&w=800&auto=format&fit=crop',
                'description' => 'Sayur daun singkong segar sebagai pelengkap hidangan Minang Anda.',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
