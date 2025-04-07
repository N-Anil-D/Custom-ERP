<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimTubeBrcLine extends Model
{
    use HasFactory;
    
    protected $table = "uretim_tube_brcline";
    protected $fillable = [
        'listId',
        'name',
        'quantity',
        'lot',
        'ref',
        'barcode',
        'date1'
    ];
}
