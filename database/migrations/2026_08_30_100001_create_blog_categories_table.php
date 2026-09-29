<?php

declare(strict_types=1);

use Database\Migration\Migration;

class CreateBlogCategoriesTable extends Migration
{
    public function up(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS blog_categories (
                    id         INTEGER PRIMARY KEY,
                    name       TEXT    NOT NULL,
                    slug       TEXT    NOT NULL UNIQUE,
                    created_at TEXT    NOT NULL DEFAULT (datetime('now'))
                )
            SQL);
        } else {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS `blog_categories` (
                    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name`       VARCHAR(120) NOT NULL,
                    `slug`       VARCHAR(140) NOT NULL,
                    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `blog_categories_slug_unique` (`slug`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL);
        }
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS blog_categories');
    }
}
