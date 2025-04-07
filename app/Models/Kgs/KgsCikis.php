<?php

namespace App\Models\Kgs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KgsCikis extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "kgs_cikis";

    protected $fillable = [
        'kgs_id',
        'cikis',
    ];


}
