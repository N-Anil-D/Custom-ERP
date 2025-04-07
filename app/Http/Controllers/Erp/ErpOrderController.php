<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Erp\Order\ErpOrder;
use Illuminate\Http\Request;

class ErpOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:erp-siparis-islemleri');
    }

    public function createOrder()
    {
        $title = 'Sipariş oluştur';
        return view('erp.order.create-order', compact('title'));
    }

    public function createOrderItem($orderId)
    {
        $order = ErpOrder::find($orderId);
        if($order->order_status != 0){
            session()->flash('error', trans('site.accessDenied'));            
            return redirect()->route('index');
        }
        $title = 'Siparişe ürün ekle';
        return view('erp.order.create-order-item', compact('title'));
    }

}
