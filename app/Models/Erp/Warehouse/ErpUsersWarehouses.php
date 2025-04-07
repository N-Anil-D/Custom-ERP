<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpUsersWarehouses extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'warehouse_id',
    ];

    public function warehouse()
    {
        return $this->hasOne(ErpWarehouse::class, 'id', 'warehouse_id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id')->where('active',1);
    }
    
    public function wherehouseItems()
    {
        return $this->hasMany(\App\Models\Erp\Item\ErpItemsWarehouses::class, 'warehouse_id', 'warehouse_id');
    }
    

}
