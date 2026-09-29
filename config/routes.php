<?php

declare(strict_types=1);

use App\Controllers\Admin\UserController as AdminUserController;
use App\Controllers\AuthController;
use App\Controllers\Blog\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Blog\Admin\CommentController as AdminCommentController;
use App\Controllers\Blog\Admin\PostController as AdminPostController;
use App\Controllers\Blog\Admin\TagController as AdminTagController;
use App\Controllers\Blog\Front\CommentController as FrontCommentController;
use App\Controllers\Blog\Front\PostController as FrontPostController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\UserController;
use Core\Auth\Middleware\AdminMiddleware;
use Core\Auth\Middleware\AuthMiddleware;
use Core\Auth\Middleware\GuestMiddleware;
use Core\Router;

return function (Router $router): void {

    $router->get('/', HomeController::class, 'index');

    $router->get('/blog', FrontPostController::class, 'index');
    $router->get('/blog/category/:slug', FrontPostController::class, 'category');
    $router->get('/blog/tag/:slug', FrontPostController::class, 'tag');
    $router->post('/blog/:slug/comments', FrontCommentController::class, 'store');
    $router->get('/blog/:slug', FrontPostController::class, 'show');

    $router->get('/login',     AuthController::class, 'loginForm')->middleware(GuestMiddleware::class);
    $router->post('/login',    AuthController::class, 'login')->middleware(GuestMiddleware::class);
    $router->post('/logout',   AuthController::class, 'logout');

    $router->get('/register',  AuthController::class, 'registerForm')->middleware(GuestMiddleware::class);
    $router->post('/register', AuthController::class, 'register')->middleware(GuestMiddleware::class);

    $router->get('/verify-pending', AuthController::class, 'verifyPending');
    $router->get('/verify-email',   AuthController::class, 'verifyEmail');

    $router->get('/forgot-password',  AuthController::class, 'forgotPasswordForm')->middleware(GuestMiddleware::class);
    $router->post('/forgot-password', AuthController::class, 'forgotPassword')->middleware(GuestMiddleware::class);
    $router->get('/reset-password',   AuthController::class, 'resetPasswordForm')->middleware(GuestMiddleware::class);
    $router->post('/reset-password',  AuthController::class, 'resetPassword')->middleware(GuestMiddleware::class);

    $router->group('', function (Router $r): void {
        $r->get('/profile',           ProfileController::class, 'editForm');
        $r->post('/profile',          ProfileController::class, 'update');
        $r->post('/profile/password', ProfileController::class, 'updatePassword');
    }, [AuthMiddleware::class]);

    $router->group('', function (Router $r): void {
        $r->get('/users',     UserController::class, 'index');
        $r->get('/users/:id', UserController::class, 'show');
    }, [AuthMiddleware::class]);

    $router->group('', function (Router $r): void {
        $r->get('/users/create',      UserController::class, 'create');
        $r->post('/users',            UserController::class, 'store');
        $r->post('/users/:id/delete', UserController::class, 'destroy');
    }, [AdminMiddleware::class]);

    $router->group('/admin', function (Router $r): void {
        $r->get('/users',           AdminUserController::class, 'index');
        $r->post('/users/:id/role', AdminUserController::class, 'updateRole');
    }, [AdminMiddleware::class]);

    $router->group('/admin/blog', static function (Router $r): void {
        $r->get('/posts', AdminPostController::class, 'index');
        $r->get('/posts/create', AdminPostController::class, 'create');
        $r->post('/posts', AdminPostController::class, 'store');
        $r->get('/posts/:id/edit', AdminPostController::class, 'edit');
        $r->post('/posts/:id', AdminPostController::class, 'update');
        $r->post('/posts/:id/delete', AdminPostController::class, 'destroy');

        $r->get('/categories', AdminCategoryController::class, 'index');
        $r->get('/categories/create', AdminCategoryController::class, 'create');
        $r->post('/categories', AdminCategoryController::class, 'store');
        $r->get('/categories/:id/edit', AdminCategoryController::class, 'edit');
        $r->post('/categories/:id', AdminCategoryController::class, 'update');
        $r->post('/categories/:id/delete', AdminCategoryController::class, 'destroy');

        $r->get('/tags', AdminTagController::class, 'index');
        $r->get('/tags/create', AdminTagController::class, 'create');
        $r->post('/tags', AdminTagController::class, 'store');
        $r->get('/tags/:id/edit', AdminTagController::class, 'edit');
        $r->post('/tags/:id', AdminTagController::class, 'update');
        $r->post('/tags/:id/delete', AdminTagController::class, 'destroy');

        $r->get('/comments', AdminCommentController::class, 'index');
        $r->post('/comments/:id/approve', AdminCommentController::class, 'approve');
        $r->post('/comments/:id/reject', AdminCommentController::class, 'reject');
        $r->post('/comments/:id/delete', AdminCommentController::class, 'destroy');
    }, [AdminMiddleware::class]);
};
