<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    use HasFactory;

    protected $table = 'sidebar';
    protected $fillable = [
        'name',
        'icon',
        'hid',
        'link',
        'line'
    ];


////planlanan şekilde kullanılamadı.
    //model-dışAnahtar-içAnahtar
//    public function sidToIco()
//    {
//        return $this->hasOne(Icon::class,'id','icon');
//    }

    public function sidToSub()
    {
        return $this->hasMany(Sidebar::class,'hid','id');
    }

    /* 
    

SELECT * FROM `sidebar`


id	name	icon	hid	link	line	created_at	updated_at	
14	Mini etiket		10	uretim/mini	34	2022-06-06 11:15:02	2022-06-06 11:15:02	
15	Koli Etiketi	<i class="fas fa-barcode"></i>	14	uretim/mini/package	35	2022-06-06 11:15:48	2022-06-06 11:19:10	
16	Tüp Etiketi	<i class="fas fa-barcode"></i>	14	uretim/mini/tube	36	2022-06-06 11:17:11	2022-06-06 11:18:44	


    */

}
