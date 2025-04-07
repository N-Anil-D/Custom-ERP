<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadMapDetail extends Model
{
    use HasFactory;
    
    protected $table = "roadmap_detail";
    protected $fillable = [
        'sectionId',
        'content',
        'type',
        'line'
    ];
}
