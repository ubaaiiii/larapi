<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPenanggung extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "transaksi_penanggung";
    protected $guarded = [];
}
