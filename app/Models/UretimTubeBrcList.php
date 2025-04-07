<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimTubeBrcList extends Model
{
    use HasFactory;
    
    protected $table = "uretim_tube_brclist";
    protected $fillable = [
        'name',
    ];
    
    public function lisToLin()
    {
        return $this->hasMany(UretimTubeBrcLine::class,'listId','id');
    }
}
