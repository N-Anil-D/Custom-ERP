<?php

namespace App\Models\Erp\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpItemVariety extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];
}
