<?php

namespace App\Models\Erp\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpItemsPersonalActions extends Model
{
    use HasFactory;

    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
