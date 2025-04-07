<?php

namespace App\Models\Kgs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KgsGiris extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "kgs_giris";

    protected $fillable = [
        'kgs_id',
        'giris',
    ];

}
