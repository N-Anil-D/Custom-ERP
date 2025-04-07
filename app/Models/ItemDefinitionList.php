<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDefinitionList extends Model
{
    use HasFactory;

    protected $fillable = [
        'listId',
        'name',
        'entry_date',
        'irsaliye',
        'company_name',
        'amount',
        'lot',
        'last_use_date',
        'controller',
        'suitability'
    ];

}
