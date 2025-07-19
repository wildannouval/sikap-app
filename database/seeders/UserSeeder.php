<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data jurusan Teknik Industri untuk dihubungkan
        $jurusanTI = Jurusan::where('kode_jurusan', 'TIS1')->first();

        // 1. Membuat User Bapendik
        User::create([
            'name' => 'Admin Bapendik',
            'email' => 'bapendik@sikap.test',
            'password' => Hash::make('password'),
            'role' => 'bapendik',
        ]);

        // 2. Membuat User Dosen beserta profilnya
        $userDosen = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'dosen@sikap.test',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $userDosen->id,
            'jurusan_id' => $jurusanTI->id,
            'nip' => '198001012005011001',
            'is_komisi' => true, // Jadikan dosen ini sebagai anggota komisi
        ]);

        // 3. Membuat User Mahasiswa beserta profilnya
        $userMahasiswa = User::create([
            'name' => 'Andi Pratama',
            'email' => 'mahasiswa@sikap.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $userMahasiswa->id,
            'jurusan_id' => $jurusanTI->id,
            'nim' => 'H1D022001',
            'tahun_angkatan' => 2022,
        ]);
    }
}
