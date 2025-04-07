<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{NotifyController, PortalController, PsBoxController};
use App\Http\Controllers\Settings\{SideBarController, UserController, RoadMapController, LogController};
use App\Http\Controllers\Luca\LucaController;
use App\Http\Controllers\INVAlogistic\InvaLogisticBarcodeController;
use App\Http\Controllers\Uretim\InvaUretimController;

// portal root
Route::get('/',         [PortalController::class,'index'])  ->name('index');
Route::post('/theme',   [PortalController::class, 'theme']) ->name('portal.theme');

// password box
Route::get('ps-box',    [PsBoxController::class, 'index'])  ->name('psbox');

// bildirimlerim
Route::get('bildirimlerim', [NotifyController::class, 'index'])->name('myNotify');
Route::get('bekleyen-taleplerim', [NotifyController::class, 'demand'])->name('myWaitingDemands');

// test route
Route::prefix('test')->group(function () {
    // template
    Route::get('template',          [PortalController::class,'template'])   ->name('portal.template');
    // mail view test
    Route::get('mailTest',          [PortalController::class,'mailTest'])   ->name('portal.mailTest');
    Route::get('mailTestV2',        [PortalController::class,'mailTestV2']) ->name('portal.mailTestV2');
    Route::get('production-report', [PortalController::class,'productionReport']);
    //login version root
    Route::view('loginV1',  'auth.loginV1');
    Route::view('loginV2',  'auth.loginV2');
});

// settings root
Route::prefix('settings')->group(function () {
    Route::get('sidebar',       [SideBarController::class, 'index'])     ->name('set.side');
    Route::get('user',          [UserController::class,'index'])         ->name('set.user');
    Route::get('roadMap',       [RoadMapController::class,'index'])      ->name('set.road');
    Route::get('roadMap/{id}',  [RoadMapController::class,'subIndex'])   ->name('set.subRoad');
    Route::get('logs',          [LogController::class, 'index'])         ->name('set.logs');
});

// luca root
Route::prefix('luca')->group(function () {
    Route::get('stok-listesi',          [LucaController::class,'stokListesi'])      ->name('luca.stok');
    Route::get('integration-manuel',    [LucaController::class,'integrationManuel'])->name('luca.integrationManuel');
    Route::get('stok-alert-manuel',     [LucaController::class,'stockAlertManuel']) ->name('luca.stockAlertManuel');
});

// INVAlogistic root
Route::prefix('INVAlogistic')->group(function () {
    Route::get('barcode',                  [InvaLogisticBarcodeController::class,'index'])      ->name('INVlogistic.barcode');
    Route::get('barcode/download/{id}',    [InvaLogisticBarcodeController::class,'download'])   ->name('INVlogistic.barcode.download');
    Route::get('barcode/see/{id}',         [InvaLogisticBarcodeController::class,'see'])        ->name('INVlogistic.barcode.see');
});

// uretim root [barcode üretimi]
Route::prefix('uretim')->group(function () {
        // barcode-package
    Route::get('barcode-package',                [InvaUretimController::class, 'barcodePackage'])     ->name('uretim.barcode-package');
    Route::get('barcode-package/download/{id}',  [InvaUretimController::class, 'packageDownload'])    ->name('uretim.barcode-package.download');
    Route::get('barcode-package/see/{id}',       [InvaUretimController::class, 'packageSee'])         ->name('uretim.barcode-package.see');
        // barcode-tube
    Route::get('barcode-tube',                   [InvaUretimController::class, 'barcodeTube'])        ->name('uretim.barcode-tube');
    Route::get('barcode-tube/download/{id}',     [InvaUretimController::class, 'tubeDownload'])       ->name('uretim.barcode-tube.download');
    Route::get('barcode-tube/see/{id}',          [InvaUretimController::class, 'tubeSee'])            ->name('uretim.barcode-tube.see');
        // barcode-serum
    Route::get('barcode-serum',                  [InvaUretimController::class, 'barcodeSerum'])       ->name('uretim.barcode-serum');
    Route::get('barcode-serum/download/{id}',    [InvaUretimController::class, 'serumDownload'])      ->name('uretim.barcode-serum.download');
    Route::get('barcode-serum/see/{id}',         [InvaUretimController::class, 'serumSee'])           ->name('uretim.barcode-serum.see');
        // diğer etiketler
            // mini & besiyeri
    Route::get('mini/{type}',                    [InvaUretimController::class, 'mini'])              ->name('uretim.mini');
    Route::get('mini/{type}/download/{id}',      [InvaUretimController::class, 'miniDownload'])      ->name('uretim.mini.download');
    Route::get('mini/{type}/see/{id}',           [InvaUretimController::class, 'miniSee'])           ->name('uretim.mini.see');
});

// erp root
require __DIR__.'/erp.php';

Route::get('mac', [PortalController::class, 'detectDevice'])->name('detect.mac');
Route::post('save-macinfo', [PortalController::class, 'saveMacinfo'])->name('save.macinfo');