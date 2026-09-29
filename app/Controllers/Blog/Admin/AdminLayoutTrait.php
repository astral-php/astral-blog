<?php

declare(strict_types=1);

namespace App\Controllers\Blog\Admin;

/**
 * Données communes layout admin blog (layout main + sous-nav).
 */
trait AdminLayoutTrait
{
    /** @param array<string, mixed> $data */
    private function adminData(array $data): array
    {
        return array_merge([
            'adminNav' => [
                ['href' => '/admin/blog/posts', 'label' => 'Articles'],
                ['href' => '/admin/blog/categories', 'label' => 'Catégories'],
                ['href' => '/admin/blog/tags', 'label' => 'Tags'],
                ['href' => '/admin/blog/comments', 'label' => 'Commentaires'],
                ['href' => '/blog', 'label' => 'Voir le blog'],
                ['href' => '/admin/users', 'label' => 'Utilisateurs'],
            ],
        ], $data);
    }
}
