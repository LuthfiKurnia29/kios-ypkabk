<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Kategori Produk
            [
                'name' => 'Karya Vokasi ABK',
                'slug' => 'karya-abk',
                'type' => 'product',
                'icon' => '🎨',
                'description' => 'Karya kreatif dan kriya buatan anak istimewa binaan YPKABK',
            ],
            [
                'name' => 'Pakaian Anak & Dewasa',
                'slug' => 'pakaian',
                'type' => 'product',
                'icon' => '👕',
                'description' => 'Pakaian preloved lolos seleksi dan kurasi higienis',
            ],
            [
                'name' => 'Buku & Mainan Edukasi',
                'slug' => 'buku-mainan',
                'type' => 'product',
                'icon' => '📚',
                'description' => 'Buku bacaan, ensiklopedia, dan mainan stimulasi sensorik',
            ],
            [
                'name' => 'Dekorasi Rumah',
                'slug' => 'dekorasi',
                'type' => 'product',
                'icon' => '🪴',
                'description' => 'Alat terapi, pernak-pernik rumah, dan kriya kayu ramah lingkungan',
            ],

            // Kategori Donasi
            [
                'name' => 'Pakaian Anak / Dewasa Layak Pakai',
                'slug' => 'donasi-pakaian',
                'type' => 'donation',
                'icon' => '👕',
                'description' => 'Pakaian bersih, tidak sobek, dan siap disortir',
            ],
            [
                'name' => 'Buku Bacaan / Ensiklopedia',
                'slug' => 'donasi-buku',
                'type' => 'donation',
                'icon' => '📖',
                'description' => 'Buku cerita, komik edukasi, dan ensiklopedia anak',
            ],
            [
                'name' => 'Mainan Edukatif / Puzzle',
                'slug' => 'donasi-mainan',
                'type' => 'donation',
                'icon' => '🧩',
                'description' => 'Mainan balok, sensory toys, puzzle lengkap',
            ],
            [
                'name' => 'Bahan Prakarya (Kain / Benang / Kanvas)',
                'slug' => 'donasi-prakarya',
                'type' => 'donation',
                'icon' => '🧵',
                'description' => 'Bahan baku untuk kelas kriya & vokasi siswa',
            ],
            [
                'name' => 'Peralatan Terapi / Lainnya',
                'slug' => 'donasi-terapi',
                'type' => 'donation',
                'icon' => '🩺',
                'description' => 'Alat peraga terapi okupasi, sensori, atau motorik',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
