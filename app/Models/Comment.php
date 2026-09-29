<?php

declare(strict_types=1);

namespace App\Models;

final class Comment
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public int $id = 0;
    public int $post_id = 0;
    public string $author = '';
    public string $email = '';
    public string $body = '';
    public string $status = self::STATUS_PENDING;
    public string $created_at = '';

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPersisted(): bool
    {
        return $this->id > 0;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'post_id'    => $this->post_id,
            'author'     => $this->author,
            'email'      => $this->email,
            'body'       => $this->body,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
