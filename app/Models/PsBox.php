<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsBox extends Model
{
    use HasFactory;

    protected $table = "ps_box";
    protected $fillable = [
        'userId',
        'definition',
        'def1',
        'def2',
        'def3',
    ];
}
