<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'name',
        'email',
        'tel_no',
        'telegram_id',
        'password',
        'theme',
        'sidebar',
        'production_report',
        'buy_assent',
        'can_buy',
        'confirm_buy',
        'can_exit',
        'confirm_exit',
        'can_count_all',
        'can_confirm_count',
        'can_request_report',
        'quality_control',
        'confirm_quality_control',
        'work_order_level',
        'active'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    
    public function usrToAut()
    {
        return $this->hasMany(UserAuth::class,'userId','id');
    }
    
    //model-dışAnahtar-içAnahtar
    public function usrToAlt()
    {
        return $this->hasMany(LucaStockAlert::class,'userId','id');
    }

    public function warehouses()
    {
        return $this->hasMany(\App\Models\Erp\Warehouse\ErpUsersWarehouses::class, 'user_id', 'id');
    }

    public function myErpItemAlerts()
    {
        return $this->hasMany(\App\Models\Erp\Item\ErpItemsPersonalActions::class, 'user_id', 'id')->where('warned', 0);
    }

    // onay bekliyor -- doğrudan stok transferi // gönderici talep sahibi
    public function pendingApprovals()
    {
        return $this->hasMany(\App\Models\View\Notifications::class, 'user_id', 'id')
            ->where('erp_approvals_status', 0)
            ->where('erp_approvals_type', 0);
    }

    // onay bekliyor -- doğrudan stok transferi // alıcı talep sahibi
    // public function pendingDemands()
    // {
    //     return $this->hasMany(\App\Models\View\Notifications::class)
    //         ->where('erp_approvals_status', 0)
    //         ->where('erp_approvals_type', 5)
    //         ->whereIn('erp_approvals_dwindling_warehouse_id', $this->warehouses->pluck('warehouse_id'));
    // }

    // onay bekliyor -- fatura alımları dışında - View kullanmadan. ? burda açıklama doğruluğu teyit edilecek
    public function pendingApprovalsFunction()
    {
        $warehouseIdArray = [];
        $warehouses = $this->warehouses;
        foreach($warehouses as $warehouse){
            array_push($warehouseIdArray, $warehouse->warehouse_id);
        }
        return Erp\ErpApproval::whereIn('increased_warehouse_id',$warehouseIdArray)->where('type',0)->where('status',0)->get();
    }

    public function waitForApproval()
    {
        return $this->hasMany(\App\Models\Erp\ErpApproval::class, 'sender_user', 'id')
            ->where('status', 0)
            ->whereNot('type', 8);
    }

    public function getUserName($telegramId)
    {
        return $this->where('telegram_id', $telegramId)->first();
    }
    
}
