<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixturesItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'barcode',
        'location',
        'section',
        'floor',
        'room_code',
        'content',
        'item_name',
        'brand',
        'amount',
    ];
}
