<?php

namespace App\Log;

use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;
use App\Models\{UserLog, UserLastAction};

class Log
{
    
    public static function createLog()
    {

        // if(Auth::user()->id != 1){
            $agent      = new Agent();
            $device     = $agent->platform();
            $browser    = $agent->browser();

            $data = [
                'userId'        => (Auth::user()) ? Auth::user()->id : 0,
                'ip'            => \Request::ip(),
                'uri'           => \Request::url(),
                'method'        => \Request::method(),
                'device'        => $device,
                'deviceVer'     => $agent->version($device),
                'browser'       => $browser,
                'browserVer'    => $agent->version($browser)
            ];
            UserLog::create($data);
        // }


        if(UserLastAction::where('user_id',Auth::user()->id)->first()){
            $lastAction = UserLastAction::where('user_id',Auth::user()->id)->first();
            $lastAction->action++;
            $lastAction->save();
        }else{
            $lastAction = new UserLastAction;
            $lastAction->user_id = Auth::user()->id;
            $lastAction->action = 1;
            $lastAction->save();
        }
            
        
    }
}