<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perluasan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "perluasan";
    protected $guarded = [];
}
