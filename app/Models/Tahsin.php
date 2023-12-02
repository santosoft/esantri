<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tahsin extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['periode'];
    protected $guarded = ['id'];

    public function pekan(): BelongsTo
    {
        return $this->belongsTo(Pekan::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function muhaffizh(): BelongsTo
    {
        return $this->belongsTo(Muhaffizh::class);
    }

    public function getPeriodeAttribute() {
        return $this->pekan?->periode;
    }
}
