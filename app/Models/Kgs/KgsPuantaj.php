<?php

namespace App\Models\Kgs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KgsPuantaj extends Model
{
    use HasFactory;
    protected $table = "kgs_puantaj";

    public function puantajToName()
    {
        return $this->hasOne(KgsKimlikleri::class,'kgs_id','kgs_id');
    }

}
