<?php

namespace CMSTests;

class PostsTest extends ApiTestCase
{
    public function testMustReturnOnePostIfItExists()
    {
        $post = (new PostBuilder())->times(1)->make();

        $this->mockPostManager('find', $post);

        $response = $this->processRequest('GET', '/posts/1');

        $this->assertTrue($response->isOk());
        $this->assertContains($post->getTitle(), (string) $response->getBody());
    }

    public function testMustReturn404IfPostDoesNotExist()
    {
        $this->mockPostManager('find', null);

        $response = $this->processRequest('GET', '/posts/1');

        $this->assertTrue($response->isNotFound());
    }

    public function testMustReturnAListOfPosts()
    {
        $posts = (new PostBuilder())->times(5)->make();

        $this->mockPostManager('findAll', $posts);

        $response = $this->processRequest('GET', '/posts');
        $responseBody = json_decode($response->getBody());

        $this->assertTrue($response->isOk());
        $this->assertCount(5, $responseBody->data);
    }

    public function testMustReturn404ThereAreNoPosts()
    {
        $this->mockPostManager('findAll', []);

        $response = $this->processRequest('GET', '/posts');

        $this->assertTrue($response->isNotFound());
    }

    public function testMustCreatePostWithValidData()
    {
        $post  = (new PostBuilder)->times(1)->make();
        $input = [
            'title' => $post->getTitle(),
            'body'  => $post->getBody(),
            'path'  => $post->getPath(),
        ];

        $this->mockPostManager('add', $post);

        $response = $this->processRequest('POST', '/posts', $input);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertContains($post->getTitle(), (string) $response->getBody());
    }

    public function testMustReturn400IfInputFailsValidationOnPostCreation()
    {
        $this->mockPostManager('add', null);

        $response = $this->processRequest('POST', '/posts', []);

        $this->assertSame(400, $response->getStatusCode());
    }
}
