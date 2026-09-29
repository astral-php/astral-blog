<?php

declare(strict_types=1);

use Database\Migration\Migration;

class CreateBlogPostsTable extends Migration
{
    public function up(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS blog_posts (
                    id           INTEGER PRIMARY KEY,
                    category_id  INTEGER NULL REFERENCES blog_categories(id) ON DELETE SET NULL,
                    title        TEXT    NOT NULL,
                    slug         TEXT    NOT NULL UNIQUE,
                    excerpt      TEXT    NOT NULL DEFAULT '',
                    body         TEXT    NOT NULL DEFAULT '',
                    status       TEXT    NOT NULL DEFAULT 'draft',
                    published_at TEXT    NULL,
                    created_at   TEXT    NOT NULL DEFAULT (datetime('now')),
                    updated_at   TEXT    NOT NULL DEFAULT (datetime('now'))
                )
            SQL);
            $pdo->exec('CREATE INDEX IF NOT EXISTS blog_posts_status_idx ON blog_posts (status)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS blog_posts_category_idx ON blog_posts (category_id)');
        } else {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS `blog_posts` (
                    `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
                    `category_id`  INT UNSIGNED  NULL,
                    `title`        VARCHAR(255)  NOT NULL,
                    `slug`         VARCHAR(255)  NOT NULL,
                    `excerpt`      TEXT          NOT NULL,
                    `body`         LONGTEXT      NOT NULL,
                    `status`       VARCHAR(20)   NOT NULL DEFAULT 'draft',
                    `published_at` DATETIME      NULL,
                    `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `blog_posts_slug_unique` (`slug`),
                    KEY `blog_posts_status_idx` (`status`),
                    KEY `blog_posts_category_idx` (`category_id`),
                    CONSTRAINT `blog_posts_category_fk`
                        FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`)
                        ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL);
        }
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS blog_posts');
    }
}
