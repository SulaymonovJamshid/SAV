<?php
namespace App\Models;
use App\Core\DB;

class Sms {
    public static function create(string $phone): string {
        DB::run('UPDATE sms_verifications SET is_used=1 WHERE phone=? AND is_used=0',[$phone]);
        $code    = make_otp();
        $expires = date('Y-m-d H:i:s', time()+600);
        DB::run('INSERT INTO sms_verifications (phone,code,expires_at) VALUES (?,?,?)',[$phone,$code,$expires]);
        return $code;
    }
    public static function verify(string $phone, string $code): bool {
        $row = DB::one(
            'SELECT id FROM sms_verifications WHERE phone=? AND code=? AND is_used=0 AND expires_at>NOW() AND attempts<5 LIMIT 1',
            [$phone,$code]
        );
        if($row){ DB::run('UPDATE sms_verifications SET is_used=1 WHERE id=?',[$row['id']]); return true; }
        DB::run('UPDATE sms_verifications SET attempts=attempts+1 WHERE phone=? AND is_used=0 ORDER BY id DESC LIMIT 1',[$phone]);
        return false;
    }
    public static function send(string $phone, string $code): void {
        $sid   = env('TWILIO_SID','');
        $token = env('TWILIO_TOKEN','');
        $from  = env('TWILIO_FROM','');
        if(!$sid||!$token){ error_log("[OTP] $phone => $code"); return; }
        $body = "SmartAvtoServis: Tasdiqlash kodi: $code (10 daqiqa)";
        $ch = curl_init("https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json");
        curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_RETURNTRANSFER=>1,CURLOPT_USERPWD=>"$sid:$token",
            CURLOPT_POSTFIELDS=>http_build_query(['From'=>$from,'To'=>$phone,'Body'=>$body])]);
        curl_exec($ch); curl_close($ch);
    }
}
