<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karya = Category::where('slug', 'karya-abk')->first();
        $pakaian = Category::where('slug', 'pakaian')->first();
        $buku = Category::where('slug', 'buku-mainan')->first();
        $dekorasi = Category::where('slug', 'dekorasi')->first();

        $products = [
            [
                'name' => 'Tote Bag Kanvas Lukis Pelangi Harapan',
                'category_id' => $karya?->id,
                'price' => 45000,
                'stock' => 3,
                'status' => 'ready',
                'isEmpty' => false,
                'is_featured' => true,
                'source' => 'karya',
                'condition' => 'baru',
                'artisan_name' => 'Tim Vokasional Siswa YPKABK',
                'image' => 'images/tote-bag.jpg',
                'description' => 'Totebag katun tebal ukuran 36x40cm, dilukis manual dengan cat akrilik ramah lingkungan. Dilengkapi furing blacu lembut.',
            ],
            [
                'name' => 'Boneka Rajut Amigurumi Kelinci',
                'category_id' => $karya?->id,
                'price' => 35000,
                'stock' => 2,
                'status' => 'ready',
                'isEmpty' => false,
                'is_featured' => true,
                'source' => 'karya',
                'condition' => 'baru',
                'artisan_name' => 'Kelas Rajut Vokasi',
                'image' => 'images/crochet-bunny.jpg',
                'description' => 'Boneka rajut tangan benang katun susu lembut (non-alergenik). Cocok untuk stimulasi taktil anak.',
            ],
            [
                'name' => 'Set Ensiklopedia Anak (5 Jilid)',
                'category_id' => $buku?->id,
                'price' => 95000,
                'stock' => 1,
                'status' => 'ready',
                'isEmpty' => false,
                'is_featured' => true,
                'source' => 'preloved',
                'condition' => 'mulus',
                'artisan_name' => 'Buku Preloved Istimewa',
                'image' => 'images/books.jpg',
                'description' => 'Buku hardcover seri Mengenal Alam & Sains Dunia. Halaman lengkap tanpa coretan, kondisi sangat terawat.',
            ],
            [
                'name' => 'Jaket Denim Anak Dinosaurus',
                'category_id' => $pakaian?->id,
                'price' => 50000,
                'stock' => 1,
                'status' => 'ready',
                'isEmpty' => false,
                'is_featured' => true,
                'source' => 'preloved',
                'condition' => 'mulus',
                'artisan_name' => 'Pakaian Preloved Terpilih',
                'image' => 'images/denim-jacket.jpg',
                'description' => 'Jaket jeans tebal anak usia 4-6 tahun. Warna denim pekat, kancing utuh, sudah dicuci & disanitasi.',
            ],
            [
                'name' => 'Kalung Manik Mutiara Ceria',
                'category_id' => $karya?->id,
                'price' => 28000,
                'stock' => 5,
                'status' => 'ready',
                'isEmpty' => false,
                'is_featured' => false,
                'source' => 'karya',
                'condition' => 'baru',
                'artisan_name' => 'Kelas Kriya Sensori Siswa',
                'image' => 'images/tote-bag.jpg',
                'description' => 'Kalung manik pastel melatih motorik halus siswa ABK. Pengait magnetik praktis dan aman untuk anak.',
            ],
            [
                'name' => 'Papan Titian Keseimbangan Kayu (Balance Board)',
                'category_id' => $dekorasi?->id,
                'price' => 65000,
                'stock' => 1,
                'status' => 'ready',
                'isEmpty' => false,
                'is_featured' => false,
                'source' => 'preloved',
                'condition' => 'mulus',
                'artisan_name' => 'Alat Terapi Preloved Terpilih',
                'image' => 'images/books.jpg',
                'description' => 'Papan keseimbangan kayu pinus pernis ramah anak. Sempurna untuk latihan koordinasi motorik kasar di rumah.',
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
