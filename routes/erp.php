<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Erp\{ErpOrderController, ErpItemController, ErpWarehouseController, ErpPersonalItemActionsController, ErpStockTakingController, ErpEndProduct,ErpLogisticDataController};

# [Order create] [ERP]
Route::prefix('erp-siparis-islemleri')->group(function () {
    Route::get('siparis-olustur',               [ErpOrderController::class, 'createOrder']);
    Route::get('siparis-urun-ekle/{orderId}',   [ErpOrderController::class, 'createOrderItem'])->name('order.item.add');
});

# [Order create] [ERP]
Route::prefix('storage-data')->group(function () {
    Route::get('/invaLogistic',                             [ErpLogisticDataController::class, 'warehouseDatas']);
    Route::get('/semi-product',                             [ErpLogisticDataController::class, 'warehouseDatas']);
});

# [Genel tanımlamalar] [ERP]
Route::prefix('erp-urun-islemleri')->group(function () {
    Route::get('depolar',                       [ErpItemController::class, 'warehouses']);
    Route::get('birimler',                      [ErpItemController::class, 'units']);
    Route::get('urun-tipi',                     [ErpItemController::class, 'variety']);
    Route::get('urunler',                       [ErpItemController::class, 'items']); 
});

# [Stok ve üretim işlemleri] [ERP]
Route::prefix('erp-stok-islemleri')->group(function () {
    Route::get('urunlerim',                     [ErpWarehouseController::class, 'myProducts'])->name('my.products');
    Route::get('uretim-islemlerim',             [ErpWarehouseController::class, 'myProductions'])->name('my.productions');
    Route::get('uretim-islemlerim/{userId}/{itemId}/{warehouseId}/{productionId}', [ErpWarehouseController::class, 'createProduction'])->name('create.production');
    Route::post('uretim-tamamla',               [ErpWarehouseController::class, 'completeProduction'])->name('complete.production');
    Route::get('is-emri',                       [ErpWarehouseController::class, 'workOrder'])->name('work.order')->middleware('erp.work.order');
});

# [Tüm ürünler & işlemlerim] [ERP]
Route::prefix('erp-kisisel-islemlerim')->group(function () {
    Route::get('/',                             [ErpPersonalItemActionsController::class, 'personalItemAction'])->name('user.item.action');
    Route::get('/odalar',                       [ErpPersonalItemActionsController::class, 'allWarehouseItems'])->name('all.warehouse.items');
    Route::get('/uretim-raporu',                [ErpPersonalItemActionsController::class, 'productionReports'])->name('production.reports');
    Route::post('/kisisel/indir',               [ErpPersonalItemActionsController::class, 'productionReportDownload'])->name('production.personal.report.download');
    Route::post('/indir',                       [ErpPersonalItemActionsController::class, 'productionReportDownload'])->name('production.report.download')->middleware('erp.report');
});

Route::prefix('urun-bitirme')->group(function () {
    Route::get('/steril-etiket-kalite-kutu',   [ErpEndProduct::class, 'sekk'])->name('sekk');
    Route::get('/bitmis-urunler',              [ErpEndProduct::class, 'finishedProducts']);
    Route::get('/gonderilmis-urunler',         [ErpEndProduct::class, 'sendProducts']);
});

# [Stok sayım işlemleri] [ERP]
Route::prefix('erp-stok-sayim')->group(function () {
    Route::get('stok-sayimi',                   [ErpStockTakingController::class, 'stockTaking']);
    Route::get('sayim-onayla',                  [ErpStockTakingController::class, 'confirmCount']);

    //stok-sayımı blade düzeni için geçici yapı
    Route::post('addStockTaking',               [ErpStockTakingController::class, 'addStockTaking'])->name('stockTaking.addStockTaking');
    Route::get('delete/{rowId}',                [ErpStockTakingController::class, 'delete'])->name('stockTaking.delete');
    Route::get('confirm',                       [ErpStockTakingController::class, 'confirm'])->name('stockTaking.confirm');

});