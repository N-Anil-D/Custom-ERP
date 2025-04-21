<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\{User, Sidebar, UserAuth};
use Illuminate\Support\Facades\{Session, Validator, Hash, Mail};
use Illuminate\Support\{Str, Arr};
use App\Mail\SendMail;
use App\Models\Erp\Warehouse\{ErpUsersWarehouses, ErpWarehouse};
use NotificationChannels\Telegram\{TelegramMessage, TelegramUpdates};
use Throwable;

class UserManage extends Component
{
    // public $users = [];
    public $action;
    public $userId;
    public $user = [];
    public $sideBar = [];
    public $search;
    public $warehouses = [];
    public $activeUserList = [];
    public $sendMessageList = [];
    public $botMessage = [];
    
    public function render()
    {
        $activeUsers = User::where('active',1);
        $inactiveUsers = User::where('active',0);
        return view('livewire.settings.user-manage',[
            'activeUsers'=>$activeUsers
            ->where(function($searchQuery){
                $searchQuery->where('name','like','%'.$this->search.'%')
                ->orWhere('email','like','%'.$this->search.'%')
                ->orWhere('tel_no','like','%'.$this->search.'%');
            })
            ->orderBy('id')->get(),

            'inActiveUsers' =>$inactiveUsers
            ->where(function($searchQuery){
                $searchQuery->where('name','like','%'.$this->search.'%')
                ->orWhere('email','like','%'.$this->search.'%')
                ->orWhere('tel_no','like','%'.$this->search.'%');
            })
            ->orderBy('id')->get(),
        ]);
    }    
    
    //başlangıçta gel
    public function boot()
    {
        $this->calc();
    }

    //güncellenir ise
    public function updating()
    {
        $this->calc();
    }

    //yeni kayıtlarda
    public function inserting()
    {
        $this->calc();
    }

    //neler güncellenecek
    public function calc()
    {
        // $this->users = User::where('name','like','%'.$this->search.'%')
        //     ->orWhere('email','like','%'.$this->search.'%')
        //     ->orWhere('tel_no','like','%'.$this->search.'%')
        //     ->orderBy('id')->get();
        $this->sideBar = Sidebar::where('hid',0)->get();
        $this->warehouses = ErpWarehouse::get();
    }
    
    public function process($userId,$action)
    {     
       
        $this->userId = $userId;
        $this->action = $action;   
        
        if($action == 'delete'){
            
            $this->user = User::find($this->userId);
            $this->dispatchBrowserEvent('userDeleteModalShow');
            
        }elseif($action == 'insert'){
            
            $this->clearItem();
            $this->dispatchBrowserEvent('userInsertOrUpdateModalShow');             
        }elseif($action == 'update'){

            $data = User::find($this->userId);
            $sidebarAuthority = [];
            $warehouses = [];

            foreach($data->usrToAut as $aut){
                array_push($sidebarAuthority,$aut->urlId);                    
            }

            foreach($data->warehouses as $warehouse)
            {
                if($warehouse->warehouse != null){
                    array_push($warehouses, $warehouse->warehouse->id);
                }
            }
            
            $this->user = [
                'name'  => $data->name,
                // 'tel_no' => $data->tel_no,
                'email' => $data->email,
                'active' => $data->active,
                'telegram_id' => $data->telegram_id,
                'authority' => $sidebarAuthority,
                'warehouses' => $warehouses,
                'production_report' => $data->production_report,
                'can_buy' => $data->can_buy,
                'buy_assent' => $data->buy_assent,
                'confirm_buy' => $data->confirm_buy,
                'can_exit' => $data->can_exit,
                'confirm_exit' => $data->confirm_exit,
                'can_count_all' => $data->can_count_all,
                'can_confirm_count' => $data->can_confirm_count,
                'can_request_report' => $data->can_request_report,
                'work_order_level' => $data->work_order_level,
                'quality_control' => $data->quality_control,
                'confirm_quality_control' => $data->confirm_quality_control,
            ];
            $this->dispatchBrowserEvent('userInsertOrUpdateModalShow');           
        }elseif($action == 'sendMessage'){
            $this->sendMessageList=[];
            $this->botMessage=['message'=>''];
            foreach(User::where('active',1)->get() as $user)
            {
                $this->activeUserList[$user->id] = $user->name;
            }
            $this->dispatchBrowserEvent('sendMessageByBotModalShow');                
        }
    } 
    
    public function delete()
    {
        $data = User::find($this->userId);
        $data->update([
            'active' => (!$data->active),
        ]);
                
        $this->dispatchBrowserEvent('userDeleteModalHide');
        $this->updating();
        Session::flash('success', trans('site.general.complete'));
    }
    
