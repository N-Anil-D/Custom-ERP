<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadMapSection extends Model
{
    use HasFactory;
    
    protected $table = "roadmap_section";
    protected $fillable = [
        'content',
        'type',
        'line',
        'author',
        'animation',
        'animationDelay',
        'active',
    ];
    
    //model-dışAnahtar-içAnahtar
    public function secToDet()
    {
        return $this->hasMany(RoadMapDetail::class,'sectionId','id');
    }
}
