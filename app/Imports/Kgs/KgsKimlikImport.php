<?php

namespace App\Imports\Kgs;

use App\Models\Kgs\KgsKimlikleri;
use Maatwebsite\Excel\Concerns\{ToModel,WithHeadingRow};

class KgsKimlikImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function __construct(){
        KgsKimlikleri::truncate();
    }
    public function model(array $row)
    {
        return new KgsKimlikleri([
            'kgs_id' => $row['kgs_id'],
            'name' => $row['name'],
            'shift' => 0,
        ]);
    }
}