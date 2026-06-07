-- ============================================================
--  SmartAvtoServis  |  MySQL 8.0+  |  utf8mb4_unicode_ci
-- ============================================================
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `smartavto`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `smartavto`;

-- ─────────────────────────────────────────────────────────────
--  USERS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `email`          VARCHAR(180)      NULL UNIQUE,
  `phone`          VARCHAR(20)       NULL UNIQUE,
  `password_hash`  VARCHAR(255)      NOT NULL,
  `first_name`     VARCHAR(80)       NOT NULL DEFAULT '',
  `last_name`      VARCHAR(80)       NOT NULL DEFAULT '',
  `role`           ENUM('user','service','admin') NOT NULL DEFAULT 'user',
  `avatar`         VARCHAR(255)      NULL,
  `is_verified`    TINYINT(1)        NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1)        NOT NULL DEFAULT 1,
  `lang`           CHAR(2)           NOT NULL DEFAULT 'uz',
  `theme`          ENUM('light','dark') NOT NULL DEFAULT 'light',
  `created_at`     DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP
                                     ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email`  (`email`),
  KEY `idx_phone`  (`phone`),
  KEY `idx_role`   (`role`),
  CONSTRAINT `chk_contact`
    CHECK (`email` IS NOT NULL OR `phone` IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
--  SMS VERIFICATIONS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `sms_verifications` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone`       VARCHAR(20)     NOT NULL,
  `code`        CHAR(6)         NOT NULL,
  `is_used`     TINYINT(1)      NOT NULL DEFAULT 0,
  `attempts`    TINYINT         NOT NULL DEFAULT 0,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at`  DATETIME        NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_phone_code` (`phone`, `code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
--  SERVICES
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `services` (
  `id`               BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`          BIGINT UNSIGNED  NOT NULL,
  `name`             VARCHAR(200)     NOT NULL,
  `slug`             VARCHAR(220)     NOT NULL,
  `description`      TEXT             NULL,
  `specializations`  JSON             NOT NULL DEFAULT ('[]'),
  `experience_years` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `viloyat`          VARCHAR(60)      NOT NULL DEFAULT '',
  `tuman`            VARCHAR(100)     NOT NULL DEFAULT '',
  `shahar`           VARCHAR(100)     NOT NULL DEFAULT '',
  `address`          VARCHAR(300)     NOT NULL DEFAULT '',
  `latitude`         DECIMAL(11,8)    NULL,
  `longitude`        DECIMAL(11,8)    NULL,
  `work_start`       TIME             NOT NULL DEFAULT '08:00:00',
  `work_end`         TIME             NOT NULL DEFAULT '18:00:00',
  `work_days`        JSON             NOT NULL DEFAULT ('[]'),
  `is_24h`           TINYINT(1)       NOT NULL DEFAULT 0,
  `phone`            VARCHAR(20)      NOT NULL DEFAULT '',
  `website`          VARCHAR(255)     NULL,
  `telegram`         VARCHAR(100)     NULL,
  `price_from`       INT UNSIGNED     NOT NULL DEFAULT 0,
  `price_to`         INT UNSIGNED     NOT NULL DEFAULT 0,
  `price_note`       TEXT             NULL,
  `is_approved`      TINYINT(1)       NOT NULL DEFAULT 0,
  `is_active`        TINYINT(1)       NOT NULL DEFAULT 1,
  `created_at`       DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                                      ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_owner`  (`user_id`),
  UNIQUE KEY `uq_slug`   (`slug`),
  KEY `idx_viloyat`      (`viloyat`),
  KEY `idx_approved`     (`is_approved`, `is_active`),
  KEY `idx_coords`       (`latitude`, `longitude`),
  FULLTEXT KEY `ft_search` (`name`, `address`, `description`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
--  SERVICE IMAGES  (max 6)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `service_images` (
  `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `service_id`  BIGINT UNSIGNED  NOT NULL,
  `filename`    VARCHAR(255)     NOT NULL,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_service_order` (`service_id`, `sort_order`),
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
--  REVIEWS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `reviews` (
  `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `service_id`  BIGINT UNSIGNED  NOT NULL,
  `user_id`     BIGINT UNSIGNED  NOT NULL,
  `rating`      TINYINT UNSIGNED NOT NULL,
  `comment`     TEXT             NULL,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                                 ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_review` (`service_id`, `user_id`),
  KEY `idx_service` (`service_id`),
  KEY `idx_user`    (`user_id`),
  CONSTRAINT `chk_rating` CHECK (`rating` BETWEEN 1 AND 5),
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
--  FAVORITES
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `favorites` (
  `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`     BIGINT UNSIGNED  NOT NULL,
  `service_id`  BIGINT UNSIGNED  NOT NULL,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_fav` (`user_id`, `service_id`),
  FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
--  VIEW: services with stats
-- ─────────────────────────────────────────────────────────────
CREATE OR REPLACE VIEW `v_services` AS
SELECT
  s.*,
  u.first_name        AS owner_first,
  u.last_name         AS owner_last,
  ROUND(COALESCE(AVG(r.rating), 0), 1) AS avg_rating,
  COUNT(DISTINCT r.id)  AS review_count,
  COUNT(DISTINCT f.id)  AS fav_count,
  (SELECT si.filename
   FROM service_images si
   WHERE si.service_id = s.id
   ORDER BY si.sort_order LIMIT 1) AS cover
FROM services s
JOIN  users     u ON u.id = s.user_id
LEFT JOIN reviews   r ON r.service_id = s.id
LEFT JOIN favorites f ON f.service_id = s.id
GROUP BY s.id;

-- ─────────────────────────────────────────────────────────────
--  STORED PROCEDURE: nearby
-- ─────────────────────────────────────────────────────────────
DROP PROCEDURE IF EXISTS `sp_nearby`;
DELIMITER $$
CREATE PROCEDURE `sp_nearby`(
  IN p_lat   DOUBLE,
  IN p_lng   DOUBLE,
  IN p_km    DOUBLE,
  IN p_limit INT
)
BEGIN
  SELECT id, name, address, viloyat, phone, latitude, longitude,
    ROUND((6371 * ACOS(
      LEAST(1.0, COS(RADIANS(p_lat)) * COS(RADIANS(latitude)) *
      COS(RADIANS(longitude) - RADIANS(p_lng)) +
      SIN(RADIANS(p_lat)) * SIN(RADIANS(latitude)))
    )), 2) AS km
  FROM services
  WHERE is_approved = 1 AND is_active = 1 AND latitude IS NOT NULL
  HAVING km <= p_km
  ORDER BY km ASC
  LIMIT p_limit;
END$$
DELIMITER ;

-- ─────────────────────────────────────────────────────────────
--  SEED DATA
-- ─────────────────────────────────────────────────────────────
-- Admin  password = Admin@12345
INSERT IGNORE INTO `users`
  (`email`,`phone`,`password_hash`,`first_name`,`last_name`,
   `role`,`is_verified`,`is_active`)
VALUES (
  'admin@smartavto.uz', NULL,
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHphaLuiS',
  'Admin', 'User', 'admin', 1, 1
);

-- Service owner  password = Test@1234
INSERT IGNORE INTO `users`
  (`email`,`phone`,`password_hash`,`first_name`,`last_name`,
   `role`,`is_verified`,`is_active`)
VALUES (
  NULL, '+998901234567',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHphaLuiS',
  'Jasur', 'Toshmatov', 'service', 1, 1
);

-- Regular user  password = Test@1234
INSERT IGNORE INTO `users`
  (`email`,`phone`,`password_hash`,`first_name`,`last_name`,
   `role`,`is_verified`,`is_active`)
VALUES (
  'user@test.com', NULL,
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHphaLuiS',
  'Kamol', 'Yusupov', 'user', 1, 1
);

-- Demo service
INSERT IGNORE INTO `services`
  (`user_id`,`name`,`slug`,`description`,`specializations`,
   `experience_years`,`viloyat`,`tuman`,`shahar`,`address`,
   `latitude`,`longitude`,`work_start`,`work_end`,`work_days`,
   `is_24h`,`phone`,`price_from`,`price_to`,`is_approved`,`is_active`)
VALUES (
  2, 'AutoMaster Pro', 'automaster-pro',
  'Toshkentdagi eng ishonchli avto servis. 10+ yillik tajriba.',
  '["engine","electrical","diagnostics","oil_change"]',
  10, 'toshkent_sh', 'Yunusobod', 'Toshkent',
  'Yunusobod 19-mavze, 45-uy',
  41.3345678, 69.3012345,
  '08:00', '20:00', '["Mon","Tue","Wed","Thu","Fri","Sat"]',
  0, '+998901234567', 50000, 500000, 1, 1
);

-- Demo review
INSERT IGNORE INTO `reviews` (`service_id`,`user_id`,`rating`,`comment`)
VALUES (1, 3, 5, 'Juda yaxshi servis! Tez va sifatli ta\'mirlash.');

SET FOREIGN_KEY_CHECKS = 1;
