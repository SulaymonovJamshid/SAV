<?php
namespace App\Controllers;
use App\Models\{Service,Review,Favorite,User,Sms};

// ════════════════════════════════════════════════════════════
class ServiceController {
    public function index(): void {
        require_login();
        $f = ['viloyat'=>clean($_GET['viloyat']??''),'tuman'=>clean($_GET['tuman']??''),'shahar'=>clean($_GET['shahar']??''),
              'spec'=>clean($_GET['spec']??''),'search'=>clean($_GET['search']??''),
              'sort'=>clean($_GET['sort']??'newest'),'rating_min'=>clean($_GET['rating_min']??'')];
        $page   = max(1,(int)($_GET['page']??1));
        $total  = Service::count($f);
        $pag    = paginate($total,12,$page);
        $svcs   = Service::list($f,$pag['per'],$pag['offset']);
        
        foreach($svcs as &$s) $s['images']=Service::images((int)$s['id']);
        $favIds = logged_in() ? Favorite::ids((int)user()['id']) : [];
        render('user/services',['title'=>t('services'),'svcs'=>$svcs,'pag'=>$pag,'f'=>$f,'favIds'=>$favIds,'viloyatlar'=>viloyatlar(),'specs'=>specializations()]);
    }
    public function detail(string $id): void {
        require_login();
        $svc = is_numeric($id) ? Service::byId((int)$id) : Service::bySlug($id);
        if(!$svc){http_response_code(404);render('partials/404',['title'=>'404']);return;}
        $images   = Service::images((int)$svc['id']);
        $reviews  = Review::forService((int)$svc['id']);
        $isFav    = logged_in() && Favorite::isFav((int)user()['id'],(int)$svc['id']);
        $myReview = logged_in() ? Review::byUser((int)$svc['id'],(int)user()['id']) : null;
        render('user/detail',['title'=>e($svc['name']),'svc'=>$svc,'images'=>$images,'reviews'=>$reviews,'isFav'=>$isFav,'myReview'=>$myReview]);
    }
}

