<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{ResetPasswordController, EmailToTelController, UserLastLogin};
use App\Http\Controllers\{NotifyController, PortalController, PsBoxController, KgsController};
use App\Http\Controllers\Settings\{SideBarController, UserController, RoadMapController, LogController};
use App\Http\Controllers\Luca\LucaController;
use App\Http\Controllers\INVAlogistic\InvaLogisticBarcodeController;
use App\Http\Controllers\Uretim\InvaUretimController;
use App\Http\Controllers\Fixtures\FixturesController;
use App\Http\Controllers\Erp\DevFunctions;
use Illuminate\Support\Facades\Http;


# password reset (parola sıfırlama)
Route::controller(ResetPasswordController::class)->group(function () {
    Route::post('check-tel-no',                 'checkTelNo')                                       ->name('password.reset');
    Route::get ('validate-tel-no/{telNo}',      'validateTelNo')                                    ->name('password.validate.tel');
    Route::post('validate-code',                'validateCode')                                     ->name('password.validate.code');
    Route::get ('set-pass/{telNo}/{code}',      'showSetPassword')                                  ->name('password.set.show');
    Route::post('set-password',                 'setPassword')                                      ->name('password.set');
});

# email to tel match (e-posta adresi ile giriş yönteminden telefon no ile giriş şekline geçiş)
Route::controller(EmailToTelController::class)->group(function () {
    Route::get ('email-validate',               'emailValidate')                                    ->name('emailToTel.email.validate');
    Route::post('email-validate-control',       'emailValidateControl')                             ->name('emailToTel.validate.control');
    Route::get ('email-link-validate/{token}',  'emailLinkValidate')                                ->name('emailToTel.email.link.validate');
    Route::post('email-to-tel-no/set',          'setTelNo')                                         ->name('emailToTel.set.telno');
});

# main-root (portal root)
Route::get('/',                                 [PortalController::class, 'index'])                 ->name('index');
Route::post('/theme',                           [PortalController::class, 'theme'])                 ->name('portal.theme');

# password box (parola kutum) - for personal 
Route::get('ps-box',                            [PsBoxController::class, 'index'])                  ->name('psbox');

# notification (onay beklediklerim / onay vereceklerim) - for personal
Route::get('bildirimlerim',                     [NotifyController::class, 'index'])                 ->name('myNotify');
Route::get('bekleyen-taleplerim',               [NotifyController::class, 'demand'])                ->name('myWaitingDemands');

# Last login check
Route::get('son-kayit',               [UserLastLogin::class, 'userLastAction'])                ->name('usersLastAction');

# test route
Route::prefix('test')->group(function () {
    # template
        // Route::get('template',                      [PortalController::class,'template'])               ->name('portal.template');
    # mail view test
        // Route::get('mailTest',                      [PortalController::class,'mailTest'])               ->name('portal.mailTest');
        // Route::get('mailTestV2',                    [PortalController::class,'mailTestV2'])             ->name('portal.mailTestV2');
        // Route::get('production-report',             [PortalController::class,'productionReport']);
    # login version root
        // Route::view('loginV1',  'auth.old.loginV1');
    # Route::view('loginV2',  'auth.old.loginV2'); // require route('password.email') login sistemi değişikliği yapıldığından artık çalışmaz.
});

# settings root [Panel ayarları]
Route::prefix('settings')->group(function () {
    Route::get('sidebar',                       [SideBarController::class, 'index'])                ->name('set.side');
    Route::get('user',                          [UserController::class,'index'])                    ->name('set.user');
    Route::get('roadMap',                       [RoadMapController::class,'index'])                 ->name('set.road');
    Route::get('roadMap/{id}',                  [RoadMapController::class,'subIndex'])              ->name('set.subRoad');
    Route::get('logs',                          [LogController::class, 'index'])                    ->name('set.logs');
});

# luca root [Luca Entegrasyon]
Route::prefix('luca')->group(function () {
    Route::get('stok-listesi',                  [LucaController::class,'stokListesi'])              ->name('luca.stok');
    Route::get('integration-manuel',            [LucaController::class,'integrationManuel'])        ->name('luca.integrationManuel');
    Route::get('stok-alert-manuel',             [LucaController::class,'stockAlertManuel'])         ->name('luca.stockAlertManuel');
});

// INVAlogistic root [INVAlogistic]
Route::prefix('INVAlogistic')->group(function () {
    Route::get('barcode',                       [InvaLogisticBarcodeController::class, 'index'])     ->name('INVlogistic.barcode');
    Route::get('barcode/pdf/{id}/{type}',       [InvaLogisticBarcodeController::class, 'pdf'])       ->name('INVlogistic.barcode.pdf');
    Route::get('item-definition',               [InvaLogisticBarcodeController::class, 'index2'])    ->name('INVlogistic.item.definition');
    Route::get('item-definition/pdf/{id}',      [InvaLogisticBarcodeController::class, 'pdf2'])      ->name('INVlogistic.item.definition.pdf');
});

