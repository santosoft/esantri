<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Muhaffizh extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = ['aktif' => 'boolean'];
    protected $fillable = ['no_induk', 'nama', 'alamat', 'tempat_lahir', 'tanggal_lahir'
        , 'no_hp', 'pendidikan_terakhir', 'mulai_bertugas', 'angkatan_kelas', 'aktif'];
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->where('role', 'Muhaffizh');
    }

    // public function group(): BelongsTo
    // {
    //     return $this->belongsTo(Group::class);
    // }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
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
}
