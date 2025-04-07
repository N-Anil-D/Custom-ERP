<?php

return [

    /* mail view için */
    'passResetSubject' => 'Parola Sıfırlama Bildirimi',
    'line1' => 'Bu e-postayı, hesabınız için bir şifre sıfırlama isteği aldığımız için alıyorsunuz.',
    'line2' => 'Parola sıfırlama talebinde bulunmadıysanız, bu e-posta iletisini dikkate almayın.',
    'actionButton' => 'Parolamı Sıfırla',
    'subLine' => "Eğer",
    'subLine2' => "butonuna tıklayamıyorsanız, aşağıdaki linki kopyalayıp ",
    'subLine3' => "tarayıcınızın adres çubuğuna yapıştırınız.",
    'hello' => 'Merhaba',
    'regards' => 'Selamlar',
    'footer' => 'All rights reserved.',
    
    /* bildirimler */
    'ops' => '.. :( ..',
    'accessDenied' => 'Üzgünüm :( ... buraya erişim yetkiniz bulunmamaktadır',
    'general' => [
        'download' => 'Bilgiler başarılı bir şekilde indirildi',
        'complete' => 'İşlem tamamlandı',
        'doTheApprovalsFirst' => 'Transfer taleplerinizi onaylamalamadan işlem yapamazsınız!',
    ],

    # alert
    'alert' => [
        'data' => [
            'insert' => [
                'success' => 'Kayıt ekleme işlemi başarılı.',
                'error' => 'Kayıt eklenemedi !. Bir hata oluştu.',
            ],
            'update' => [
                'success' => 'Kayıt güncelleme işlemi başarılı.',
                'error' => 'Kayıt güncellenemedi !. Bir hata oluştu.',
            ],
            'delete' => [
                'success' => 'Kayıt silme işlemi başarılı.',
                'error' => 'Kayıt silinemedi !. Bir hata oluştu.',
            ],
        ],
    ],

    # modal
    'modal' => [
        'header' => [
            'insert' => 'Yeni kayıt ekle',
            'update' => 'Kayıt güncelle',
            'delete' => 'Kayıt sil',
            'cancel' => 'İptal et',
            'view' => 'Görüntüle / Düzenle',
            'edit' => 'Kayıt Düzenle'
        ],
        'deleteinfo' => '<strong>Dikkat !<br><b class="text-danger">Bu işlem veri sisteminde büyük arızalara sebep olabilir !</b><br>Bu işlem geri alınamaz. Lütfen silmek istediğinizi onaylayın !</strong>',
        'button' => [
            'confirm' => 'Onayla',
            'save' => 'Kaydet',
            'send' => 'Gönder',
            'cancel' => 'Vazgeç', 
        ], 
    ],

    'button' => [
        'insert'        => '<i class="fa fa-plus"></i> Yeni kayıt oluştur',
        'update'        => '<i class="fas fa-pencil-alt fa-lg"></i> Düzenle',
        'delete'        => '<i class="far fa-trash-alt fa-lg"></i> Sil',
        'takeRepport'   => 'Rapor Al',
        'acceptbuy'     => 'Satın Al',
        'deny'          => 'Reddet',
    ],

    # excel
    'excel'=> [
        'export'=>'<i class="far fa-file-excel"></i> Excel olarak indir',
        'FileDetails'=>[
            'warehouse'=>'Genel tanımlamalar depo bilgileri',
            'unit'=>'Genel tanımlamalar birim bilgileri',
            'item'=>'Genel tanımlamalar urun bilgileri',
        ],
    ],


];