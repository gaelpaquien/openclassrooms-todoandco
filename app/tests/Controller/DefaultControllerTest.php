<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private $urlGenerator;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function testHomepageIsUp()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_default'));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
