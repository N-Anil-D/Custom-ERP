<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    
    use HasFactory;
    
    protected $table = 'userlog';
    protected $fillable = [
        'userId',
        'ip',
        'uri',
        'method',
        'device',
        'deviceVer',
        'browser',
        'browserVer',
    ];

    public function logToUsr()
    {
        return $this->hasOne(User::class,'id','userId');
    }

    public function deviceIcon()
    {
        $icon = $this->device;

        switch ($icon) {
            case 'iOS':         $icon = '<i class="bx bxl-apple"></i>';     break;
            case 'AndroidOS':   $icon = '<i class="bx bxl-android"></i>';   break;
            case 'Windows':     $icon = '<i class="bx bxl-windows"></i>';   break;
            case 'OS X':        $icon = '<i class="fas fa-laptop"></i>';    break;
            case 'Linux':       $icon = '<i class="fab fa-linux"></i>';     break;
        }

        return $icon;
    }

    public function browserIcon()
    {
        $icon = $this->browser;

        switch ($icon) {
            case 'Chrome':  $icon = '<i class="fab fa-chrome"></i>';         break;
            case 'Edge':    $icon = '<i class="fab fa-edge-legacy"></i>';    break;
            case 'Firefox': $icon = '<i class="bx bxl-firefox"></i>';        break;
            case 'Safari':  $icon = '<i class="fab fa-safari"></i>';         break;
        }

        return $icon;
    }


}
