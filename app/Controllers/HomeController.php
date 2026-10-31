<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Dao\PostDao;
use App\Dao\UserDao;
use Controller\AbstractController;
use Core\Http\Response;
use Core\View;

/**
 * Accueil Astral Blog — derniers articles + bootstrap admin.
 */
final class HomeController extends AbstractController
{
    public function __construct(
        View $view,
        private UserDao $userDao,
        private PostDao $postDao,
        private string $version = '2.0.0',
        private string $githubUrl = 'https://github.com/astral-php/astral-blog',
        private string $websiteUrl = 'https://astral-php.dev',
    ) {
        parent::__construct($view);
    }

    public function index(): Response
    {
        $latest = $this->postDao->paginatePublished(1, 3);

        return $this->render('home/index', [
            'title'      => 'Astral Blog',
            'version'    => $this->version,
            'hasUsers'   => $this->userDao->count() > 0,
            'posts'      => $latest['data'],
            'githubUrl'  => $this->githubUrl,
            'websiteUrl' => $this->websiteUrl,
        ]);
    }
}
