<?php

namespace Database\Seeders;

use App\Models\ImpactMetric;
use Illuminate\Database\Seeder;

class ImpactMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metrics = [
            [
                'label' => 'Barang Preloved Tersalurkan',
                'value' => '1.420+',
                'description' => 'Dari 1.120+ donatur di Sidoarjo & Mojokerto',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'ABK Terlatih Mandiri',
                'value' => '38 Anak',
                'description' => 'Kelas keterampilan, kriya, dan pelayanan kios',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'Dana Terapi Terkumpul',
                'value' => 'Rp 84,5 Jt',
                'description' => '100% dialokasikan secara transparan',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($metrics as $metric) {
            ImpactMetric::updateOrCreate(
                ['label' => $metric['label']],
                $metric
            );
        }
    }
}
