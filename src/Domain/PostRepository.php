<?php

namespace CMS\Domain;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function add(array $input)
    {
        $post = new Post();
        $post->setTitle($input['title']);
        $post->setBody($input['body']);
        $post->setPath($input['path']);

        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();

        return $post;
    }

    public function update(Post $post, array $input)
    {
        // Set new values
        $post->setTitle($input['title']);
        $post->setBody($input['body']);
        $post->setPath($input['path']);

        // Persisting changes to the Post object
        $this->getEntityManager()->flush();

        return $post;
    }
}
