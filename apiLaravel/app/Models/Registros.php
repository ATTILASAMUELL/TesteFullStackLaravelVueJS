<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registros extends Model
{
    protected $table = 'registros';
    public $timestamps = false;
    use HasFactory;
}
