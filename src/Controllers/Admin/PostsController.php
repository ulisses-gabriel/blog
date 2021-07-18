<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class PostsController extends Controller
{
    public function index(): void
    {
        $posts = (new Post())->paginate();
        $authors = [];

        /**
         * Create later feature to fetch model relations and save db connections
         */
        foreach ($posts->getItems() as $post) {
            if (empty($authors[$post->author_id])) {
                $authors[$post->author_id] = (new User())->first((int)$post->author_id);
            }

            $post->author = $authors[$post->author_id];
        }

        $this->view->posts = $posts;

        $this->render('admin/posts/index');

        clearMessage();
        clearErrors();
    }

    public function create(): void
    {
        $this->view->pageTitle = 'Create new post';

        $this->render('/admin/posts/create');

        clearErrors();
        clearMessage();
    }

    public function store(): void
    {
        $data = $_POST;
        $errors = $this->validateData($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;

            $this->redirectTo('/admin/posts/new');

            return;
        }

        $post = new Post();

        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->author_id = loggedUser()->id;

        $post->save();

        $_SESSION['message'] = 'Post created with success!';

        $this->redirectTo('/admin/posts/new');
    }

    public function edit(array $params): void
    {
        $slug = $params[1];
        $post = (new Post())
            ->findOneBy([['slug', $slug]]);

        if (!$post) {
            $this->redirectBack();
        }

        $this->view->post = $post;
        $this->render('admin/posts/edit');

        clearErrors();
        clearMessage();
    }

    public function update(array $params): void
    {
        $data = $_POST;
        $slug = $params[1];
        $errors = $this->validateData($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;

            $this->redirectBack();

            return;
        }

        $post = (new Post())
            ->findOneBy([['slug', $slug]]);
        $post->title = $data['title'];
        $post->content = $data['content'];

        $post->save();

        $_SESSION['message'] = 'Post updated with success!';

        $this->redirectTo('/admin/posts/' . $post->slug);
    }

    public function delete(array $params): void
    {
        $slug = $params[1];
        $post = (new Post())
            ->findOneBy([['slug', $slug]]);

        if ($post) {
            $post->delete();
            $_SESSION['message'] = 'Post deleted with success!';
        } else {
            $_SESSION['errors']['post'] = 'Post not found.';
        }

        $this->redirectBack();
    }

    private function validateData(array $data): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }

        if (empty($data['content'])) {
            $errors['content'] = 'Content is require';
        }

        return $errors;
    }
}