<?php

namespace CMSTests;

use CMS\Domain\Post;
use Faker\Factory as Faker;

class PostBuilder
{
    private $fake;
    private $times;

    public function __construct()
    {
        $this->fake  = Faker::create();
        $this->times = 1;
    }

    private function newInstance()
    {
        $post = new Post();
        $post->setTitle($this->fake->sentence);
        $post->setBody($this->fake->paragraph);
        $post->setPath($this->fake->slug);

        return $post;
    }

    public function make()
    {
        $posts = [];
        while ($this->times--) {
            $posts[] = $this->newInstance();
        }

        if (count($posts) == 1) {
            $posts = $posts[0];
        }

        return $posts;
    }

    public function times($number)
    {
        $this->times = $number;

        return $this;
    }
}
