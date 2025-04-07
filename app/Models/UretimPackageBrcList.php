<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimPackageBrcList extends Model
{
    use HasFactory;
    
    protected $table = "uretim_package_brclist";
    protected $fillable = [
        'name',
    ];
    
    public function lisToLin()
    {
        return $this->hasMany(UretimPackageBrcLine::class,'listId','id');
    }
}