// ════════════════════════════════════════════════════════════
class DashboardController {
    private function svc(): array {
        require_role('service');
        $s = Service::byUser((int)user()['id']);
        if(!$s) redirect(APP_URL.'/dashboard/edit');
        return $s;
    }
    public function index(): void {
        require_role('service');
        $svc = Service::byUser((int)user()['id']);
        if(!$svc){ render('service/empty',['title'=>t('dashboard')]); return; }
        $images  = Service::images((int)$svc['id']);
        $reviews = Review::forService((int)$svc['id']);
        render('service/dashboard',['title'=>t('dashboard'),'svc'=>$svc,'images'=>$images,'reviews'=>$reviews]);
    }
    public function edit(): void {
        require_role('service');
        $svc    = Service::byUser((int)user()['id']);
        $images = $svc ? Service::images((int)$svc['id']) : [];
        render('service/edit',['title'=>t('edit'),'svc'=>$svc,'images'=>$images,'errors'=>[],'old'=>$svc??[],'viloyatlar'=>viloyatlar(),'specs'=>specializations()]);
    }
    public function save(): void {
        require_role('service'); csrf_verify();
        $uid = (int)user()['id'];
        $svc = Service::byUser($uid);
        $d = [
            'name'=>clean($_POST['name']??''),'description'=>clean($_POST['description']??''),
            'specializations'=>array_intersect($_POST['specs']??[],specializations()),
            'experience_years'=>(int)($_POST['experience_years']??0),
            'viloyat'=>clean($_POST['viloyat']??''),'tuman'=>clean($_POST['tuman']??''),
            'shahar'=>clean($_POST['shahar']??''),'address'=>clean($_POST['address']??''),
            'latitude'=>clean($_POST['latitude']??''),'longitude'=>clean($_POST['longitude']??''),
            'work_start'=>clean($_POST['work_start']??'08:00'),'work_end'=>clean($_POST['work_end']??'18:00'),
            'work_days'=>$_POST['work_days']??[],'is_24h'=>!empty($_POST['is_24h']),
            'phone'=>clean($_POST['phone']??''),'website'=>clean($_POST['website']??''),
            'telegram'=>clean($_POST['telegram']??''),
            'price_from'=>(int)($_POST['price_from']??0),'price_to'=>(int)($_POST['price_to']??0),
            'price_note'=>clean($_POST['price_note']??''),
        ];
        $err=[];
        if(!$d['name'])    $err['name']   =t('err_required',['f'=>t('svc_name')]);
        if(!$d['viloyat']) $err['viloyat']=t('err_required',['f'=>t('viloyat')]);
        if(!$d['tuman'])   $err['tuman']  =t('err_required',['f'=>t('tuman')]);
        if(!$d['phone'])   $err['phone']  =t('err_required',['f'=>t('phone')]);
        if($err){
            $images=$svc?Service::images((int)$svc['id']):[];
            render('service/edit',['title'=>t('edit'),'svc'=>$svc,'images'=>$images,'errors'=>$err,'old'=>array_merge($svc??[],$d),'viloyatlar'=>viloyatlar(),'specs'=>specializations()]);
            return;
        }
        $svc ? Service::update((int)$svc['id'],$d) : Service::create($uid,$d);
        flash('success',t('ok_saved')); redirect(APP_URL.'/dashboard');
    }
    public function uploadImage(): void {
        require_role('service'); csrf_verify();
        $svc = Service::byUser((int)user()['id']);
        if(!$svc) json_out(['error'=>'No service'],400);
        if(Service::imageCount((int)$svc['id'])>=6) json_out(['error'=>t('err_max_images')],400);
        if(empty($_FILES['image'])||$_FILES['image']['error']!==UPLOAD_ERR_OK) json_out(['error'=>t('err_upload')],400);
        $fn = upload_img($_FILES['image'], UPLOAD_SVC);
        if(!$fn) json_out(['error'=>t('err_upload')],400);
        Service::addImage((int)$svc['id'],$fn);
        json_out(['ok'=>true,'url'=>svc_img($fn),'filename'=>$fn]);
    }
    public function deleteImage(): void {
        require_role('service'); csrf_verify();
        $svc = Service::byUser((int)user()['id']);
        if(!$svc) json_out(['error'=>'No service'],400);
        $iid = (int)($_POST['image_id']??0);
        $fn  = Service::deleteImage($iid,(int)$svc['id']);
        if($fn){ $p=UPLOAD_SVC."/$fn"; if(file_exists($p)) unlink($p); json_out(['ok'=>true]); }
        json_out(['error'=>'Not found'],404);
    }
    public function deleteSvc(): void {
        require_role('service'); csrf_verify();
        $svc = Service::byUser((int)user()['id']);
        if(!$svc){ flash('error','Servis topilmadi'); redirect(APP_URL.'/dashboard'); }
        foreach(Service::images((int)$svc['id']) as $img){
            $p=UPLOAD_SVC."/{$img['filename']}"; if(file_exists($p)) unlink($p);
        }
        Service::delete((int)$svc['id']);
        flash('success',t('ok_deleted')); redirect(APP_URL.'/dashboard');
    }
}

// ════════════════════════════════════════════════════════════
class ReviewController {
    public function store(): void {
        require_login(); csrf_verify();
        $u   = user();
        if($u['role']!=='user'){ flash('error','Faqat foydalanuvchilar sharh yozishi mumkin'); redirect($_SERVER['HTTP_REFERER']??APP_URL.'/services'); }
        $sid     = (int)($_POST['service_id']??0);
        $rating  = (int)($_POST['rating']??0);
        $comment = clean($_POST['comment']??'');
        if($rating<1||$rating>5){ flash('error','Reyting 1-5'); redirect($_SERVER['HTTP_REFERER']??APP_URL.'/services'); }
        Review::upsert($sid,(int)$u['id'],$rating,$comment);
        flash('success',t('ok_saved'));
        redirect($_SERVER['HTTP_REFERER']??APP_URL.'/services/'.$sid);
    }
    public function delete(): void {
        require_login(); csrf_verify();
        Review::delete((int)($_POST['review_id']??0),(int)user()['id']);
        flash('success',t('ok_deleted'));
        redirect($_SERVER['HTTP_REFERER']??APP_URL.'/services');
    }
}

