<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Erp\Item\ErpItem;
use App\Models\Erp\Warehouse\ErpWarehouse;
use App\Models\Erp\StockTaking\ErpStockTaking;
use App\Models\User;
use Illuminate\Support\Facades\{Session, Auth};
use NotificationChannels\Telegram\TelegramMessage;

class ErpStockTakingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:erp-stok-sayim');
    }

    
    public function confirmCount()
    {
        $title = 'Sayım onayla';
        return view('erp.stock-taking.confirm-count', compact('title'));
    }

    public function stockTaking()
    {
        $title = 'Stok sayımı';
        // return view('erp.stock-taking.stock-taking', compact('title'));
        // geçici olarak livewire düzeninden blade düzenine dönüldü. component teki frontend javascript sorunu nedeniyle.
    
        $items      = ErpItem::with('itemToUnit')->get();
        $warehouses = ErpWarehouse::get();
        $data       = ErpStockTaking::with('item', 'warehouse', 'countingUser')->where('status', 0)->paginate(50);
    
        return view('erp.stock-taking.stock-taking', compact('title', 'items', 'warehouses', 'data'));
    
    }

    public function addStockTaking(Request $request)
    {
        $validateData = $request->validate([
            'item_id'           => 'required',
            'warehouse_id'      => 'required',
            'amount'            => 'required|numeric'
        ]);
            
        ErpStockTaking::updateOrInsert(
            [
                'item_id'       => $request->item_id,
                'warehouse_id'  => $request->warehouse_id,
                'status'        => 0,
                'counting_user' => Auth::user()->id,
            ],
            [
                'amount'        => $request->amount,
                'deleted_at'    => NULL
            ]
        );

        // $this->dispatchBrowserEvent('refresh-page');
        Session::flash('success', 'Sayım değeri girildi/güncellendi');
        return redirect()->back();
    }

    public function delete($rowId)
    {
        ErpStockTaking::find($rowId)->delete();
        Session::flash('success', trans('site.alert.data.delete.success'));
        return redirect()->back();
    }

    public function confirm()
    {
        if(ErpStockTaking::where('status',1)->count() == 0){
            ErpStockTaking::where('status', 0)->where('counting_user', Auth::user()->id)
                ->update([
                    'status' => 1
                ]);
                $telegramIdArray = User::where('can_confirm_count',1)->where('active',1)->get('telegram_id')->pluck('telegram_id')->filter();
                $waitingCount = ErpStockTaking::where('status',1)->where('counting_user', Auth::user()->id)->count();
                foreach($telegramIdArray as $telegramId){
                    try {
                        TelegramMessage::create()
                            ->to($telegramId)
                            ->line('*DEPO SAYIMI ONAYINIZI BEKLİYOR*')
                            ->line('')
                            ->line(Auth::user()->name.' kullanıcısının oluşturduğu '.$waitingCount.' kalem stok var.')
                            ->line('')
                            ->button('Kontrol et & Onayla', url('/erp-stok-sayim/sayim-onayla'))
                            ->send();
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                
                // dd($telegramIdArray);
            Session::flash('success', 'Sayım verileriniz onaya gönderildi');
        }else{
            Session::flash('error', 'Daha önce onaya gönderilmiş ve bekleyen bir sayım işlemi bulmakta.');
        }

        return redirect()->back();
    }
}
