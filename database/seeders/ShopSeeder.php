<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Shop\Models\CategoryModel;
use Modules\Shop\Models\ProductModel;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Электроника' => [
                ['name' => 'Смартфон X100', 'price' => 24990, 'quantity' => 15],
                ['name' => 'Беспроводные наушники', 'price' => 3990, 'quantity' => 40],
                ['name' => 'Умные часы', 'price' => 8990, 'quantity' => 0],
                ['name' => 'Портативная колонка', 'price' => 2490, 'quantity' => 25],
            ],
            'Одежда' => [
                ['name' => 'Футболка базовая', 'price' => 990, 'quantity' => 100],
                ['name' => 'Джинсы классические', 'price' => 3490, 'quantity' => 30],
                ['name' => 'Куртка демисезонная', 'price' => 6990, 'quantity' => 12],
                ['name' => 'Кроссовки спортивные', 'price' => 4990, 'quantity' => 0],
            ],
            'Дом и сад' => [
                ['name' => 'Набор кастрюль', 'price' => 5990, 'quantity' => 18],
                ['name' => 'Настольная лампа', 'price' => 1490, 'quantity' => 22],
                ['name' => 'Комнатное растение', 'price' => 790, 'quantity' => 50],
                ['name' => 'Постельное белье', 'price' => 2990, 'quantity' => 35],
            ],
        ];

        $rank = count($categories);

        foreach ($categories as $categoryName => $products) {
            $category = CategoryModel::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'rank' => $rank--]
            );

            foreach ($products as $product) {
                ProductModel::updateOrCreate(
                    ['slug' => Str::slug($product['name'])],
                    [
                        'category_id' => $category->id,
                        'name' => $product['name'],
                        'description' => 'Описание товара «' . $product['name'] . '». Тестовые данные для проверки страниц магазина.',
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                        'display' => true,
                    ]
                );
            }
        }
    }
}
