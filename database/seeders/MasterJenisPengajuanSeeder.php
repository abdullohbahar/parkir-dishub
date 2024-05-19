<?php

namespace Database\Seeders;

use App\Models\JenisPengajuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterJenisPengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenisPengajuan = JenisPengajuan::create([
            'jenis' => 'Tepi Jalan'
        ]);

        $data = [
            [
                'tipe' => 'Tetap',
            ],
            [
                'tipe' => 'Isidental',
            ],
        ];

        $jenisPengajuan->hasManyTipePengajuan()->createMany($data);

        $jenisPengajuan = JenisPengajuan::create([
            'jenis' => 'Khusus Parkir'
        ]);

        $data = [
            [
                'tipe' => 'Tetap',
            ],
            [
                'tipe' => 'Isidental',
            ],
            [
                'tipe' => 'Pariwisata'
            ],
            [
                'tipe' => 'Perorangan'
            ],
            [
                'tipe' => 'Swasta'
            ],
        ];

        $jenisPengajuan->hasManyTipePengajuan()->createMany($data);
    }
}
