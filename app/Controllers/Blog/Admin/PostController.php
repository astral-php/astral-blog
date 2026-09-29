<?php

declare(strict_types=1);

namespace App\Controllers\Blog\Admin;

use App\Dao\CategoryDao;
use App\Dao\CommentDao;
use App\Dao\PostDao;
use App\Dao\TagDao;
use App\Models\Post;
use App\Support\Slug;
use Controller\AbstractController;
use Core\Exception\NotFoundException;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

final class PostController extends AbstractController
{
    use AdminLayoutTrait;

    public function __construct(
        View $view,
        private Request $request,
        private Session $session,
        private PostDao $posts,
        private CategoryDao $categories,
        private TagDao $tags,
        private CommentDao $comments,
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        return $this->render('blog/admin/posts/index', $this->adminData([
            'title' => 'Admin — Articles',
            'posts' => $this->posts->findAll('updated_at', 'DESC'),
        ]));
    }

    public function create(): Response
    {
        return $this->render('blog/admin/posts/form', $this->adminData([
            'title'         => 'Nouvel article',
            'post'          => new Post(),
            'categories'    => $this->categories->findAll('name', 'ASC'),
            'allTags'       => $this->tags->findAll('name', 'ASC'),
            'selectedTags'  => [],
            'errors'        => [],
            'old'           => [],
        ]));
    }

    public function store(): Response
    {
        $post = $this->request->post();
        $v    = $this->validate($post, [
            'title'  => 'required|max:255',
            'body'   => 'required',
            'status' => 'required',
        ]);

        $selected = $this->normalizeTagIds($post['tag_ids'] ?? []);

        if ($v->fails()) {
            return $this->render('blog/admin/posts/form', $this->adminData([
                'title'        => 'Nouvel article',
                'post'         => new Post(),
                'categories'   => $this->categories->findAll('name', 'ASC'),
                'allTags'      => $this->tags->findAll('name', 'ASC'),
                'selectedTags' => $selected,
                'errors'       => $v->errors(),
                'old'          => $post,
            ]));
        }

        $title  = trim((string) ($post['title'] ?? ''));
        $slugIn = trim((string) ($post['slug'] ?? ''));
        $base   = $slugIn !== '' ? Slug::make($slugIn) : Slug::make($title);
        $status = (string) ($post['status'] ?? Post::STATUS_DRAFT);
        if (!in_array($status, [Post::STATUS_DRAFT, Post::STATUS_PUBLISHED], true)) {
            $status = Post::STATUS_DRAFT;
        }

        $categoryId = (int) ($post['category_id'] ?? 0);
        $now        = date('Y-m-d H:i:s');

        $id = $this->posts->insert([
            'category_id'  => $categoryId > 0 ? $categoryId : null,
            'title'        => $title,
            'slug'         => $this->posts->uniqueSlug($base),
            'excerpt'      => trim((string) ($post['excerpt'] ?? '')),
            'body'         => (string) ($post['body'] ?? ''),
            'status'       => $status,
            'published_at' => $status === Post::STATUS_PUBLISHED ? $now : null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        $this->tags->syncForPost($id, $selected);
        $this->session->flash('success', 'Article créé.');

        return $this->redirect('/admin/blog/posts');
    }

    public function edit(string $id): Response
    {
        $post = $this->posts->findById((int) $id);
        if (!$post instanceof Post) {
            throw new NotFoundException("Article #{$id} introuvable.");
        }

        $selected = array_map(
            static fn ($t) => $t->id,
            $this->posts->tagsOf($post),
        );

        return $this->render('blog/admin/posts/form', $this->adminData([
            'title'        => 'Modifier l’article',
            'post'         => $post,
            'categories'   => $this->categories->findAll('name', 'ASC'),
            'allTags'      => $this->tags->findAll('name', 'ASC'),
            'selectedTags' => $selected,
            'errors'       => [],
            'old'          => [],
        ]));
    }

    public function update(string $id): Response
    {
        $idInt = (int) $id;
        $existing = $this->posts->findById($idInt);
        if (!$existing instanceof Post) {
            throw new NotFoundException("Article #{$id} introuvable.");
        }

        $post = $this->request->post();
        $v    = $this->validate($post, [
            'title'  => 'required|max:255',
            'body'   => 'required',
            'status' => 'required',
        ]);

        $selected = $this->normalizeTagIds($post['tag_ids'] ?? []);

        if ($v->fails()) {
            return $this->render('blog/admin/posts/form', $this->adminData([
                'title'        => 'Modifier l’article',
                'post'         => $existing,
                'categories'   => $this->categories->findAll('name', 'ASC'),
                'allTags'      => $this->tags->findAll('name', 'ASC'),
                'selectedTags' => $selected,
                'errors'       => $v->errors(),
                'old'          => $post,
            ]));
        }

        $title  = trim((string) ($post['title'] ?? ''));
        $slugIn = trim((string) ($post['slug'] ?? ''));
        $base   = $slugIn !== '' ? Slug::make($slugIn) : Slug::make($title);
        $status = (string) ($post['status'] ?? Post::STATUS_DRAFT);
        if (!in_array($status, [Post::STATUS_DRAFT, Post::STATUS_PUBLISHED], true)) {
            $status = Post::STATUS_DRAFT;
        }

        $categoryId = (int) ($post['category_id'] ?? 0);
        $now        = date('Y-m-d H:i:s');
        $published  = $existing->published_at;
        if ($status === Post::STATUS_PUBLISHED && ($published === null || $published === '')) {
            $published = $now;
        }
        if ($status === Post::STATUS_DRAFT) {
            $published = null;
        }

        $this->posts->update($idInt, [
            'category_id'  => $categoryId > 0 ? $categoryId : null,
            'title'        => $title,
            'slug'         => $this->posts->uniqueSlug($base, $idInt),
            'excerpt'      => trim((string) ($post['excerpt'] ?? '')),
            'body'         => (string) ($post['body'] ?? ''),
            'status'       => $status,
            'published_at' => $published,
            'updated_at'   => $now,
        ]);

        $this->tags->syncForPost($idInt, $selected);
        $this->session->flash('success', 'Article mis à jour.');

        return $this->redirect('/admin/blog/posts');
    }

    public function destroy(string $id): Response
    {
        $idInt = (int) $id;
        $this->tags->syncForPost($idInt, []);
        $this->comments->deleteByPost($idInt);
        $this->posts->delete($idInt);
        $this->session->flash('success', 'Article supprimé.');

        return $this->redirect('/admin/blog/posts');
    }

    /**
     * @param mixed $raw
     * @return list<int>
     */
    private function normalizeTagIds(mixed $raw): array
    {
        if (!is_array($raw)) {
            return [];
        }

        $ids = [];
        foreach ($raw as $value) {
            $id = (int) $value;
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }
}
