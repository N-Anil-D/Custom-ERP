<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LucaStockType extends Model
{
    use HasFactory;
    
    protected $table = 'luca_stocktype';
    protected $fillable = [
        'typeId',
        'name'
    ];
    public $timestamps = FALSE;
    
    //model-dışAnahtar-içAnahtar
    public function typToStk()
    {
        return $this->hasMany(LucaStockList::class,'stokTipi','typeId');
    }
}
