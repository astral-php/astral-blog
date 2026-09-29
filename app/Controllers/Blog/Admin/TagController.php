<?php

declare(strict_types=1);

namespace App\Controllers\Blog\Admin;

use App\Dao\TagDao;
use App\Models\Tag;
use App\Support\Slug;
use Controller\AbstractController;
use Core\Exception\NotFoundException;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

final class TagController extends AbstractController
{
    use AdminLayoutTrait;

    public function __construct(
        View $view,
        private Request $request,
        private Session $session,
        private TagDao $tags,
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        return $this->render('blog/admin/tags/index', $this->adminData([
            'title' => 'Admin — Tags',
            'tags'  => $this->tags->findAll('name', 'ASC'),
        ]));
    }

    public function create(): Response
    {
        return $this->render('blog/admin/tags/form', $this->adminData([
            'title'  => 'Nouveau tag',
            'tag'    => new Tag(),
            'errors' => [],
            'old'    => [],
        ]));
    }

    public function store(): Response
    {
        $data = $this->request->post();
        $v    = $this->validate($data, [
            'name' => 'required|max:120',
        ]);

        if ($v->fails()) {
            return $this->render('blog/admin/tags/form', $this->adminData([
                'title'  => 'Nouveau tag',
                'tag'    => new Tag(),
                'errors' => $v->errors(),
                'old'    => $data,
            ]));
        }

        $name   = trim((string) ($data['name'] ?? ''));
        $slugIn = trim((string) ($data['slug'] ?? ''));
        $slug   = $slugIn !== '' ? Slug::make($slugIn) : Slug::make($name);

        if ($this->tags->slugExists($slug)) {
            $slug .= '-' . time();
        }

        $this->tags->insert([
            'name'       => $name,
            'slug'       => $slug,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->flash('success', 'Tag créé.');

        return $this->redirect('/admin/blog/tags');
    }

    public function edit(string $id): Response
    {
        $tag = $this->tags->findById((int) $id);
        if (!$tag instanceof Tag) {
            throw new NotFoundException("Tag #{$id} introuvable.");
        }

        return $this->render('blog/admin/tags/form', $this->adminData([
            'title'  => 'Modifier le tag',
            'tag'    => $tag,
            'errors' => [],
            'old'    => [],
        ]));
    }

    public function update(string $id): Response
    {
        $idInt = (int) $id;
        $existing = $this->tags->findById($idInt);
        if (!$existing instanceof Tag) {
            throw new NotFoundException("Tag #{$id} introuvable.");
        }

        $data = $this->request->post();
        $v    = $this->validate($data, [
            'name' => 'required|max:120',
        ]);

        if ($v->fails()) {
            return $this->render('blog/admin/tags/form', $this->adminData([
                'title'  => 'Modifier le tag',
                'tag'    => $existing,
                'errors' => $v->errors(),
                'old'    => $data,
            ]));
        }

        $name   = trim((string) ($data['name'] ?? ''));
        $slugIn = trim((string) ($data['slug'] ?? ''));
        $slug   = $slugIn !== '' ? Slug::make($slugIn) : Slug::make($name);

        if ($this->tags->slugExists($slug, $idInt)) {
            $slug .= '-' . $idInt;
        }

        $this->tags->update($idInt, [
            'name' => $name,
            'slug' => $slug,
        ]);

        $this->session->flash('success', 'Tag mis à jour.');

        return $this->redirect('/admin/blog/tags');
    }

    public function destroy(string $id): Response
    {
        $idInt = (int) $id;
        $this->tags->detachFromAllPosts($idInt);
        $this->tags->delete($idInt);
        $this->session->flash('success', 'Tag supprimé.');

        return $this->redirect('/admin/blog/tags');
    }
}
