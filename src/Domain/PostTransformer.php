<?php

namespace CMS\Domain;

use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'id'    => (int) $post->getId(),
            'title' => $post->getTitle(),
            'body'  => $post->getBody(),
            'path'  => $post->getPath()
        ];
    }
}
