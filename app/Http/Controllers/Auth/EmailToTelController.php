<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, EmailToTelMatch};
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Carbon;

class EmailToTelController extends Controller
{

    # doğrulama linkinin dk cinsinden ömür süresi
    public $tokenMin;
    
    public function __construct()
    {
        # doğrulama linkinin dk cinsinden ömür süresi
        $this->tokenMin = env('EPOST_TELNO_MATCH_TOKEN_MINUTE');
    }

    public function emailValidate()
    {
        # e posta doğrulama formunu aç
        return view('auth.email-to-tel.email-validate');

    }

    public function emailValidateControl(Request $reuqest)
    {
        # kullanıcı tarafından girilen e posta doğrumu kontrol et
        $user = User::where('email', $reuqest->email)
            ->where('tel_no', NULL)
            ->first();
        
        if($user){
            # eşleşen kullanıcı var ise

            # telegram kullanıyor ise
            if($user->telegram_id) {

                # daha önceden üretilen vakti dolmamış ve kullanılmamış doğrulama linki var ise onu devre dışı bırakır
                $this->typeControl($user->email);
    
                # doğrulama kodu üret
                $validateCode = md5($user->name.now());
    
                # verileri tabloya kaydet
                EmailToTelMatch::create([
                    'email' => $user->email,
                    'validate_code' => $validateCode,
                    'created_at' => now()
                ]);
                
                # mail verileri
                $data = [
                    'name'          => $user->name,
                    'link'          => route('emailToTel.email.link.validate', $validateCode),
                    'buttonName'    => 'Tel. No. Eşleştir'
                ];
                $subData = [];
                $subj = "CustomERP | Telefon numaranızı eşleştiriniz.";
                $view = "mail.e-mail-validate";
                
                # mail gönder
                Mail::to($user->email)->send(new SendMail($data, $subData, $subj, $view));
    
                session()->flash('success' , 'Telefon numaranızı doğrulamanız için e-posta adresinize size özel bir link gönderildi. Lütfen e-posta kutunuzu kontrol ediniz. Gönderilen doğrulama linki '.$this->tokenMin.' dk. boyunca erişime açık olacaktır.');
                return redirect('/login');
            }

            # telegram kullanmıyor
            session()->flash('error', 'Hesabınızla alakalı eksik bilgiler tespit edildi. Lütfen CustomERP IT departmanı ile iletişime geçiniz.');
            return redirect()->back();

        }
        
        # kullanıcı kaydı bulunamadı
        session()->flash('error' , 'Girmiş olduğunuz e-posta adresi ile telefon numarası eşleşmemiş herhangi bir kayıt bulunamadı.');
        return redirect()->back();

    }

    public function emailLinkValidate($token)
    {
        # token geçerlilik kontrolü
        $token = EmailToTelMatch::where('validate_code', $token)
            ->where('type', 0)
            ->where('created_at', '>', Carbon::now()->subMinute($this->tokenMin))
            ->first();

        # token geçerli ve bilgiler doğru ise
        if($token) {
            return view('auth.email-to-tel.set-email-tel', compact('token'));
        }

        # token geçersiz yada bilgiler hatalı ise
        session()->flash('error', 'Geçersiz doğrulama linki');
        return redirect()->route('emailToTel.email.validate');      

    }

    public function setTelNo(Request $request)
    {
        # kullanıcının telefon numarası bilgisini kaydetmeden önce kontroller
        $matchRequest = EmailToTelMatch::where('email', $request->email)
            ->where('validate_code', $request->validate_code)
            ->where('type', 0)
            ->where('created_at', '>', Carbon::now()->subMinute($this->tokenMin))
            ->first();
        
        # bilgiler doğru ise
        if($matchRequest) {
            
            # telno doğrula
            $this->validate($request, [
                'tel_no' => 'required|min:14'
            ]);

            $user = User::where('email', $request->email)->first();

            # telefon numarasını güncelle
            $user->update([
                'tel_no' => $request->tel_no
            ]);

            # doğrulama linkinin tekrar kullanılmaması için
            $matchRequest->update([
                'type' => 1
            ]);

            # telefon numarasının güncellendiğini kullanıcıya bildir
            session()->flash('success', 'Telefon numaranız başarı ile kaydedilmiştir. Şimdi belirlediğiniz telefon numarası ile giriş yapabilirsiniz');
            return redirect('/login');

        }

        # doğrulama linki formda değiştirilmiştir.
        # doğrulama kodunun süresi dolmuştur.
        # e-posta adresi formda değiştirilmiştir.
        session()->flash('error', 'Geçersiz doğrulama linki');
        return redirect()->back();
    }

    public function typeControl($email)
    {
        # daha önceden üretilen vakti dolmamış ve kullanılmamış doğrulama linki var ise onu devre dışı bırakır
        $resReqType = EmailToTelMatch::where('email', $email)
            ->where('created_at', '>', Carbon::now()->subMinute($this->tokenMin))
            ->get();

        foreach ($resReqType as $type) {
            $type->update([
                'type' => 2
            ]);
        }
    }
}
