<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    use HasFactory;
    
    protected $table = "userauth";
    protected $fillable = [
        'userId',
        'urlId'
    ];
    
    public function autToSid()
    {
        return $this->hasOne(Sidebar::class,'id','urlId');
    }
}
