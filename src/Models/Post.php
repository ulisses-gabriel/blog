<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Model;

class Post extends Model
{
    public function loadAuthor(): void
    {
        $this->author = (new User())->first((int)$this->author_id);
    }

    public function getExcerpt(): string
    {
        if (strlen($this->content) >= 128) {
            return substr($this->content, 0, 128) . '...';
        }

        return $this->content;
    }

    protected function preSave(): void
    {
        $slug = slugify($this->title);
        $tries = 0;

        while ((new Post())->findOneBy($this->getPreSaveCriteria($slug))) {
            $tries++;

            if ($tries > 1) {
                $slug = substr($slug, 0, strlen($slug)-2);
            }

            $slug .= '-' . $tries;
        }

        $this->slug = $slug;
    }

    private function getPreSaveCriteria(string $slug): array
    {
        $criteria = [['slug', $slug]];

        if ($this->id) {
            $criteria[] = ['id', '<>', $this->id];
        }

        return $criteria;
    }
}