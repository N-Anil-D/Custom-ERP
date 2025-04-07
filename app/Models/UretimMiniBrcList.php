<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimMiniBrcList extends Model
{
    use HasFactory;
    protected $table = 'uretim_mini_brclist';
    protected $fillable = [
        'name',
        'type',
    ];

    public function lisToLin()
    {
        return $this->hasMany(UretimMiniBrcLine::class,'listId','id');
    }

    /*
    CREATE TABLE `uretim_mini_brclist` ( `id` int(11) NOT NULL, `name` varchar(255) DEFAULT NULL, `type` varchar(255) DEFAULT NULL, `created_at` datetime NOT NULL, `updated_at` datetime NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    */

    /**
     * 
     * 12x6 etiket 1
     * 1,5x 2,5 etiket 2
     */
}
