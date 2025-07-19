<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::create([
            'kode_jurusan' => 'TIS1',
            'nama' => 'Teknik Industri'
        ]);

        Jurusan::create([
            'kode_jurusan' => 'IFS1',
            'nama' => 'Informatika'
        ]);
    }
}
