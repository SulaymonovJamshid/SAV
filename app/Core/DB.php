<?php
namespace App\Core;

use PDO, PDOException, PDOStatement;

class DB {
    private static ?PDO $pdo = null;

    public static function conn(): PDO {
        if (self::$pdo) return self::$pdo;
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            env('DB_HOST','localhost'), env('DB_PORT','3306'), env('DB_NAME','smartavto'));
        try {
            self::$pdo = new PDO($dsn, env('DB_USER','root'), env('DB_PASS',''), [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ]);
        } catch (PDOException $e) {
            error_log('[DB] ' . $e->getMessage());
            http_response_code(500);
            die('Database connection failed.');
        }
        return self::$pdo;
    }

    public static function run(string $sql, array $p = []): PDOStatement {
        $st = self::conn()->prepare($sql);
        $st->execute($p);
        return $st;
    }

    public static function one(string $sql, array $p = []): ?array {
        $r = self::run($sql, $p)->fetch();
        return $r ?: null;
    }

    public static function all(string $sql, array $p = []): array {
        return self::run($sql, $p)->fetchAll();
    }

    public static function scalar(string $sql, array $p = []): mixed {
        return self::run($sql, $p)->fetchColumn();
    }

    public static function insert(string $sql, array $p = []): string {
        self::run($sql, $p);
        return self::conn()->lastInsertId();
    }

    public static function begin(): void   { self::conn()->beginTransaction(); }
    public static function commit(): void   { self::conn()->commit(); }
    public static function rollback(): void { self::conn()->rollBack(); }
}
