<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MOLiBot\DataSources\NcdrRss;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NcdrRssDataTest extends TestCase
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

        $mockBody = '{"id":"","title":"","updated":"","author":{"name":""},"link":{"@rel":"self","@href":""},"entry":[{"id":"","title":"","updated":"","author":{"name":""},"link":{"@rel":"","@href":""},"summary":{"@type":"","#text":""},"category":{"@term":""}}]}';

        $mock = new MockHandler([
            new Response(200, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new NcdrRss();

        $dataSource->newHttpClient($testClient);

        $data = $dataSource->getContent();

        $this->assertIsArray($data['entry'], 'entry must be an array');
    }

    /**
     *
     * @return void
     * @throws
     */
    public function testGetNotArrayEntry()
    {
        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '{"id":"","title":"","updated":"","author":{"name":""},"link":{"@rel":"self","@href":""},"entry":{"id":"","title":"","updated":"","author":{"name":""},"link":{"@rel":"","@href":""},"summary":{"@type":"","#text":""},"category":{"@term":""}}}';

        $mock = new MockHandler([
            new Response(200, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new NcdrRss();

        $dataSource->newHttpClient($testClient);

        $data = $dataSource->getContent();

        $this->assertIsArray($data['entry'], 'entry must be an array');
    }

    /**
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

        $dataSource = new NcdrRss();

        $dataSource->newHttpClient($testClient);

        $dataSource->getContent();
    }
}