// ════════════════════════════════════════════════════════════
class FavoriteController {
    public function toggle(): void {
        require_login(); csrf_verify();
        $sid    = (int)($_POST['service_id']??0);
        $result = Favorite::toggle((int)user()['id'],$sid);
        json_out(['status'=>$result]);
    }
}

// ════════════════════════════════════════════════════════════
class UserController {
    public function profile(): void {
        require_login();
        $uid  = (int)user()['id'];
        render('user/profile',['title'=>t('profile'),'u'=>User::find($uid),'favorites'=>Favorite::forUser($uid),'reviews'=>Review::byUserList($uid),'errors'=>[]]);
    }
    public function updateProfile(): void {
        require_login(); csrf_verify();
        $uid = (int)user()['id'];
        $err = [];
        $fn  = clean($_POST['first_name']??'');
        $ln  = clean($_POST['last_name']??'');
        $em  = filter_var(trim($_POST['email']??''),FILTER_SANITIZE_EMAIL);
        $ph  = clean($_POST['phone']??'');
        if(!$fn) $err['first_name']=t('err_required',['f'=>t('first_name')]);
        if(!$ln) $err['last_name'] =t('err_required',['f'=>t('last_name')]);
        if($em){ $ex=User::byEmail($em); if($ex&&(int)$ex['id']!==$uid) $err['email']=t('err_exists',['f'=>'Email']); }
        if($ph){ $ex=User::byPhone($ph); if($ex&&(int)$ex['id']!==$uid) $err['phone']=t('err_exists',['f'=>t('phone')]); }
        $avatar = user()['avatar'];
        if(!empty($_FILES['avatar']['name'])){
            $fn2=upload_img($_FILES['avatar'],UPLOAD_AVT);
            if($fn2) $avatar=$fn2; else $err['avatar']=t('err_upload');
        }
        if($err){
            render('user/profile',['title'=>t('profile'),'u'=>User::find($uid),'favorites'=>Favorite::forUser($uid),'reviews'=>Review::byUserList($uid),'errors'=>$err]);
            return;
        }
        User::update($uid,['first_name'=>$fn,'last_name'=>$ln,'email'=>$em?:null,'phone'=>$ph?:null,'avatar'=>$avatar]);
        $np=$_POST['new_password']??''; $np2=$_POST['confirm_new_pw']??'';
        if($np&&strlen($np)>=8&&$np===$np2) User::updatePassword($uid,$np);
        $fresh=User::find($uid);
        $_SESSION['auth']=array_merge($_SESSION['auth'],['first_name'=>$fresh['first_name'],'last_name'=>$fresh['last_name'],'avatar'=>$fresh['avatar']]);
        flash('success',t('ok_saved')); redirect(APP_URL.'/profile');
    }
    public function setTheme(): void {
        $th=in_array($_GET['theme']??'',['light','dark'])?$_GET['theme']:'light';
        $_SESSION['theme']=$th;
        if(logged_in()){ User::update((int)user()['id'],['theme'=>$th]); $_SESSION['auth']['theme']=$th; }
        redirect($_SERVER['HTTP_REFERER']??APP_URL.'/services');
    }
}

