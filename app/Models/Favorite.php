<?php
namespace App\Models;
use App\Core\DB;

class Favorite {
    public static function toggle(int $uid, int $sid): string {
        $ex = DB::one('SELECT id FROM favorites WHERE user_id=? AND service_id=?',[$uid,$sid]);
        if($ex){ DB::run('DELETE FROM favorites WHERE user_id=? AND service_id=?',[$uid,$sid]); return 'removed'; }
        DB::run('INSERT INTO favorites (user_id,service_id) VALUES (?,?)',[$uid,$sid]);
        return 'added';
    }
    public static function isFav(int $uid, int $sid): bool {
        return (bool)DB::one('SELECT id FROM favorites WHERE user_id=? AND service_id=? LIMIT 1',[$uid,$sid]);
    }
    public static function ids(int $uid): array {
        return DB::all('SELECT service_id FROM favorites WHERE user_id=?',[$uid])
            ? array_column(DB::all('SELECT service_id FROM favorites WHERE user_id=?',[$uid]),'service_id')
            : [];
    }
    public static function forUser(int $uid): array {
        return DB::all('SELECT s.*,ROUND(COALESCE(AVG(r.rating),0),1) AS avg_rating,COUNT(DISTINCT r.id) AS review_count,
            (SELECT si.filename FROM service_images si WHERE si.service_id=s.id ORDER BY si.sort_order LIMIT 1) AS cover
            FROM favorites f JOIN services s ON s.id=f.service_id LEFT JOIN reviews r ON r.service_id=s.id
            WHERE f.user_id=? GROUP BY s.id ORDER BY f.created_at DESC',[$uid]);
    }
}
