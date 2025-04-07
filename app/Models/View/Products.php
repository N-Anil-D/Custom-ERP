<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    const unscripted = 'Yeni tip eklenecek... (lütfen iletişime geçiniz)';

    public function getType()
    {
        $type = self::unscripted;

        switch ($this->erp_items_type) {
            case 0: $type = 'Ham madde'; break;
            case 1: $type = 'Yarı mamül'; break;
            case 2: $type = 'Ürün'; break;
        }

        return $type;

    }
}