# uretim root [Barkod üretimi]
Route::prefix('uretim')->group(function () {
    # barcode-package [Barkod oluştur - Paket]
    Route::get('barcode-package',               [InvaUretimController::class, 'barcodePackage'])    ->name('uretim.barcode-package');
    Route::get('barcode-package/download/{id}', [InvaUretimController::class, 'packageDownload'])   ->name('uretim.barcode-package.download');
    Route::get('barcode-package/see/{id}',      [InvaUretimController::class, 'packageSee'])        ->name('uretim.barcode-package.see');
    # barcode-tube [Barkod oluştur - Tüp]
    Route::get('barcode-tube',                  [InvaUretimController::class, 'barcodeTube'])       ->name('uretim.barcode-tube');
    Route::get('barcode-tube/download/{id}',    [InvaUretimController::class, 'tubeDownload'])      ->name('uretim.barcode-tube.download');
    Route::get('barcode-tube/see/{id}',         [InvaUretimController::class, 'tubeSee'])           ->name('uretim.barcode-tube.see');
    # barcode-serum [Barkod oluştur - Serum]
    Route::get('barcode-serum',                 [InvaUretimController::class, 'barcodeSerum'])      ->name('uretim.barcode-serum');
    Route::get('barcode-serum/download/{id}',   [InvaUretimController::class, 'serumDownload'])     ->name('uretim.barcode-serum.download');
    Route::get('barcode-serum/see/{id}',        [InvaUretimController::class, 'serumSee'])          ->name('uretim.barcode-serum.see');
    # diğer etiketler [Diğer barkodlar]
        # mini & besiyeri [Koli - Tüp (Mini-bsyr.)]
    Route::get('mini/{type}',                   [InvaUretimController::class, 'mini'])              ->name('uretim.mini');
    Route::get('mini/{type}/download/{id}',     [InvaUretimController::class, 'miniDownload'])      ->name('uretim.mini.download');
    Route::get('mini/{type}/see/{id}',          [InvaUretimController::class, 'miniSee'])           ->name('uretim.mini.see');
});

# erp root [ERP root]
require __DIR__.'/erp.php';

# demirbas root [Demirbaşlar]
Route::prefix('demirbas')->group(function () {
    // listele
    Route::get('listele',                       [FixturesController::class, 'index'])               ->name('demirbas.index');
    Route::get('pdf/{id}/{type}',               [FixturesController::class, 'download'])            ->name('demirbas.download');
});

# secret root -- for admin information
Route::prefix('secret-root')->group(function () {
    Route::get('{secretParam}/{informationType}', function ($secretParam, $informationType) {
        if($secretParam == env('SECRET_ROOT_KEY')){
            switch ($informationType) {
                case 'phpinfo':
                    echo phpinfo();
                    break;
                case 'laravelinfo':
                    echo 'Laravel version : '.app()->version();
                    break;
                default:
                    echo 'information request type is invalid';
                    break;
            }
        } else {
            echo 'secret parameter is invalid';
        }
    });
});


Route::prefix('kgs')->group(function () {
    
    Route::get('/', [KgsController::class,'index'])->name('kgs.table.list');
    
});

Route::prefix('dev-online')->group(function () {
    Route::get('',                              [DevFunctions::class, 'index'])->name('dev.index');
    Route::post('addStockTaking',               [DevFunctions::class, 'importErpItems'])->name('import.erp.items');
    Route::post('addStockToRoom',               [DevFunctions::class, 'importErpItemsToWarehouse'])->name('import.erp.items.to.warehouse');
    Route::post('addLocationToWarehouseItem',   [DevFunctions::class, 'importErpItemLocations'])->name('import.erp.item.location');
    Route::post('addFinishedProduct',           [DevFunctions::class, 'importErpFinishedProducts'])->name('import.erp.finished.product');
    Route::get('sqlQuery',                      [DevFunctions::class, 'sqlUpdate'])->name('dev.sql.query');
})->middleware('developer.access');


# örnek bağlantı api route
# local de htaccess ssl kuralı kapalı olacak
// Route::get('portal-api-test', function () {
//     return Http::withHeaders([
//         'Authorization' => 'Bearer KpVGXgeYb4EeVBRFaL1lXINdkyp7OPl9SPkBPfPb'
//     ])->get('#test/portal-api-test');
// });