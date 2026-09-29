<?php

declare(strict_types=1);

namespace App\Controllers\Blog\Admin;

use App\Dao\CommentDao;
use App\Dao\PostDao;
use App\Models\Comment;
use Controller\AbstractController;
use Core\Exception\NotFoundException;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

final class CommentController extends AbstractController
{
    use AdminLayoutTrait;

    public function __construct(
        View $view,
        private Request $request,
        private Session $session,
        private CommentDao $comments,
        private PostDao $posts,
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        $filter = (string) $this->request->query('status', 'pending');
        if (!in_array($filter, ['pending', 'approved', 'rejected', 'all'], true)) {
            $filter = 'pending';
        }

        $list = $filter === 'all'
            ? $this->comments->findAllRecent()
            : $this->comments->findByStatus($filter);

        $postTitles = [];
        foreach ($list as $comment) {
            if (!isset($postTitles[$comment->post_id])) {
                $post = $this->posts->findById($comment->post_id);
                $postTitles[$comment->post_id] = $post?->title ?? ('#' . $comment->post_id);
            }
        }

        return $this->render('blog/admin/comments/index', $this->adminData([
            'title'       => 'Admin — Commentaires',
            'comments'    => $list,
            'filter'      => $filter,
            'postTitles'  => $postTitles,
            'pendingCount'=> $this->comments->countPending(),
        ]));
    }

    public function approve(string $id): Response
    {
        $this->assertExists($id);
        $this->comments->setStatus((int) $id, Comment::STATUS_APPROVED);
        $this->session->flash('success', 'Commentaire approuvé.');

        return $this->redirect('/admin/blog/comments?status=pending');
    }

    public function reject(string $id): Response
    {
        $this->assertExists($id);
        $this->comments->setStatus((int) $id, Comment::STATUS_REJECTED);
        $this->session->flash('success', 'Commentaire rejeté.');

        return $this->redirect('/admin/blog/comments?status=pending');
    }

    public function destroy(string $id): Response
    {
        $this->assertExists($id);
        $this->comments->delete((int) $id);
        $this->session->flash('success', 'Commentaire supprimé.');

        return $this->redirect('/admin/blog/comments?status=pending');
    }

    private function assertExists(string $id): void
    {
        $comment = $this->comments->findById((int) $id);
        if (!$comment instanceof Comment) {
            throw new NotFoundException("Commentaire #{$id} introuvable.");
        }
    }
}
