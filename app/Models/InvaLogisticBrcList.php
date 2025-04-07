<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvaLogisticBrcList extends Model
{
    use HasFactory;
    
    protected $table = "invalogistic_brclist";
    protected $fillable = [
        'name',
    ];
    
    public function lisToLin()
    {
        return $this->hasMany(InvaLogisticBrcLine::class,'listId','id');
    }
}
