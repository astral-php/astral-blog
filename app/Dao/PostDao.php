<?php

declare(strict_types=1);

namespace App\Dao;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Database\AbstractDao;

/**
 * @extends AbstractDao<Post>
 */
final class PostDao extends AbstractDao
{
    protected function getTable(): string
    {
        return 'blog_posts';
    }

    protected function getModelClass(): string
    {
        return Post::class;
    }

    public function findBySlug(string $slug): ?Post
    {
        $rows = $this->query(
            "SELECT * FROM {$this->getTable()} WHERE slug = :slug LIMIT 1",
            [':slug' => $slug],
        );

        /** @var Post|null */
        return $rows[0] ?? null;
    }

    public function findPublishedBySlug(string $slug): ?Post
    {
        $rows = $this->query(
            "SELECT * FROM {$this->getTable()}
             WHERE slug = :slug AND status = :status
             LIMIT 1",
            [':slug' => $slug, ':status' => Post::STATUS_PUBLISHED],
        );

        /** @var Post|null */
        return $rows[0] ?? null;
    }

    /**
     * @return list<Post>
     */
    public function findPublished(string $orderBy = 'published_at', string $direction = 'DESC'): array
    {
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $orderBy   = in_array($orderBy, ['published_at', 'created_at', 'title', 'id'], true)
            ? $orderBy
            : 'published_at';

        /** @var list<Post> */
        return $this->query(
            "SELECT * FROM {$this->getTable()}
             WHERE status = :status
             ORDER BY {$orderBy} {$direction}",
            [':status' => Post::STATUS_PUBLISHED],
        );
    }

    /**
     * Liste publiée paginée, avec recherche optionnelle (title / excerpt / body).
     *
     * @return array{data: list<Post>, total: int, pages: int, current: int, per_page: int, q: string}
     */
    public function paginatePublished(int $page = 1, int $perPage = 6, string $q = ''): array
    {
        $page    = max(1, $page);
        $perPage = max(1, min(50, $perPage));
        $offset  = ($page - 1) * $perPage;
        $q       = trim($q);

        $where  = 'status = :status';
        $params = [':status' => Post::STATUS_PUBLISHED];

        if ($q !== '') {
            $where .= ' AND (title LIKE :q OR excerpt LIKE :q OR body LIKE :q)';
            $params[':q'] = '%' . $q . '%';
        }

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->getTable()} WHERE {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        /** @var list<Post> $data */
        $data = $this->query(
            "SELECT * FROM {$this->getTable()}
             WHERE {$where}
             ORDER BY published_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params,
        );

        return [
            'data'     => $data,
            'total'    => $total,
            'pages'    => $total > 0 ? (int) ceil($total / $perPage) : 0,
            'current'  => $page,
            'per_page' => $perPage,
            'q'        => $q,
        ];
    }

    /**
     * @return list<Post>
     */
    public function findPublishedByCategory(int $categoryId): array
    {
        /** @var list<Post> */
        return $this->query(
            "SELECT * FROM {$this->getTable()}
             WHERE category_id = :id AND status = :status
             ORDER BY published_at DESC",
            [':id' => $categoryId, ':status' => Post::STATUS_PUBLISHED],
        );
    }

    /**
     * @return array{data: list<Post>, total: int, pages: int, current: int, per_page: int}
     */
    public function paginatePublishedByCategory(int $categoryId, int $page = 1, int $perPage = 6): array
    {
        $page    = max(1, $page);
        $perPage = max(1, min(50, $perPage));
        $offset  = ($page - 1) * $perPage;

        $params = [
            ':id'     => $categoryId,
            ':status' => Post::STATUS_PUBLISHED,
        ];

        $countStmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM {$this->getTable()} WHERE category_id = :id AND status = :status"
        );
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        /** @var list<Post> $data */
        $data = $this->query(
            "SELECT * FROM {$this->getTable()}
             WHERE category_id = :id AND status = :status
             ORDER BY published_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params,
        );

        return [
            'data'     => $data,
            'total'    => $total,
            'pages'    => $total > 0 ? (int) ceil($total / $perPage) : 0,
            'current'  => $page,
            'per_page' => $perPage,
        ];
    }

    /**
     * @return array{data: list<Post>, total: int, pages: int, current: int, per_page: int}
     */
    public function paginatePublishedByTag(int $tagId, int $page = 1, int $perPage = 6): array
    {
        $page    = max(1, $page);
        $perPage = max(1, min(50, $perPage));
        $offset  = ($page - 1) * $perPage;

        $params = [
            ':tag_id' => $tagId,
            ':status' => Post::STATUS_PUBLISHED,
        ];

        $countStmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM {$this->getTable()} p
             INNER JOIN blog_post_tag pt ON pt.post_id = p.id
             WHERE pt.tag_id = :tag_id AND p.status = :status"
        );
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        /** @var list<Post> $data */
        $data = $this->query(
            "SELECT p.* FROM {$this->getTable()} p
             INNER JOIN blog_post_tag pt ON pt.post_id = p.id
             WHERE pt.tag_id = :tag_id AND p.status = :status
             ORDER BY p.published_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params,
        );

        return [
            'data'     => $data,
            'total'    => $total,
            'pages'    => $total > 0 ? (int) ceil($total / $perPage) : 0,
            'current'  => $page,
            'per_page' => $perPage,
        ];
    }

    public function categoryOf(Post $post): ?Category
    {
        if ($post->category_id === null || $post->category_id <= 0) {
            return null;
        }

        /** @var Category|null */
        return $this->belongsTo(
            relatedClass: Category::class,
            table: 'blog_categories',
            foreignId: $post->category_id,
        );
    }

    /**
     * @return list<Tag>
     */
    public function tagsOf(Post $post): array
    {
        if ($post->id <= 0) {
            return [];
        }

        $stmt = $this->pdo->prepare(
            'SELECT t.* FROM blog_tags t
             INNER JOIN blog_post_tag pt ON pt.tag_id = t.id
             WHERE pt.post_id = :post_id
             ORDER BY t.name ASC'
        );
        $stmt->execute([':post_id' => $post->id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Tag::class);

        /** @var list<Tag> */
        return $stmt->fetchAll();
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

    public function uniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = $base;
        $i    = 2;
        while ($this->slugExists($slug, $exceptId)) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
