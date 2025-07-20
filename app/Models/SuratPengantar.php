<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengantar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mahasiswa_id',
        'nomor_surat_pengantar',
        'lokasi_kp_surat_pengantar',
        'penerima_surat_pengantar',
        'alamat_surat_pengantar',
        'tembusan_surat_pengantar',
        'status_surat_pengantar',
        'catatan_surat_pengantar',
        'tanggal_pengajuan_surat_pengantar',
        'tanggal_disetujui_surat_pengantar',
        'tanggal_pengambilan_surat_pengantar',
    ];

    /**
     * Get the mahasiswa that owns the surat pengantar.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
