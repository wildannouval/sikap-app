<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jurusan_id',
        'nip',
        'is_komisi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
