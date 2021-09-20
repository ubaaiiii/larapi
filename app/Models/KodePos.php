<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodePos extends Model
{
    use HasFactory;
    protected $table = "kodepos";
    protected $guarded = [];

    public function scopeCariKecamatan($query, $value)
    {
        return $query->where('kecamatan', 'like', '%' . $value . '%');
    }

    public function scopeCariKelurahan($query, $value)
    {
        return $query->where('kelurahan', 'like', '%' . $value . '%');
    }

    public function scopeCariKodePos($query, $value)
    {
        return $query->where('kodepos', 'like', '%' . $value . '%');
    }
}
