<?php

namespace App\Models\Erp\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpItemsLocation extends Model
{
    protected $fillable = [
        'item_id',
        'warehouse_id',
        'p1',
        'p2',
        'p3',
    ];

}
