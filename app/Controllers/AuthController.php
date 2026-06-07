<?php
namespace App\Controllers;
use App\Models\{User, Sms, Service};

class AuthController {
    public function home(): void {
        if(logged_in()) redirect(APP_URL.'/services');
        render('auth/home', ['title' => t('app_name')]);
    }

    // ── Login ─────────────────────────────────────────────────
    public function showLogin(): void {
        if(logged_in()) redirect(APP_URL.'/services');
        render('auth/login', ['title'=>t('login'),'errors'=>[],'old'=>[]]);
    }
    public function login(): void {
        csrf_verify();
        $id  = clean($_POST['identifier']??'');
        $pw  = $_POST['password']??'';
        $err = [];
        if(!$id) $err['identifier'] = t('err_required',['f'=>t('email')]);
        if(!$pw) $err['password']   = t('err_required',['f'=>t('password')]);
        if(!$err){
            $u = User::byLogin($id);
            if(!$u||!password_verify($pw,$u['password_hash'])) $err['general']=t('err_login');
            elseif(!$u['is_active']) $err['general']='Hisobingiz bloklangan.';
            elseif(!$u['is_verified']){
                $_SESSION['_verify_phone'] = $u['phone'];
                $_SESSION['_verify_uid']   = $u['id'];
                $code = Sms::create($u['phone']); Sms::send($u['phone'],$code);
                redirect(APP_URL.'/verify-phone');
            }
        }
        if($err){ render('auth/login',['title'=>t('login'),'errors'=>$err,'old'=>['identifier'=>$id]]); return; }
        $this->startSession($u);
        flash('success',t('ok_login'));
        $this->byRole($u['role']);
    }

    // ── Register ──────────────────────────────────────────────
    public function showRegister(): void {
        if(logged_in()) redirect(APP_URL.'/services');
        $role = in_array($_GET['role']??'',['user','service']) ? $_GET['role'] : 'user';
        render('auth/register',['title'=>t('register'),'role'=>$role,'errors'=>[],'old'=>[],'viloyatlar'=>viloyatlar(),'specs'=>specializations()]);
    }
    public function register(): void {
        csrf_verify();
        $role = in_array($_POST['role']??'',['user','service']) ? $_POST['role'] : 'user';
        $old  = $_POST;
        $err  = [];
        $fn   = clean($_POST['first_name']??'');
        $ln   = clean($_POST['last_name']??'');
        $em   = filter_var(trim($_POST['email']??''),FILTER_SANITIZE_EMAIL);
        $ph   = clean($_POST['phone']??'');
        $pw   = $_POST['password']??'';
        $pw2  = $_POST['password2']??'';
        if(!$fn) $err['first_name']=t('err_required',['f'=>t('first_name')]);
        if(!$ln) $err['last_name'] =t('err_required',['f'=>t('last_name')]);
        if(!$em&&!$ph) $err['contact']='Email yoki telefon kiritilishi shart';
        if($em&&!filter_var($em,FILTER_VALIDATE_EMAIL)) $err['email']="Email noto'g'ri";
        if(strlen($pw)<8) $err['password']='Parol kamida 8 belgi';
        if($pw!==$pw2)    $err['password2']='Parollar mos emas';
        if($em&&User::byEmail($em)) $err['email']=t('err_exists',['f'=>'Email']);
        if($ph&&User::byPhone($ph)) $err['phone']=t('err_exists',['f'=>t('phone')]);
        if($role==='service'){
            if(empty($_POST['svc_name'])) $err['svc_name']=t('err_required',['f'=>t('svc_name')]);
            if(empty($_POST['viloyat']))  $err['viloyat'] =t('err_required',['f'=>t('viloyat')]);
            if(empty($_POST['tuman']))    $err['tuman']   =t('err_required',['f'=>t('tuman')]);
            if(!$ph) $err['phone']='Servis uchun telefon majburiy';
        }
        if($err){
            render('auth/register',['title'=>t('register'),'role'=>$role,'errors'=>$err,'old'=>$old,'viloyatlar'=>viloyatlar(),'specs'=>specializations()]);
            return;
        }
        $needVerify = $ph && !$em;
        $uid = User::create(['email'=>$em?:null,'phone'=>$ph?:null,'password'=>$pw,'first_name'=>$fn,'last_name'=>$ln,'role'=>$role,'is_verified'=>$needVerify?0:1,'lang'=>lang()]);
        if($role==='service'){
            Service::create($uid,[
                'name'=>clean($_POST['svc_name']),'phone'=>$ph,
                'viloyat'=>clean($_POST['viloyat']??''),'tuman'=>clean($_POST['tuman']??''),
                'shahar'=>clean($_POST['shahar']??''),'address'=>clean($_POST['address']??''),
                'specializations'=>array_intersect($_POST['specs']??[],specializations()),
            ]);
        }
        if($needVerify){
            $_SESSION['_verify_phone']=$ph; $_SESSION['_verify_uid']=$uid;
            $code=Sms::create($ph); Sms::send($ph,$code);
            redirect(APP_URL.'/verify-phone');
        }
        $u = User::find($uid);
        $this->startSession($u);
        flash('success',t('ok_register'));
        $this->byRole($role);
    }

    // ── OTP ───────────────────────────────────────────────────
    public function showVerify(): void {
        $ph = $_SESSION['_verify_phone']??null;
        if(!$ph) redirect(APP_URL.'/login');
        render('auth/verify',['title'=>t('verify_phone'),'phone'=>$ph,'errors'=>[]]);
    }
    public function verify(): void {
        csrf_verify();
        $ph  = $_SESSION['_verify_phone']??null;
        $uid = $_SESSION['_verify_uid']??null;
        if(!$ph||!$uid) redirect(APP_URL.'/login');
        $code = clean($_POST['code']??'');
        if(!$code||!Sms::verify($ph,$code)){
            render('auth/verify',['title'=>t('verify_phone'),'phone'=>$ph,'errors'=>['code'=>t('invalid_otp')]]);
            return;
        }
        User::verify((int)$uid);
        $u = User::find((int)$uid);
        $this->startSession($u);
        unset($_SESSION['_verify_phone'],$_SESSION['_verify_uid']);
        flash('success',t('ok_register'));
        $this->byRole($u['role']);
    }
    public function resendOtp(): void {
        $ph = $_SESSION['_verify_phone']??null;
        if(!$ph) json_out(['error'=>'no phone'],400);
        $code=Sms::create($ph); Sms::send($ph,$code);
        json_out(['ok'=>true]);
    }
    public function logout(): void { session_destroy(); redirect(APP_URL.'/login'); }

    // ── Helpers ───────────────────────────────────────────────
    private function startSession(array $u): void {
        session_regenerate_id(true);
        $_SESSION['auth']  = ['id'=>$u['id'],'first_name'=>$u['first_name'],'last_name'=>$u['last_name'],'email'=>$u['email'],'phone'=>$u['phone'],'role'=>$u['role'],'avatar'=>$u['avatar'],'lang'=>$u['lang'],'theme'=>$u['theme']];
        $_SESSION['theme'] = $u['theme'];
        load_lang($u['lang']);
    }
    private function byRole(string $role): never {
        match($role){'admin'=>redirect(APP_URL.'/admin'),'service'=>redirect(APP_URL.'/dashboard'),default=>redirect(APP_URL.'/services')};
    }
}
