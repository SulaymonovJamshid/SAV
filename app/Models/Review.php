<?php
namespace App\Models;
use App\Core\DB;

class Review {
    public static function forService(int $sid): array {
        return DB::all('SELECT r.*,u.first_name,u.last_name,u.avatar FROM reviews r
            JOIN users u ON u.id=r.user_id WHERE r.service_id=? ORDER BY r.created_at DESC',[$sid]);
    }
    public static function byUser(int $sid, int $uid): ?array {
        return DB::one('SELECT * FROM reviews WHERE service_id=? AND user_id=?',[$sid,$uid]);
    }
    public static function upsert(int $sid, int $uid, int $rating, string $comment): void {
        DB::run('INSERT INTO reviews (service_id,user_id,rating,comment) VALUES (?,?,?,?)
            ON DUPLICATE KEY UPDATE rating=VALUES(rating),comment=VALUES(comment)',[$sid,$uid,$rating,$comment]);
    }
    public static function delete(int $id, int $uid): void {
        DB::run('DELETE FROM reviews WHERE id=? AND user_id=?',[$id,$uid]);
    }
    public static function adminDelete(int $id): void { DB::run('DELETE FROM reviews WHERE id=?',[$id]); }
    public static function adminList(int $off, int $lim): array {
        return DB::all('SELECT r.*,u.first_name,u.last_name,s.name AS svc_name FROM reviews r
            JOIN users u ON u.id=r.user_id JOIN services s ON s.id=r.service_id
            ORDER BY r.id DESC LIMIT ? OFFSET ?',[$lim,$off]);
    }
    public static function adminCount(): int { return (int)DB::scalar('SELECT COUNT(*) FROM reviews'); }
    public static function byUserList(int $uid): array {
        return DB::all('SELECT r.*,s.name AS svc_name,s.id AS svc_id FROM reviews r
            JOIN services s ON s.id=r.service_id WHERE r.user_id=? ORDER BY r.created_at DESC LIMIT 20',[$uid]);
    }
}
