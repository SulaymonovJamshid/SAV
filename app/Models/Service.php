<?php
namespace App\Models;
use App\Core\DB;

class Service {
    // ── Decode JSON fields ────────────────────────────────────
    private static function decode(array $r): array {
        $r['specializations'] = json_decode($r['specializations']??'[]',true)??[];
        $r['work_days']       = json_decode($r['work_days']??'[]',true)??[];
        return $r;
    }

    // ── Finders ───────────────────────────────────────────────
    public static function byId(int $id): ?array {
        $r = DB::one('SELECT * FROM v_services WHERE id=?',[$id]);
        return $r ? self::decode($r) : null;
    }
    public static function bySlug(string $slug): ?array {
        $r = DB::one('SELECT * FROM v_services WHERE slug=?',[$slug]);
        return $r ? self::decode($r) : null;
    }
    public static function byUser(int $uid): ?array {
        $r = DB::one('SELECT * FROM v_services WHERE user_id=?',[$uid]);
        return $r ? self::decode($r) : null;
    }

    // ── List with filters ─────────────────────────────────────
    public static function list(array $f, int $lim, int $off): array {
        [$w,$p] = self::where($f);
        $ob = match($f['sort']??'newest'){'rating'=>'avg_rating DESC','price'=>'price_from ASC',default=>'id DESC'};
        $rows = DB::all("SELECT * FROM v_services $w ORDER BY $ob LIMIT ? OFFSET ?", [...$p,$lim,$off]);
        return array_map([self::class,'decode'],$rows);
    }
    public static function count(array $f): int {
        [$w,$p] = self::where($f);
        return (int)DB::scalar("SELECT COUNT(*) FROM v_services $w",$p);
    }
    private static function where(array $f): array {
        // $c=['is_active=1']; $p=[];
        // // Admin yoki o'z servisini ko'rayotgan owner uchun is_approved tekshirilmaydi
        // if(empty($f['skip_approval'])) $c[]='is_approved=1';
        // if(!empty($f['viloyat']))   {$c[]='viloyat=?';       $p[]=$f['viloyat'];}
        // if(!empty($f['tuman']))     {$c[]='tuman LIKE ?';    $p[]="%{$f['tuman']}%";}
        // if(!empty($f['shahar']))    {$c[]='shahar LIKE ?';   $p[]="%{$f['shahar']}%";}
        // if(!empty($f['spec']))      {$c[]='JSON_CONTAINS(specializations,?)'; $p[]=json_encode($f['spec']);}
        // if(!empty($f['search']))    {$c[]='MATCH(name,address,description) AGAINST(? IN BOOLEAN MODE)'; $p[]=$f['search'].'*';}
        // if(!empty($f['rating_min'])){$c[]='avg_rating>=?';   $p[]=(float)$f['rating_min'];}
        // return ['WHERE '.implode(' AND ',$c),$p];
    $c = ['is_active=1', 'is_approved=1'];
    
    // Satr va bo'sh parametrlar massivini qaytaramiz
    return [' WHERE ' . implode(' AND ', $c), []]; 
    }

