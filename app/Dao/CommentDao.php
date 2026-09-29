<?php

declare(strict_types=1);

namespace App\Dao;

use App\Models\Comment;
use Database\AbstractDao;

/**
 * @extends AbstractDao<Comment>
 */
final class CommentDao extends AbstractDao
{
    protected function getTable(): string
    {
        return 'blog_comments';
    }

    protected function getModelClass(): string
    {
        return Comment::class;
    }

    /**
     * @return list<Comment>
     */
    public function findApprovedByPost(int $postId): array
    {
        /** @var list<Comment> */
        return $this->query(
            "SELECT * FROM {$this->getTable()}
             WHERE post_id = :post_id AND status = :status
             ORDER BY created_at ASC",
            [':post_id' => $postId, ':status' => Comment::STATUS_APPROVED],
        );
    }

    /**
     * @return list<Comment>
     */
    public function findByStatus(string $status = Comment::STATUS_PENDING): array
    {
        /** @var list<Comment> */
        return $this->query(
            "SELECT * FROM {$this->getTable()}
             WHERE status = :status
             ORDER BY created_at DESC",
            [':status' => $status],
        );
    }

    /**
     * @return list<Comment>
     */
    public function findAllRecent(int $limit = 100): array
    {
        $limit = max(1, min(500, $limit));

        /** @var list<Comment> */
        return $this->query(
            "SELECT * FROM {$this->getTable()}
             ORDER BY created_at DESC
             LIMIT {$limit}",
        );
    }

    public function setStatus(int $id, string $status): void
    {
        if (!in_array($status, [
            Comment::STATUS_PENDING,
            Comment::STATUS_APPROVED,
            Comment::STATUS_REJECTED,
        ], true)) {
            return;
        }

        $this->update($id, ['status' => $status]);
    }

    public function countPending(): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM {$this->getTable()} WHERE status = :status"
        );
        $stmt->execute([':status' => Comment::STATUS_PENDING]);

        return (int) $stmt->fetchColumn();
    }

    public function deleteByPost(int $postId): void
    {
        $this->execute(
            "DELETE FROM {$this->getTable()} WHERE post_id = :id",
            [':id' => $postId],
        );
    }
}
