<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LucaStockAlert extends Model
{
    use HasFactory;
    
    protected $table = "luca_stockalert";
    protected $fillable = [
        'userId',
        'itemId',
        'name',
        'amount',
        'alertCondition',
        'warned'
    ];
    
    //model-dışAnahtar-içAnahtar
    public function altToIte()
    {
        return $this->hasOne(LucaStockList::class,'id','itemId');
    }
    
    public function altToUsr()
    {
        return $this->hasOne(User::class,'id','userId');
    }
    
    
}
