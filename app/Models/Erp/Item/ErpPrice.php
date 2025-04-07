<?php

namespace App\Models\Erp\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
