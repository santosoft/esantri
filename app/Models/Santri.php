<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Santri extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['foto' => 'array'];
    protected $fillable = ['no_induk', 'nama', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'gender'
        , 'nama_ayah', 'nama_ibu', 'no_hp', 'mulai_belajar', 'angkatan_kelas', 'grade', 'level_santri'];
    protected $guarded = ['id'];
    protected $purgeable = ['periodeRapor','pekanRapor'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->where('role', 'Santri');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function muhaffizh(): BelongsTo
    {
        return $this->belongsTo(Muhaffizh::class);
    }

    public function tahsins(): HasMany
    {
        return $this->hasMany(Tahsin::class);
    }

    public function tahfizhs(): HasMany
    {
        return $this->hasMany(Tahfizh::class);
    }

    public function mutqins(): HasMany
    {
        return $this->hasMany(Mutqin::class);
    }

    public function matans(): HasMany
    {
        return $this->hasMany(Matan::class);
    }

    public function absens(): HasMany
    {
        return $this->hasMany(Absen::class);
    }
}