// ════════════════════════════════════════════════════════════
class ApiController {
    public function nearby(): void {
        require_login();
        $lat=(float)($_GET['lat']??0); $lng=(float)($_GET['lng']??0);
        $km=min((float)($_GET['km']??10),50);
        if(!$lat||!$lng) json_out(['error'=>'coords required'],400);
        $svcs = Service::nearby($lat,$lng,$km,20);
        json_out(['services'=>$svcs]);
    }
    public function tumans(): void {
        $v = clean($_GET['viloyat']??'');
        $map=['toshkent_sh'=>["Yunusobod","Chilonzor","Mirzo Ulug'bek","Shayxontohur","Olmosoy","Yakkasaroy","Uchtepa","Bektemir","Sergeli","Mirobod","Hamza","Yashnobod"],'toshkent'=>["Zangiota","Qibray","Yuqorichirchiq","Ohangaron","Bo'stonliq","Parkent","Piskent","O'rtachirchiq","Chinoz"],'andijon'=>["Andijon shahri","Asaka","Baliqchi","Bo'z","Buloqboshi","Izboskan","Jalaquduq","Marhamat","Oltinko'l","Shahrixon"],'fargona'=>["Farg'ona shahri","Quva","Rishton","Bag'dod","Beshariq","Buvayda","Dang'ara","Marg'ilon","Yozyovon"],'samarqand'=>["Samarqand shahri","Urgut","Kattaqo'rg'on","Bulung'ur","Ishtixon","Jomboy","Pastdarg'om","Toyloq"],'namangan'=>["Namangan shahri","Chortoq","Chust","Kosonsoy","Pop","To'raqo'rg'on","Uychi"],'buxoro'=>["Buxoro shahri","G'ijduvon","Kogon","Romitan","Shofirkon","Vobkent"],'qashqadaryo'=>["Qarshi shahri","Chiroqchi","G'uzor","Kitob","Shahrisabz","Yakkabog'"],'surxondaryo'=>["Termiz shahri","Angor","Boysun","Denov","Jarqo'rg'on","Uzun"],'xorazm'=>["Urganch shahri","Bog'ot","Gurlan","Xiva","Xonqa"],'navoiy'=>["Navoiy shahri","Karmana","Navbahor","Nurota","Uchquduq"],'jizzax'=>["Jizzax shahri","Arnasoy","Forish","Zafarobod","Zomin"],'sirdaryo'=>["Guliston shahri","Boyovut","Oqoltin","Sardoba","Xovos"],'qoraqalpogiston'=>["Nukus shahri","Beruniy","Chimboy","Kegeyli","Qo'ng'irot","Xo'jayli"]];
        json_out(['tumans'=>$map[$v]??[]]);
    }
}

// ════════════════════════════════════════════════════════════
class AdminController {
    private function boot(): void { require_role('admin'); }
    public function index(): void {
        $this->boot();
        $stats=['users'=>(int)\App\Core\DB::scalar('SELECT COUNT(*) FROM users'),'services'=>(int)\App\Core\DB::scalar('SELECT COUNT(*) FROM services'),'reviews'=>(int)\App\Core\DB::scalar('SELECT COUNT(*) FROM reviews'),'pending'=>(int)\App\Core\DB::scalar('SELECT COUNT(*) FROM services WHERE is_approved=0')];
        render('admin/dashboard',['title'=>t('admin_panel'),'stats'=>$stats,'recentSvcs'=>Service::adminList(0,5)]);
    }
    public function users(): void {
        $this->boot();
        $q=(clean($_GET['q']??'')); $page=max(1,(int)($_GET['page']??1));
        $total=User::count($q); $pag=paginate($total,20,$page);
        render('admin/users',['title'=>t('total_users'),'users'=>User::list($pag['offset'],20,$q),'pag'=>$pag,'q'=>$q]);
    }
    public function toggleUser(): void { $this->boot();csrf_verify();User::toggle((int)($_POST['uid']??0));flash('success',"Holat o'zgardi");redirect(APP_URL.'/admin/users'); }
    public function services(): void {
        $this->boot(); $page=max(1,(int)($_GET['page']??1));
        $total=Service::adminCount(); $pag=paginate($total,20,$page);
        render('admin/services',['title'=>t('total_services'),'svcs'=>Service::adminList($pag['offset'],20),'pag'=>$pag]);
    }
    public function approveService(): void { $this->boot();csrf_verify();Service::approve((int)($_POST['sid']??0),(int)($_POST['val']??1));flash('success',t('ok_saved'));redirect(APP_URL.'/admin/services'); }
    public function deleteService(): void {
        $this->boot();csrf_verify();
        $id=(int)($_POST['sid']??0);
        foreach(Service::images($id) as $img){ $p=UPLOAD_SVC."/{$img['filename']}";if(file_exists($p))unlink($p); }
        Service::delete($id); flash('success',t('ok_deleted')); redirect(APP_URL.'/admin/services');
    }
    public function reviews(): void {
        $this->boot(); $page=max(1,(int)($_GET['page']??1));
        $total=Review::adminCount(); $pag=paginate($total,25,$page);
        render('admin/reviews',['title'=>t('total_reviews'),'reviews'=>Review::adminList($pag['offset'],25),'pag'=>$pag]);
    }
    public function deleteReview(): void { $this->boot();csrf_verify();Review::adminDelete((int)($_POST['rid']??0));flash('success',t('ok_deleted'));redirect(APP_URL.'/admin/reviews'); }
}
