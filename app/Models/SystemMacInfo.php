<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemMacInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'mac',
        'device',
        'device_ver',
        'browser',
        'browser_ver',
        'user',
        'location',
        'type'
    ];
}
