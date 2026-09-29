<?php

declare(strict_types=1);

namespace App\Controllers\Blog\Front;

use App\Dao\CommentDao;
use App\Dao\PostDao;
use App\Models\Comment;
use App\Models\Post;
use Controller\AbstractController;
use Core\Exception\NotFoundException;
use Core\Http\Response;
use Core\Request;
use Core\Session;
use Core\View;

/**
 * Dépôt de commentaires publics (modération a posteriori).
 */
final class CommentController extends AbstractController
{
    public function __construct(
        View $view,
        private Request $request,
        private Session $session,
        private PostDao $posts,
        private CommentDao $comments,
    ) {
        parent::__construct($view);
    }

    public function store(string $slug): Response
    {
        $post = $this->posts->findPublishedBySlug($slug);
        if (!$post instanceof Post) {
            throw new NotFoundException("Article introuvable : {$slug}");
        }

        $data = $this->request->post();
        $v    = $this->validate($data, [
            'author' => 'required|min:2|max:120',
            'body'   => 'required|min:3|max:2000',
            'email'  => 'max:180',
        ]);

        // email optionnel mais si fourni doit être valide
        $email = trim((string) ($data['email'] ?? ''));
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors = $v->errors();
            $errors['email'] = ['Adresse e-mail invalide.'];
            $this->session->flash('comment_error', 'Commentaire invalide — vérifiez le formulaire.');
            $this->session->flash('comment_errors', $errors);
            $this->session->flash('comment_old', $data);

            return $this->redirect('/blog/' . $post->slug . '#comments');
        }

        if ($v->fails()) {
            $this->session->flash('comment_error', 'Commentaire invalide — vérifiez le formulaire.');
            $this->session->flash('comment_errors', $v->errors());
            $this->session->flash('comment_old', $data);

            return $this->redirect('/blog/' . $post->slug . '#comments');
        }

        // Honeypot anti-bot simple
        if (trim((string) ($data['website'] ?? '')) !== '') {
            return $this->redirect('/blog/' . $post->slug);
        }

        $this->comments->insert([
            'post_id'    => $post->id,
            'author'     => trim((string) ($data['author'] ?? '')),
            'email'      => $email,
            'body'       => trim((string) ($data['body'] ?? '')),
            'status'     => Comment::STATUS_PENDING,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->flash(
            'comment_success',
            'Merci ! Votre commentaire a bien été enregistré. Il sera publié après validation, dès que possible.',
        );

        return $this->redirect('/blog/' . $post->slug . '#comments');
    }
}
