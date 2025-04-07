<?php

namespace App\Models\Erp\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpItemsPrices extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'pricetype',
        'active',
    ];
}
