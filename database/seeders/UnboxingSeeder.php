<?php

namespace Database\Seeders;

use App\Models\Unboxing;
use Illuminate\Database\Seeder;

class UnboxingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => 'Unboxing 3 Box Pakaian Balita Bunda Melati',
                'recorded_at' => '2025-07-12',
                'platform' => 'instagram',
                'url' => 'https://www.instagram.com/p/C8unboxing1/',
                'is_visible' => true,
                'description' => 'Penerimaan pakaian balita layak pakai dari donatur Surabaya.',
            ],
            [
                'title' => 'Buka Paket Alat Lukis & Kuas untuk Kelas Seni ABK',
                'recorded_at' => '2025-07-08',
                'platform' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'is_visible' => true,
                'description' => 'Paket kuas, cat akrilik, dan kanvas untuk adik-adik kelas seni sentra YPKABK.',
            ],
            [
                'title' => 'Unboxing Buku Cerita Bergambar Sahabat Istimewa',
                'recorded_at' => '2025-07-03',
                'platform' => 'instagram',
                'url' => 'https://www.instagram.com/p/C8unboxing2/',
                'is_visible' => true,
                'description' => 'Koleksi buku cerita bergambar ramah anak berkebutuhan khusus.',
            ],
            [
                'title' => 'Paket Mainan Sensori & Balok Kayu Edukatif',
                'recorded_at' => '2025-06-28',
                'platform' => 'instagram',
                'url' => 'https://www.instagram.com/p/C8unboxing3/',
                'is_visible' => false,
                'description' => 'Mainan sensori motorik halus untuk ruang terapi sensori integrasi.',
            ],
            [
                'title' => 'Dokumentasi Serah Terima Meja Kursi Belajar Sentra',
                'recorded_at' => '2025-06-20',
                'platform' => 'youtube',
                'url' => 'https://youtu.be/kJQP7kiw5Fk',
                'is_visible' => true,
                'description' => 'Peralatan meja kursi belajar anak untuk sentra Mojokerto.',
            ],
        ];

        foreach ($items as $item) {
            Unboxing::updateOrCreate(
                ['url' => $item['url']],
                $item
            );
        }
    }
}
