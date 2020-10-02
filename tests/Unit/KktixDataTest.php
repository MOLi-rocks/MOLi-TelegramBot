<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MOLiBot\DataSources\MoliKktix;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KktixDataTest extends TestCase
{
    /**
     * Test for get content.
     *
     * @return void
     * @throws
     */
    public function testMoliGetContent()
    {
        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '{"entry":[{"url":"","published":"","title":"","summary":"","content":"","author":{"name":"","uri":""}}]}';

        $mock = new MockHandler([
            new Response(200, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliKktix();

        $dataSource->newHttpClient($testClient);

        $data = $dataSource->getContent();

        $this->assertIsArray($data['entry'], 'entry must be an array');
    }

    /**
     *
     * @return void
     * @throws
     */
    public function testMoliGetObjectContent()
    {
        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '{"entry":{"url":"","published":"","title":"","summary":"","content":"","author":{"name":"","uri":""}}}';

        $mock = new MockHandler([
            new Response(200, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliKktix();

        $dataSource->newHttpClient($testClient);

        $data = $dataSource->getContent();

        $this->assertIsArray($data['entry'], 'entry must be an array');
    }

    /**
     *
     * @return void
     * @throws
     */
    public function testMoliGetEmptyContent()
    {
        $this->expectException(\MOLiBot\Exceptions\DataSourceRetrieveException::class);

        $this->expectExceptionCode(404);

        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '{}';

        $mock = new MockHandler([
            new Response(404, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliKktix();

        $dataSource->newHttpClient($testClient);

        $dataSource->getContent();
    }

    /**
     *
     * @return void
     * @throws
     */
    public function testMoliGetErrorResponse()
    {
        $this->expectException(\MOLiBot\Exceptions\DataSourceRetrieveException::class);

        $this->expectExceptionCode(502);

        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliKktix();

        $dataSource->newHttpClient($testClient);

        $dataSource->getContent();
    }
}
