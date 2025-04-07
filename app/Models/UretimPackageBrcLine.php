<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimPackageBrcLine extends Model
{
    use HasFactory;
    
    protected $table = "uretim_package_brcline";
    protected $fillable = [
        'listId',
        'title',
        'dimensions',
        'barcode',
        'lot',
        'ref',
        'date1',
        'date2',
        'content1',
        'content2',
        'property1',
        'property2',
        'property3',
        'property4',
        'property5',
        'property6',
        'property7',
        'property8',
        'rev1',
        'rev2',
        'quantity',
    ];
}
