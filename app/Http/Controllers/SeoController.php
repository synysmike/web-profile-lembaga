<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap(): Response
    {
        $posts = Post::query()
            ->visible()
            ->orderByDesc('published_on')
            ->orderByDesc('id')
            ->get(['slug', 'published_on']);

        return response()
            ->view('sitemap', ['posts' => $posts])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
