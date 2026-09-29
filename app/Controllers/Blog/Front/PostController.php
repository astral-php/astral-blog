<?php

declare(strict_types=1);

namespace App\Controllers\Blog\Front;

use App\Dao\CategoryDao;
use App\Dao\CommentDao;
use App\Dao\PostDao;
use App\Dao\TagDao;
use Controller\AbstractController;
use Core\Exception\NotFoundException;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

final class PostController extends AbstractController
{
    private const PER_PAGE = 6;

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
        $q    = trim((string) $this->request->query('q', ''));
        $page = max(1, (int) $this->request->query('page', 1));
        $result = $this->posts->paginatePublished($page, self::PER_PAGE, $q);

        return $this->render('blog/front/index', $this->frontData([
            'title'      => $q !== '' ? 'Recherche : ' . $q : 'Blog',
            'posts'      => $result['data'],
            'pagination' => $result,
            'q'          => $q,
            'categories' => $this->categories->findAll('name', 'ASC'),
            'tags'       => $this->tags->findAll('name', 'ASC'),
            'postTags'   => $this->mapTagsForPosts($result['data']),
        ]));
    }

    public function show(string $slug): Response
    {
        $post = $this->posts->findPublishedBySlug($slug);
        if ($post === null) {
            throw new NotFoundException("Article introuvable : {$slug}");
        }

        $commentErrors = $this->session->hasFlash('comment_errors')
            ? (array) $this->session->getFlash('comment_errors', [])
            : [];
        $commentOld = $this->session->hasFlash('comment_old')
            ? (array) $this->session->getFlash('comment_old', [])
            : [];
        $commentSuccess = $this->session->hasFlash('comment_success')
            ? (string) $this->session->getFlash('comment_success', '')
            : '';
        $commentError = $this->session->hasFlash('comment_error')
            ? (string) $this->session->getFlash('comment_error', '')
            : '';

        return $this->render('blog/front/show', $this->frontData([
            'title'          => $post->title,
            'post'           => $post,
            'category'       => $this->posts->categoryOf($post),
            'postTags'       => $this->posts->tagsOf($post),
            'categories'     => $this->categories->findAll('name', 'ASC'),
            'tags'           => $this->tags->findAll('name', 'ASC'),
            'comments'       => $this->comments->findApprovedByPost($post->id),
            'commentErrors'  => $commentErrors,
            'commentOld'     => $commentOld,
            'commentSuccess' => $commentSuccess,
            'commentError'   => $commentError,
        ]));
    }

    public function category(string $slug): Response
    {
        $category = $this->categories->findBySlug($slug);
        if ($category === null) {
            throw new NotFoundException("Catégorie introuvable : {$slug}");
        }

        $page   = max(1, (int) $this->request->query('page', 1));
        $result = $this->posts->paginatePublishedByCategory($category->id, $page, self::PER_PAGE);

        return $this->render('blog/front/category', $this->frontData([
            'title'      => $category->name,
            'category'   => $category,
            'posts'      => $result['data'],
            'pagination' => $result,
            'categories' => $this->categories->findAll('name', 'ASC'),
            'tags'       => $this->tags->findAll('name', 'ASC'),
            'postTags'   => $this->mapTagsForPosts($result['data']),
        ]));
    }

    public function tag(string $slug): Response
    {
        $tag = $this->tags->findBySlug($slug);
        if ($tag === null) {
            throw new NotFoundException("Tag introuvable : {$slug}");
        }

        $page   = max(1, (int) $this->request->query('page', 1));
        $result = $this->posts->paginatePublishedByTag($tag->id, $page, self::PER_PAGE);

        return $this->render('blog/front/tag', $this->frontData([
            'title'      => '#' . $tag->name,
            'tag'        => $tag,
            'posts'      => $result['data'],
            'pagination' => $result,
            'categories' => $this->categories->findAll('name', 'ASC'),
            'tags'       => $this->tags->findAll('name', 'ASC'),
            'postTags'   => $this->mapTagsForPosts($result['data']),
        ]));
    }

    /**
     * @param list<\App\Models\Post> $posts
     * @return array<int, list<\App\Models\Tag>>
     */
    private function mapTagsForPosts(array $posts): array
    {
        $map = [];
        foreach ($posts as $post) {
            $map[$post->id] = $this->posts->tagsOf($post);
        }

        return $map;
    }

    /** @param array<string, mixed> $data */
    private function frontData(array $data): array
    {
        return $data;
    }
}
