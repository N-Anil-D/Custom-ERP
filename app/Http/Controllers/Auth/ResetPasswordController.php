<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, ResetPasswordRequest};
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Support\{Str, Carbon};
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{

    # doğrulama kodunun dk cinsinden ömür süresi
    public $tokenMin;

    public function __construct()
    {
        # doğrulama kodunun dk cinsinden ömür süresi
        $this->tokenMin = env('RESET_PASSWORD_TOKEN_MINUTE');
    }

    public function checkTelNo(Request $request)
    {

        # use varmı yok mu kontrol ?
        $user = User::where('tel_no', $request->tel_no)->first();
        if ($user) {

            # daha önceden üretilen vakti dolmamış ve kullanılmamış doğrulama kodu var ise o kodu devre dışı bırakır
            $this->typeControl($user->tel_no);

            # kod üret 6 haneli
            $rand = rand(0, 999999);
            $validateCode = Str::padLeft($rand, 6, 0);

            # isteği ve oluşturulan kodu tabloya kaydet
            ResetPasswordRequest::create([
                'tel_no' => $user->tel_no,
                'validate_code' => $validateCode,
                'created_at' => now(),
            ]);

            # kullanıcının telegram hesabına kodu gönder
            $this->sendTelegramMessage($user, $validateCode);

            # gönderilen kodu girmesi için form a yönlendir
            session()->flash('success', 'Parola sıfırlama kodunuz telegram hesabınıza gönderildi.');
            return redirect()->route('password.validate.tel', $user->tel_no);
        }

        # user eşleşmedi geri bildirimi
        session()->flash('error', 'Girilmiş olan kullanıcı verileri sistemdekiler ile eşleşmemektedir.');
        return redirect()->back()->withInput();
    }

    public function typeControl($telNo)
    {
        # daha önceden üretilen vakti dolmamış ve kullanılmamış doğrulama kodu var ise o kodu devre dışı bırakır
        $resReqType = ResetPasswordRequest::where('tel_no', $telNo)
            ->where('created_at', '>', Carbon::now()->subMinute($this->tokenMin))
            ->get();

        foreach ($resReqType as $type) {
            $type->update([
                'type' => 2
            ]);
        }
    }

    public function validateTelNo($telNo)
    {
        # gönderilen kodu girmesi için form a yönlendir
        return view('auth.pass-reset.reset-validate-code', compact('telNo'));
    }

    public function validateCode(Request $request)
    {
        # doğrulama kodunu kontrol eder
        $resetRequest = ResetPasswordRequest::where('tel_no', $request->tel_no)
            ->where('validate_code', $request->validate_code)
            ->where('type', 0)
            ->where('created_at', '>', Carbon::now()->subMinute($this->tokenMin))
            ->first();

        # doğrulama kodu var ise
        if ($resetRequest) {
            # parola belirleme formu na kullanıcıyı yönlendirir
            session()->flash('success', 'Doğrulama kodu kabul edildi. Lütfen parolanızı belirleyiniz.');
            return redirect()->route('password.set.show', [$request->tel_no, $request->validate_code]);
        }

        # doğrulama kodu hatalı girilmiştir.
        # doğrulama kodunun süresi dolmuştur.
        # telefon numarası elle müdahele sonucu değiştirilmiştir.
        # doğrulama kodu kullanılmıştır. - type 0 değilse
        session()->flash('error', 'Doğrulama kodu geçersiz.');
        return redirect()->back();
    }

    public function showSetPassword($telNo, $validateCode)
    {
        # parola belirleme formu na kullanıcıyı yönlendirir
        return view('auth.pass-reset.reset-password', compact('telNo', 'validateCode'));
    }

    public function setPassword(Request $request)
    {

        # parola değiştirme isteği için son kontroller 
        $resetRequest = ResetPasswordRequest::where('tel_no', $request->tel_no)
            ->where('validate_code', $request->validate_code)
            ->where('type', 0)
            ->where('created_at', '>', Carbon::now()->subMinute($this->tokenMin))
            ->first();

        # bilgiler doğru ise
        if ($resetRequest) {
            # parola doğrulama
            $this->validate($request, [
                'password' => 'required|confirmed|min:8',
            ]);

            $user = User::where('tel_no', $request->tel_no)->first();

            # parola hashle ve kaydetme
            $this->setUserPassword($user, $request->password);
            $user->save();

            # doğrulama kodunun tekrar kullanılmaması için 
            $resetRequest->update([
                'type' => 1
            ]);

            # parolanın değiştirildiği bilgisi kullanıcıya bildirilir ve login ekranına yönlendirilir.
            session()->flash('success', 'Parolanız başarı ile sıfırlandı. Belirlediğiniz yeni parolanız ile giriş yapabilirsiniz.');
            return redirect('/login');

        }

        # doğrulama kodu formda değiştirilmiştir.
        # doğrulama kodunun süresi dolmuştur.
        # telefon numarası formda değiştirilmiştir.
        session()->flash('error', 'Doğrulama kodu geçersiz.');
        return redirect()->back();
        
    }

    protected function setUserPassword($user, $password)
    {
        # parola hashle
        $user->password = Hash::make($password);
    }

    public function sendTelegramMessage($user, $validateCode)
    {
        # kullanıcının telegram hesabına kodu gönder
        TelegramMessage::create()
            ->to($user->telegram_id)
            ->line('*Sayın ' . $user->name . '*')
            ->line('')
            ->line(env('APP_NAME') . ' için parola sıfırlama doğrulama kodunuz :')
            ->line('')
            ->line($validateCode)
            ->line('')
            ->line('Bu kodun geçerlilik süresi ' . $this->tokenMin . ' dk. ile sınırlıdır. Sıfırlama isteğinde bulunmadıysanız bu mesajı dikkate almayınız. Tek kullanımlık kodunuzu kimse ile paylaşmayınız.')
            ->send();
    }
}
