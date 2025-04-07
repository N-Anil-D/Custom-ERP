<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpWorkOrder extends Model
{
    use HasFactory;

    public function getUpdateUser()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

}
