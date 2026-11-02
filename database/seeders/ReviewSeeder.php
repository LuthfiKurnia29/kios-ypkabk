<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'name' => 'Nanie Kurniawati',
                'role' => 'Donatur Preloved • Sidoarjo',
                'quote' => 'Sempat bingung pakaian anak menumpuk mau dikemanakan. Di Kios Mandiri YPKABK prosesnya mudah, laporannya transparan, dan senang sekali melihat hasilnya membuka ruang terapi bagi anak-anak istimewa.',
                'theme_color' => 'sun',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Dimas Prasetyo',
                'role' => 'Pembeli & Orang Tua Asuh • Mojokerto',
                'quote' => 'Saya beli papan titian dan tote bag rajut. Kualitasnya sangat rapi, packing ramah lingkungan, dan yang terpenting menyokong anak-anak istimewa jadi lebih mandiri.',
                'theme_color' => 'cream',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Ibu Endah Wulandari',
                'role' => 'Orang Tua Siswa & Donatur • Mojokerto',
                'quote' => 'Pelatihan keterampilan di sentra Mojokerto sangat bermakna bagi putra kami. Kini dia jauh lebih percaya diri dan bangga melihat karya kerajinannya bisa diapresiasi serta dibeli oleh banyak orang.',
                'theme_color' => 'olive',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($reviews as $review) {
            Review::updateOrCreate(
                ['name' => $review['name']],
                $review
            );
        }
    }
}
