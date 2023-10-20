<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    // use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nama', 'keterangan'];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function muhaffizhs(): HasMany
    {
        return $this->hasMany(Muhaffizh::class);
    }
}
