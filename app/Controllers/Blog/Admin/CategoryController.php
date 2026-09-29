<?php

declare(strict_types=1);

namespace App\Controllers\Blog\Admin;

use App\Dao\CategoryDao;
use App\Models\Category;
use App\Support\Slug;
use Controller\AbstractController;
use Core\Exception\NotFoundException;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

final class CategoryController extends AbstractController
{
    use AdminLayoutTrait;

    public function __construct(
        View $view,
        private Request $request,
        private Session $session,
        private CategoryDao $categories,
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        return $this->render('blog/admin/categories/index', $this->adminData([
            'title'      => 'Admin — Catégories',
            'categories' => $this->categories->findAll('name', 'ASC'),
        ]));
    }

    public function create(): Response
    {
        return $this->render('blog/admin/categories/form', $this->adminData([
            'title'    => 'Nouvelle catégorie',
            'category' => new Category(),
            'errors'   => [],
            'old'      => [],
        ]));
    }

    public function store(): Response
    {
        $data = $this->request->post();
        $v    = $this->validate($data, [
            'name' => 'required|max:120',
        ]);

        if ($v->fails()) {
            return $this->render('blog/admin/categories/form', $this->adminData([
                'title'    => 'Nouvelle catégorie',
                'category' => new Category(),
                'errors'   => $v->errors(),
                'old'      => $data,
            ]));
        }

        $name   = trim((string) ($data['name'] ?? ''));
        $slugIn = trim((string) ($data['slug'] ?? ''));
        $slug   = $slugIn !== '' ? Slug::make($slugIn) : Slug::make($name);

        if ($this->categories->slugExists($slug)) {
            $slug .= '-' . time();
        }

        $this->categories->insert([
            'name'       => $name,
            'slug'       => $slug,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->flash('success', 'Catégorie créée.');

        return $this->redirect('/admin/blog/categories');
    }

    public function edit(string $id): Response
    {
        $category = $this->categories->findById((int) $id);
        if (!$category instanceof Category) {
            throw new NotFoundException("Catégorie #{$id} introuvable.");
        }

        return $this->render('blog/admin/categories/form', $this->adminData([
            'title'    => 'Modifier la catégorie',
            'category' => $category,
            'errors'   => [],
            'old'      => [],
        ]));
    }

    public function update(string $id): Response
    {
        $idInt = (int) $id;
        $existing = $this->categories->findById($idInt);
        if (!$existing instanceof Category) {
            throw new NotFoundException("Catégorie #{$id} introuvable.");
        }

        $data = $this->request->post();
        $v    = $this->validate($data, [
            'name' => 'required|max:120',
        ]);

        if ($v->fails()) {
            return $this->render('blog/admin/categories/form', $this->adminData([
                'title'    => 'Modifier la catégorie',
                'category' => $existing,
                'errors'   => $v->errors(),
                'old'      => $data,
            ]));
        }

        $name   = trim((string) ($data['name'] ?? ''));
        $slugIn = trim((string) ($data['slug'] ?? ''));
        $slug   = $slugIn !== '' ? Slug::make($slugIn) : Slug::make($name);

        if ($this->categories->slugExists($slug, $idInt)) {
            $slug .= '-' . $idInt;
        }

        $this->categories->update($idInt, [
            'name' => $name,
            'slug' => $slug,
        ]);

        $this->session->flash('success', 'Catégorie mise à jour.');

        return $this->redirect('/admin/blog/categories');
    }

    public function destroy(string $id): Response
    {
        $this->categories->delete((int) $id);
        $this->session->flash('success', 'Catégorie supprimée.');

        return $this->redirect('/admin/blog/categories');
    }
}
