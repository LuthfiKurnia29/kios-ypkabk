<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'hero_image',
                'value' => null,
                'type' => 'image',
            ],
            [
                'key' => 'hero_image_alt',
                'value' => 'Karya dan kegiatan anak binaan Kios Mandiri YPKABK',
                'type' => 'string',
            ],
            [
                'key' => 'hero_title',
                'value' => 'Barang preloved, *peluang baru* untuk anak berkebutuhan khusus.',
                'type' => 'text',
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Donasi barang layak pakai dan hasil karya adik-adik disabilitas kami kelola untuk operasional ruang terapi gratis dan pelatihan vokasi mandiri di Sidoarjo dan Mojokerto.',
                'type' => 'text',
            ],
            [
                'key' => 'abk_trained_count',
                'value' => '38',
                'type' => 'number',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
