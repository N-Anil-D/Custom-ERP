<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimSerumBrcList extends Model
{
    use HasFactory;

    protected $table = "uretim_serum_brclist";
    protected $fillable = [
        'name',
    ];
    
    public function lisToLin()
    {
        return $this->hasMany(UretimSerumBrcLine::class,'listId','id');
    }
}
