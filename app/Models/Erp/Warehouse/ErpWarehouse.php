<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpWarehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'content',
        'can_take_from_outside',
        'can_send_to_outside',
    ];

    public function approvalDwindling()
    {
        return $this->hasMany(\App\Models\Erp\ErpApproval::class, 'dwindling_warehouse_id', 'id');
    }

    public function approvalIncreased()
    {
        return $this->hasMany(\App\Models\Erp\ErpApproval::class, 'increased_warehouse_id', 'id');
    }
}
