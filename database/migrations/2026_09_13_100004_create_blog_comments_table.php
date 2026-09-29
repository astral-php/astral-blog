<?php

declare(strict_types=1);

use Database\Migration\Migration;

class CreateBlogCommentsTable extends Migration
{
    public function up(PDO $pdo): void
    {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS blog_comments (
                    id         INTEGER PRIMARY KEY,
                    post_id    INTEGER NOT NULL REFERENCES blog_posts(id) ON DELETE CASCADE,
                    author     TEXT    NOT NULL,
                    email      TEXT    NOT NULL DEFAULT '',
                    body       TEXT    NOT NULL,
                    status     TEXT    NOT NULL DEFAULT 'pending',
                    created_at TEXT    NOT NULL DEFAULT (datetime('now'))
                )
            SQL);
            $pdo->exec('CREATE INDEX IF NOT EXISTS blog_comments_post_idx ON blog_comments (post_id)');
            $pdo->exec('CREATE INDEX IF NOT EXISTS blog_comments_status_idx ON blog_comments (status)');
        } else {
            $pdo->exec(<<<'SQL'
                CREATE TABLE IF NOT EXISTS `blog_comments` (
                    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `post_id`    INT UNSIGNED NOT NULL,
                    `author`     VARCHAR(120) NOT NULL,
                    `email`      VARCHAR(180) NOT NULL DEFAULT '',
                    `body`       TEXT         NOT NULL,
                    `status`     VARCHAR(20)  NOT NULL DEFAULT 'pending',
                    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `blog_comments_post_idx` (`post_id`),
                    KEY `blog_comments_status_idx` (`status`),
                    CONSTRAINT `blog_comments_post_fk`
                        FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`)
                        ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL);
        }
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS blog_comments');
    }
}
