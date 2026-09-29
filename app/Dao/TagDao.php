<?php

declare(strict_types=1);

namespace App\Dao;

use App\Models\Tag;
use Database\AbstractDao;

/**
 * @extends AbstractDao<Tag>
 */
final class TagDao extends AbstractDao
{
    protected function getTable(): string
    {
        return 'blog_tags';
    }

    protected function getModelClass(): string
    {
        return Tag::class;
    }

    public function findBySlug(string $slug): ?Tag
    {
        $rows = $this->query(
            "SELECT * FROM {$this->getTable()} WHERE slug = :slug LIMIT 1",
            [':slug' => $slug],
        );

        /** @var Tag|null */
        return $rows[0] ?? null;
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        if ($exceptId === null) {
            return $this->findBySlug($slug) !== null;
        }

        $rows = $this->query(
            "SELECT id FROM {$this->getTable()} WHERE slug = :slug AND id != :id LIMIT 1",
            [':slug' => $slug, ':id' => $exceptId],
        );

        return $rows !== [];
    }

    /**
     * @return list<Tag>
     */
    public function forPost(int $postId): array
    {
        /** @var list<Tag> */
        return $this->query(
            "SELECT t.* FROM {$this->getTable()} t
             INNER JOIN blog_post_tag pt ON pt.tag_id = t.id
             WHERE pt.post_id = :post_id
             ORDER BY t.name ASC",
            [':post_id' => $postId],
        );
    }

    /**
     * Remplace les tags d'un article.
     *
     * @param list<int> $tagIds
     */
    public function syncForPost(int $postId, array $tagIds): void
    {
        $this->execute('DELETE FROM blog_post_tag WHERE post_id = :id', [':id' => $postId]);

        $unique = [];
        foreach ($tagIds as $tagId) {
            $id = (int) $tagId;
            if ($id > 0) {
                $unique[$id] = $id;
            }
        }

        foreach ($unique as $tagId) {
            $this->execute(
                'INSERT INTO blog_post_tag (post_id, tag_id) VALUES (:post_id, :tag_id)',
                [':post_id' => $postId, ':tag_id' => $tagId],
            );
        }
    }

    public function detachFromAllPosts(int $tagId): void
    {
        $this->execute('DELETE FROM blog_post_tag WHERE tag_id = :id', [':id' => $tagId]);
    }
}
