<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequential extends Model
{
    use HasFactory;
    protected $table = "sequential";
    protected $guarded = [];
}
