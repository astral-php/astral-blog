<?php

declare(strict_types=1);

namespace App\Models;

final class Category
{
    public int $id = 0;
    public string $name = '';
    public string $slug = '';
    public string $created_at = '';

    public function isPersisted(): bool
    {
        return $this->id > 0;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'slug'       => $this->slug,
            'created_at' => $this->created_at,
        ];
    }
}
