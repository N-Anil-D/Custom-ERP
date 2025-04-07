<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UretimMiniBrcLine extends Model
{
    use HasFactory;
    protected $table = 'uretim_mini_brcline';
    protected $fillable = [
        'type',
        'listId',
        'title',
        'subtitle',
        'quantity',
        'barcode',
        'ref',
        'lot',
        'date1',
        'amount',
        'color',
    ];

    /* 
    CREATE TABLE `uretim_mini_brcline` ( `id` bigint(11) NOT NULL, `type` varchar(255) DEFAULT NULL, `listId` int(11) NOT NULL, `title` varchar(255) DEFAULT NULL, `subtitle` varchar(255) DEFAULT NULL, `quantity` int(11) DEFAULT NULL, `barcode` varchar(100) DEFAULT NULL, `ref` varchar(100) DEFAULT NULL, `lot` varchar(255) DEFAULT NULL, `date1` varchar(100) DEFAULT NULL, `amount` varchar(255) DEFAULT NULL, `color` varchar(255) DEFAULT NULL, `created_at` datetime NOT NULL, `updated_at` datetime NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    */
}