    // ── Create ────────────────────────────────────────────────
    public static function create(int $uid, array $d): int {
        $slug = self::uniqueSlug(make_slug($d['name']));
        // is_approved=1 — auto tasdiqlash (admin panel orqali boshqarish mumkin)
        return (int) DB::insert(
            'INSERT INTO services (user_id,name,slug,description,specializations,experience_years,
             viloyat,tuman,shahar,address,latitude,longitude,work_start,work_end,work_days,is_24h,
             phone,website,telegram,price_from,price_to,price_note,is_approved) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)',
            [$uid,$d['name'],$slug,$d['description']??null,
             json_encode($d['specializations']??[]),(int)($d['experience_years']??0),
             $d['viloyat']??'',$d['tuman']??'',$d['shahar']??'',$d['address']??'',
             $d['latitude']??(null),$d['longitude']??(null),
             $d['work_start']??'08:00',$d['work_end']??'18:00',
             json_encode($d['work_days']??[]),empty($d['is_24h'])?0:1,
             $d['phone']??'',$d['website']??null,$d['telegram']??null,
             (int)($d['price_from']??0),(int)($d['price_to']??0),$d['price_note']??null]
        );
    }

    // ── Update ────────────────────────────────────────────────
    public static function update(int $id, array $d): void {
        $slug = self::uniqueSlug(make_slug($d['name']),$id);
        DB::run(
            'UPDATE services SET name=?,slug=?,description=?,specializations=?,experience_years=?,
             viloyat=?,tuman=?,shahar=?,address=?,latitude=?,longitude=?,work_start=?,work_end=?,
             work_days=?,is_24h=?,phone=?,website=?,telegram=?,price_from=?,price_to=?,price_note=?
             WHERE id=?',
            [$d['name'],$slug,$d['description']??null,
             json_encode($d['specializations']??[]),(int)($d['experience_years']??0),
             $d['viloyat']??'',$d['tuman']??'',$d['shahar']??'',$d['address']??'',
             !empty($d['latitude'])?(float)$d['latitude']:null,
             !empty($d['longitude'])?(float)$d['longitude']:null,
             $d['work_start']??'08:00',$d['work_end']??'18:00',
             json_encode($d['work_days']??[]),empty($d['is_24h'])?0:1,
             $d['phone']??'',$d['website']??null,$d['telegram']??null,
             (int)($d['price_from']??0),(int)($d['price_to']??0),$d['price_note']??null,$id]
        );
    }

    public static function delete(int $id): void { DB::run('DELETE FROM services WHERE id=?',[$id]); }
    public static function approve(int $id,int $v=1): void { DB::run('UPDATE services SET is_approved=? WHERE id=?',[$v,$id]); }

    // ── Images ────────────────────────────────────────────────
    public static function images(int $sid): array {
        return DB::all('SELECT * FROM service_images WHERE service_id=? ORDER BY sort_order',[$sid]);
    }
    public static function addImage(int $sid, string $fn): void {
        $ord = (int)DB::scalar('SELECT COALESCE(MAX(sort_order)+1,0) FROM service_images WHERE service_id=?',[$sid]);
        DB::run('INSERT INTO service_images (service_id,filename,sort_order) VALUES (?,?,?)',[$sid,$fn,$ord]);
    }
    public static function deleteImage(int $iid, int $sid): ?string {
        $r = DB::one('SELECT filename FROM service_images WHERE id=? AND service_id=?',[$iid,$sid]);
        if(!$r) return null;
        DB::run('DELETE FROM service_images WHERE id=?',[$iid]);
        return $r['filename'];
    }
    public static function imageCount(int $sid): int {
        return (int)DB::scalar('SELECT COUNT(*) FROM service_images WHERE service_id=?',[$sid]);
    }

    // ── Admin list ────────────────────────────────────────────
    public static function adminList(int $off, int $lim): array {
        $rows = DB::all('SELECT * FROM v_services ORDER BY id DESC LIMIT ? OFFSET ?',[$lim,$off]);
        return array_map([self::class,'decode'],$rows);
    }
    public static function adminCount(): int { return (int)DB::scalar('SELECT COUNT(*) FROM services'); }

    // ── Nearby (PHP-side haversine) ───────────────────────────
    public static function nearby(float $lat, float $lng, float $km=10, int $lim=20): array {
        $rows = DB::all('SELECT id,name,address,viloyat,phone,latitude,longitude,
            (SELECT filename FROM service_images si WHERE si.service_id=s.id ORDER BY si.sort_order LIMIT 1) AS cover
            FROM services s WHERE is_approved=1 AND is_active=1 AND latitude IS NOT NULL');
        $res=[];
        foreach($rows as $s){
            $d=haversine($lat,$lng,(float)$s['latitude'],(float)$s['longitude']);
            if($d<=$km){$s['km']=round($d,2);$res[]=$s;}
        }
        usort($res,fn($a,$b)=>$a['km']<=>$b['km']);
        return array_slice($res,0,$lim);
    }

    // ── Unique slug ───────────────────────────────────────────
    private static function uniqueSlug(string $slug, int $ex=0): string {
        $base=$slug; $i=1;
        while(DB::one('SELECT id FROM services WHERE slug=? AND id!=?',[$slug,$ex])){$slug=$base.'-'.$i++;}
        return $slug;
    }
}
