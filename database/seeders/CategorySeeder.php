<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = [
            'Developpement Web',
            'Design',
            'Langues',
            'Marketing',
            'Bureautique',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'description' => 'Category for '.$name,
                    'is_active' => true,
                ]
            );
        }
    }
}
