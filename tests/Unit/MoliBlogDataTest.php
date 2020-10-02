<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MOLiBot\DataSources\MoliBlogArticle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoliBlogDataTest extends TestCase
{
    /**
     * Test for get content.
     *
     * @return void
     * @throws
     */
    public function testGetContent()
    {
        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '{"posts":[{}],"meta":{"pagination":{"page":1,"limit":15,"pages":2,"total":19,"next":2,"prev":null}}}';

        $mock = new MockHandler([
            new Response(200, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliBlogArticle();

        $dataSource->newHttpClient($testClient);

        $data = $dataSource->getContent();

        $this->assertIsArray($data['posts'], 'posts must be an array');
    }

    /**
     * Test for get content with custom page.
     *
     * @return void
     * @throws
     */
    public function testGetPageContent()
    {
        $page = rand(2, 999);

        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '{"posts":[{}],"meta":{"pagination":{"page":'. $page .',"limit":15,"pages":2,"total":19,"next":2,"prev":null}}}';

        $mock = new MockHandler([
            new Response(200, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliBlogArticle();

        $dataSource->newHttpClient($testClient);

        $dataSource->setPage($page);

        $data = $dataSource->getContent();

        $this->assertIsArray($data['posts'], 'posts must be an array');

        $this->assertTrue($data['meta']['pagination']['page'] === $page);
    }

    /**
     * Test for get content error.
     *
     * @return void
     * @throws
     */
    public function testGetErrorResponse()
    {
        $this->expectException(\MOLiBot\Exceptions\DataSourceRetrieveException::class);

        $this->expectExceptionCode(502);

        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliBlogArticle();

        $dataSource->newHttpClient($testClient);

        $dataSource->getContent();
    }
}
