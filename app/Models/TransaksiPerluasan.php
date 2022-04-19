<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPerluasan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "transaksi_perluasan";
    protected $guarded = [];
}
