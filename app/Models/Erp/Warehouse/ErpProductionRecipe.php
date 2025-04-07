<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class ErpProductionRecipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "erp_production_recipes";

    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }

    public function recipeAltItems()
    {
        return $this->hasMany(ErpProductionRecipe::class, 'recipe_id', 'id');
    }


}
