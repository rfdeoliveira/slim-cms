<?php

use CMS\Domain\Post;
use League\Fractal\Resource;
use CMS\Domain\PostTransformer;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;

// Returns Post By its path
$cms->get('/blog/{path}', function (Request $req, Response $res, $args) {
    $path = $args['path'];
    $post = $this->em->getRepository(Post::class)->findOneBy(compact('path'));
    if (null === $post) {
        return $res->withJson('Page not found!= (', 404);
    }

    // Transforming posts collections for sending to the client
    $resource = new Resource\Item($post, new PostTransformer);
    $post     = $this->fractal->createData($resource)->toArray();

    return $res->withJson($post);
});

// Retrieves post collection
$cms->get('/posts', function (Request $req, Response $res) {
    $posts = $this->em->getRepository(Post::class)->findAll();
    if (count($posts) == 0) {
        return $res->withJson('No posts found', 404);
    }

    // Transforming posts collections for sending to the client
    $resource = new Resource\Collection($posts, new PostTransformer);
    $posts    = $this->fractal->createData($resource)->toArray();

    return $res->withJson($posts);
});

// Retrieves one post by 'id'
$cms->get('/posts/{id}', function (Request $req, Response $res, $args = []) {
    $id   = (int) $args['id'];
    $post = $this->em->getRepository(Post::class)->find($id);
    if (null === $post) {
        return $res->withJson(['Post not found.'], 404);
    }

    // Transforming post object for sending to the client
    $resource = new Resource\Item($post, new PostTransformer);
    $post     = $this->fractal->createData($resource)->toArray();

    return $res->withJson($post);
});

// Creates post
$cms->post('/posts', function (Request $req, Response $res) {
    // Validating input
    $input = $req->getParsedBody();

    $errors = [];
    foreach ($this->validationRules as $field => $rule) {
        try {
            $rule->assert($input[$field]);
        } catch(NestedValidationException $exception) {
            $errors[$field] = $exception->findMessages(['notEmpty', 'slug']);
        }
    }

    if (count($errors) > 0) {
        // Input failed validation, it's a Bad Request =)
        return $res->withJson($errors, 400);
    }

    // Persisting the new Post
    $post = $this->em->getRepository(Post::class)->add($input);

    // Transforming the newly created post for sending back to the client
    $resource = new Resource\Item($post, new PostTransformer);
    $newPost  = $this->fractal->createData($resource)->toArray();

    return $res->withJson($newPost, 201);
});

// Updates post
$cms->put('/posts/{id}', function (Request $req, Response $res, $args = []) {
    // Find post
    $id = (int) $args['id'];
    $post = $this->em->find(Post::class, $id);
    if (null === $post) {
        return $res->withJson(['Post not found.'], 404);
    }

    // Validate input
    $input  = $req->getParsedBody();
    $errors = [];
    foreach ($this->validationRules as $field => $rule) {
        try {
            $rule->assert($input[$field]);
        } catch(NestedValidationException $exception) {
            $errors[$field] = $exception->findMessages(['alnum', 'notEmpty', 'slug']);
        }
    }

    if (count($errors) > 0) {
        return $res->withJson($errors, 400);
    }

    $post = $this->em->getRepository(Post::class)->update($post, $input);

    // Transforming the newly created post for sending back to the client
    $resource = new Resource\Item($post, new PostTransformer);
    $post     = $this->fractal->createData($resource)->toArray();

    return $res->withJson($post);
});

// Removes post
$cms->delete('/posts/{id}', function (Request $req, Response $res, $args = []) {
    // Finding the post
    $post = $this->em->find(Post::class, (int) $args['id']);
    if (null === $post) {
        return $res->withJson('Post not found.', 404);
    }

    // Destroying the post
    $this->em->remove($post);
    $this->em->flush();

    // it returns 204 because operation was successful
    // and there is no additional content to send
    return $res->withStatus(204);
});
