<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instype extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "instype";
    protected $guarded = [];
    public $incrementing = false;
}
