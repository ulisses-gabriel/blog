<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;

class PostsController extends Controller
{
    public function show(array $params): void
    {
        $slug = $params[1];
        /** @var Post $post */
        $post = (new Post())->findOneBy([['slug', $slug]]);

        if (!$post) {
            $this->redirectTo('/');
        }

        $post->loadAuthor();

        $this->view->post = $post;

        $this->render('home/post');
    }
}