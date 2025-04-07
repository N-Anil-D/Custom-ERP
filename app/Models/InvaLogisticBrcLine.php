<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvaLogisticBrcLine extends Model
{
    use HasFactory;
    
    protected $table = "invalogistic_brcline";
    protected $fillable = [
        'listId',
        'lot',
        'lotDate',
        'ref',
        'barcode',
        'name',
        'amount',
        'content'
    ];
}
