<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default category if none exists
        $category = Category::firstOrCreate(
            ['name' => 'Sandwiches'],
            ['description' => 'Fresh handcrafted sandwiches']
        );

        $products = [
            [
                'name' => 'Classic Club',
                'description' => 'Turkey, bacon, lettuce, tomato, mayo on toasted bread',
                'price' => 23000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Veggie Delight',
                'description' => 'Fresh vegetables, hummus, sprouts on whole grain',
                'price' => 20000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'BBQ Chicken',
                'description' => 'Grilled chicken, BBQ sauce, cheese, onions',
                'price' => 24000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Italian Sub',
                'description' => 'Salami, pepperoni, provolone, peppers, Italian dressing',
                'price' => 26000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Grilled Cheese',
                'description' => 'Melted cheddar and Swiss cheese on buttered bread',
                'price' => 18000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Spicy Tuna',
                'description' => 'Tuna salad with jalapeños, cilantro, lime mayo',
                'price' => 24000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Crispy Bacon Avocado',
                'description' => 'Bacon, fresh avocado, tomato, lettuce, ranch',
                'price' => 27000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Philly Cheesesteak',
                'description' => 'Sliced steak, melted cheese, peppers, onions',
                'price' => 29000,
                'image_url' => 'images/products/sandwich.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'image_url' => $productData['image_url'],
                'is_available' => $productData['is_available'],
            ]);
        }
    }
}

