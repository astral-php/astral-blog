<?php

declare(strict_types=1);

namespace App\Models;

final class Post
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public int $id = 0;
    public ?int $category_id = null;
    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $body = '';
    public string $status = self::STATUS_DRAFT;
    public ?string $published_at = null;
    public string $created_at = '';
    public string $updated_at = '';

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPersisted(): bool
    {
        return $this->id > 0;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'category_id'  => $this->category_id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'excerpt'      => $this->excerpt,
            'body'         => $this->body,
            'status'       => $this->status,
            'published_at' => $this->published_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
