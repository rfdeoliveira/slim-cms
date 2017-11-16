<?php

namespace CMSTests;

use Slim\App;
use Slim\Http;
use CMS\Domain\PostRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class ApiTestCase extends TestCase
{
    protected $cms;

    public function setUp()
    {
        parent::setUp();

        $settings = require __DIR__ . '/../core/settings.php';
        $cms      = new App($settings);

        // Importing dependencies
        require __DIR__ . '/../core/dependencies.php';

        $this->cms = $cms;
    }

    /**
     * Process a fake request hitting the app endpoints
     *
     * @param $requestMethod
     * @param $requestUri
     * @param array $requestBody
     * @return \Psr\Http\Message\ResponseInterface|Http\Response
     */
    public function processRequest($requestMethod, $requestUri, $requestBody = [])
    {
        $environment = Http\Environment::mock([
            'REQUEST_METHOD' => $requestMethod,
            'REQUEST_URI'    => $requestUri
        ]);
        $request = Http\Request::createFromEnvironment($environment);

        if (count($requestBody) > 0) {
            $request = $request->withParsedBody($requestBody);
        }

        $cms = $this->cms;

        // Registering routes
        require __DIR__ . '/../core/routes.php';

        $response = new Http\Response();
        $response = $cms->process($request, $response);

        return $response;
    }

    /**
     * Mocks the entity manager methods that will be used by the route callback
     *
     * @param $repositoryClass
     * @param $method
     * @param $results
     */
    private function mockEntityManager($repositoryClass, $method, $results)
    {
        $this->cms->getContainer()['em'] = function () use ($repositoryClass, $method, $results) {
            // Mocking PostRepository
            $repository = $this->createMock($repositoryClass);
            $repository->expects($this->any())
                ->method($method)
                ->willReturn($results);

            // Mocking Doctrine EntityManager
            $em = $this->createMock(EntityManager::class);
            $em->expects($this->any())
                ->method('getRepository')
                ->willReturn($repository);

            return $em;
        };
    }

    /**
     * Mocks a EntityManager for Posts database operations
     *
     * @param $method
     * @param $result
     */
    public function mockPostManager($method, $result)
    {
        $this->mockEntityManager(PostRepository::class, $method, $result);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
