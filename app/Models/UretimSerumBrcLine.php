<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimSerumBrcLine extends Model
{
    use HasFactory;

    protected $table = 'uretim_serum_brcline';
    protected $fillable = [
        'listId',
        'title',
        'subtitle',
        'size',
        'color',
        'quantity',
        'ref',
        'lot',
        'date1',
        'barcode',
    ];
}
