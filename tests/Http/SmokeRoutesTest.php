<?php

declare(strict_types=1);

namespace Tests\Http;

/** Smokes HTTP astral-blog — accueil, blog public, auth. */
final class SmokeRoutesTest extends HttpSmokeTestCase
{
    public function testHomeReturns200(): void
    {
        $response = $this->get('/');
        $this->assertSame(200, $response->getStatus());
    }

    public function testBlogIndexReturns200(): void
    {
        $response = $this->get('/blog');
        $this->assertSame(200, $response->getStatus());
    }

    public function testLoginFormReturns200(): void
    {
        $response = $this->get('/login');
        $this->assertSame(200, $response->getStatus());
    }

    public function testProfileWithoutAuthRedirectsToLogin(): void
    {
        $response = $this->get('/profile');
        $this->assertSame(302, $response->getStatus());
    }

    public function testAdminBlogWithoutAuthRedirects(): void
    {
        $response = $this->get('/admin/blog/posts');
        $this->assertSame(302, $response->getStatus());
    }
}