    public function insertOrUpdate()
    {

        if($this->action == 'update'){
            
             Validator::make($this->user, [
                'name' => 'required',
            ])->validate();
            
        }else{
            
            Validator::make($this->user, [
                'name' => 'required',
                'tel_no' => 'required|unique:users,tel_no',
                'telegram_id' => 'required|unique:users,telegram_id',
                'email' => 'unique:users,email',
            ])->validate();
            
        }
        
        if($this->userId == 0){            
            $password = Str::upper(Str::random(8));
            $bcryptPass = Hash::make($password);
            
            $newUser = User::create([
                'name' => $this->user['name'],
                'tel_no' => $this->user['tel_no'],
                'password' => $bcryptPass,
                'telegram_id' => $this->user['telegram_id'],
                'email' => $this->user['email'],
                'production_report' => $this->user['production_report'],
                'can_buy' => $this->user['can_buy'],
                'buy_assent' => $this->user['buy_assent'],
                'confirm_buy' => $this->user['confirm_buy'],
                'can_exit' => $this->user['can_exit'],
                'confirm_exit' => $this->user['confirm_exit'],
                'can_count_all' => $this->user['can_count_all'],
                'can_confirm_count' => $this->user['can_confirm_count'],
                'can_request_report' => $this->user['can_request_report'],
                'work_order_level' => $this->user['work_order_level'],
                'quality_control' => $this->user['quality_control'],
                'confirm_quality_control' => $this->user['confirm_quality_control'],
            ]);
            
            if($this->user['authority']){
                foreach($this->user['authority'] as $key){
                    UserAuth::create([
                        'userId' => $newUser->id,
                        'urlId' => $key
                    ]);
                }
            }
            
            if($this->user['warehouses']){
                foreach($this->user['warehouses'] as $key){
                    ErpUsersWarehouses::create([
                        'user_id' => $newUser->id,
                        'warehouse_id' => $key
                    ]);
                }
            }

            TelegramMessage::create()
                ->to($newUser->telegram_id)
                ->line('*Sayın '.$newUser->name.'*')
                ->line('')
                ->line(env('APP_NAME').' için giriş bilgileriniz :')
                ->line('')
                ->line('Kullanıcı adınız (Tel no.): '. $newUser->tel_no)
                ->line('Parolanız : '. $password)
                ->line('')
                ->line('Parolanız rastgele belirlenmiştir, kimse tarafından bilinmemektedir ve sadece bir kereye mahsus size gönderilmiştir. Parolanızı giriş yaptıktan sonra '. route('profile.show').' adresinden değiştirebilirsiniz. Güvenliğiniz için kimse ile paylaşmamanızı öneririz.')
                ->line('Selamlar ve iyi çalışmalar.')
                ->send();
            
            # yeni kullanıcının bilgileri kullanıcıya daha önce mail ile gönderiliyordu 
            // $data = [
            //     'name' => $newUser->name,
            //     'email' => $newUser->email,
            // ];
            // $subData = [
            //     'password' => $password
            // ];
            // $subj = "CustomERP | Yeni Kullanıcı Hesabınız";
            // $view = "mail.newUserV2";
            
            // Mail::to($newUser->email)->send(new SendMail($data, $subData, $subj, $view));
        }else{

            User::find($this->userId)->update($this->user);
            UserAuth::where('userId', $this->userId)->delete();
            foreach($this->user['authority'] as $key){
                UserAuth::create([
                    'userId'    => $this->userId,
                    'urlId'     => $key
                ]);
            }

            ErpUsersWarehouses::where('user_id', $this->userId)->delete();
            foreach($this->user['warehouses'] as $key){
                ErpUsersWarehouses::create([
                    'user_id' => $this->userId,
                    'warehouse_id' => $key
                ]);
            }
            
        }
        
        $this->dispatchBrowserEvent('userInsertOrUpdateModalHide');
        $this->updating();
        Session::flash('success', trans('site.alert.data.'.$this->action.'.success'));
    }
    
    public function clearItem()
    {
        $this->user = [
            'name' => '',
            'tel_no' => '',
            'telegram_id' => '',
            'email' => '',
            'active' => true,
            'authority' => [],
            'warehouses' => [],
            'production_report' => false,
            'can_buy' => false,
            'buy_assent' => false,
            'confirm_buy' => false,
            'can_exit' => false,
            'confirm_exit' => false,
            'can_count_all' => false,
            'can_confirm_count' => false,
            'can_request_report' => false,
            'work_order_level' => 0,
            'quality_control' => false,
            'confirm_quality_control' => false,
        ];
    }
    
    public function telegramTest($rowId)
    {
        $user = User::find($rowId);
        if($user->telegram_id){
            TelegramMessage::create()
                ->to($user->telegram_id)
                ->line('*Sayın '.$user->name.'*')
                ->line('')
                ->line('Bu mesaj size ulaştıysa lütfen CustomERP IT departmanını ile iletişime geçin.')
                ->line('İyi çalışmalar.')
                ->send();
            Session::flash('success', $user->name.' kullanıcısına bildirim mesajı gönderildi.');
        }else{
            Session::flash('error', 'Kullanıcıda telegram ID yok');
        }
    }

    public function showTelegramMsg()
    {
        $updates = TelegramUpdates::create()->get();
        dd(Arr::dot($updates));
        // dd($updates['result'][0]['message']['from']);
    }

    public function authorityProcess($rowId, $column)
    {
        $user = User::find($rowId);
        $user->update([
            $column => (!$user->$column)
        ]);
        Session::flash('success', '#'.$user->id.'# '.trans('site.alert.data.update.success'));
    }

    public function sendMessage()
    {
        
        if(count($this->sendMessageList)==0){
            Session::flash('error', 'Hiç bir kullanıcı seçilmediğinden, mesaj gönderilmedi.');
            $this->dispatchBrowserEvent('sendMessageByBotModalHide');                
        }else{
            $sendMessage = User::whereIn('id',$this->sendMessageList)->get();
            $botMessage = preg_replace('/[^A-Za-z0-9\-ÜİÇÖĞIığüşöç.,\/]/', ' ', $this->botMessage['message']);
            foreach ($sendMessage as $user) {
                if(!is_null($user->telegram_id)){
                    try {
                        TelegramMessage::create()
                        ->to($user->telegram_id)
                        ->line('*Sayın '.$user->name.'*')
                        ->line('')
                        ->line($botMessage)
                        ->send();
                    } catch (Throwable $e) {
                        $this->dispatchBrowserEvent('sendMessageByBotModalHide');                
                        report($e);
                 
                        return false;
                    }
                }
            }
            $this->dispatchBrowserEvent('sendMessageByBotModalHide');                
        }

        Session::flash('success', 'Mesajınız seçili kullanıcılara gönderildi');
    }
    

}
