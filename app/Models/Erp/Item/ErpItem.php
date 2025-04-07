<?php

namespace App\Models\Erp\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpItem extends Model
{
    use HasFactory, SoftDeletes;

    const unscripted = 'Yeni tip eklenecek... (lütfen iletişime geçiniz)';
    
    protected $fillable = [
        'unit_id',
        'code',
        'name',
        'content',
        'type',
        'barcode',
        'variety_id',
    ];

    public function itemToUnit()
    {
        return $this->hasOne(ErpUnit::class, 'id', 'unit_id');
    }

    public function itemToVariety()
    {
        return $this->hasOne(ErpItemVariety::class, 'id', 'variety_id');
    }

    public function barcodes()
    {
        return $this->hasMany(ErpBarcode::class, 'item_id', 'id');
    }

    public function stocks()
    {
        return $this->hasMany(ErpItemsWarehouses::class, 'item_id', 'id');
    }

    public function getType()
    {
        $type = self::unscripted;

        switch ($this->type) {
            case 0: $type = 'Ham madde'; break;
            case 1: $type = 'Yarı mamül'; break;
            case 2: $type = 'Ürün'; break;
        }

        return $type;

    }

    public function stock($warehouseId)
    {
        return ErpItemsWarehouses::where('item_id', $this->id)
            ->where('warehouse_id', $warehouseId)
            ->first();
    }

    public function movements()
    {
        return $this->hasMany(\App\Models\Erp\Warehouse\ErpStockMovement::class, 'item_id', 'id');
    }

    public function stockTaking()
    {
        return $this->hasOne(\App\Models\Erp\StockTaking\ErpStockTaking::class, 'item_id', 'id');
    }

    public function location($itemID,$warehouseID)
    {
        return ErpItemsLocation::where('item_id',$itemID)->where('warehouse_id',$warehouseID)->get();
    }
}
