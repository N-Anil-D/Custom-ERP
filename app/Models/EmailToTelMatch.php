<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailToTelMatch extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'validate_code',
        'type',
        'created_at',
    ];
}
