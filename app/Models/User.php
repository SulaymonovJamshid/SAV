<?php
namespace App\Models;
use App\Core\DB;

class User {
    public static function find(int $id): ?array {
        return DB::one('SELECT * FROM users WHERE id=?', [$id]);
    }
    public static function byEmail(string $e): ?array {
        return DB::one('SELECT * FROM users WHERE email=?', [$e]);
    }
    public static function byPhone(string $p): ?array {
        return DB::one('SELECT * FROM users WHERE phone=?', [$p]);
    }
    public static function byLogin(string $v): ?array {
        return DB::one('SELECT * FROM users WHERE email=? OR phone=? LIMIT 1', [$v,$v]);
    }
    public static function create(array $d): int {
        return (int) DB::insert(
            'INSERT INTO users (email,phone,password_hash,first_name,last_name,role,is_verified,lang)
             VALUES (?,?,?,?,?,?,?,?)',
            [$d['email']??null,$d['phone']??null,
             password_hash($d['password'],PASSWORD_BCRYPT,['cost'=>12]),
             $d['first_name']??'',$d['last_name']??'',$d['role']??'user',
             $d['is_verified']??0,$d['lang']??'uz']
        );
    }
    public static function update(int $id, array $d): void {
        $allowed = ['first_name','last_name','email','phone','avatar','lang','theme','is_active','is_verified'];
        $sets=[]; $vals=[];
        foreach($d as $k=>$v) if(in_array($k,$allowed,true)){$sets[]="$k=?";$vals[]=$v;}
        if(!$sets) return;
        $vals[]=$id;
        DB::run('UPDATE users SET '.implode(',',$sets).' WHERE id=?',$vals);
    }
    public static function updatePassword(int $id, string $pw): void {
        DB::run('UPDATE users SET password_hash=? WHERE id=?',
            [password_hash($pw,PASSWORD_BCRYPT,['cost'=>12]),$id]);
    }
    public static function verify(int $id): void {
        DB::run('UPDATE users SET is_verified=1 WHERE id=?',[$id]);
    }
    public static function toggle(int $id): void {
        DB::run('UPDATE users SET is_active=1-is_active WHERE id=?',[$id]);
    }
    public static function list(int $off, int $lim, string $q=''): array {
        if($q){$lk="%$q%";
            return DB::all('SELECT * FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?',[$lk,$lk,$lk,$lk,$lim,$off]);}
        return DB::all('SELECT * FROM users ORDER BY id DESC LIMIT ? OFFSET ?',[$lim,$off]);
    }
    public static function count(string $q=''): int {
        if($q){$lk="%$q%";return(int)DB::scalar('SELECT COUNT(*) FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?',[$lk,$lk,$lk,$lk]);}
        return(int)DB::scalar('SELECT COUNT(*) FROM users');
    }
}
