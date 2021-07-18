<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view->pageTitle = 'Blog';
        $user = new User();
        $posts = (new Post())->all();
        $authors = [];

        foreach ($posts as $post) {
            if (empty($authors[$post->author_id])) {
                $authors[$post->author_id] = $user->first($post->author_id);
            }

            $post->author = $authors[$post->author_id];
        }

        $this->view->posts = $posts;

        $this->render('home/index');
    }
}