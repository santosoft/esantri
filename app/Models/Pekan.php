<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekan extends Model
{
    use HasFactory;

    protected $appends = ['periode'];
    public $incrementing = false;

    public function getPeriodeAttribute() {
        return date("M'y", mktime(0,0,0, $this->bulan, 15, $this->tahun));
    }
}
