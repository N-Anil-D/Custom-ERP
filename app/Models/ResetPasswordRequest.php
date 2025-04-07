<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPasswordRequest extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tel_no',
        'validate_code',
        'type',
        'created_at',
    ];
}
