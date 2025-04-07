<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLastAction extends Model
{
    use HasFactory;

    public function findUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
