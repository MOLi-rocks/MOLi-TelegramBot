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

        $mockBody = '{"title":"MOLi \u8fd1\u671f\u516c\u958b\u6d3b\u52d5 - KKTIX","updated":"2020-10-02T10:19:35.508+08:00","entry":[{"url":"https://moli.kktix.cc/events/moliday-31","published":"2020-06-24T15:00:00.000+08:00","title":"#MOLiDay #31 - \u8cc7\u8a0a\u5b89\u5168\u5165\u9580\u8207\u5be6\u52d9 (\u9032\u968e)","summary":"\u8b93\u6211\u5011\u651c\u624b\u4e86\u89e3\u8cc7\u5b89\u4e26\u7a7f\u68ad\u5728 #\u8cc7\u8a0a\u6f0f\u6d1e \u4e2d\u5427\uff01","content":"\u6642\u9593\uff1a2020/06/24 15:00(+0800)~18:00\n\u5730\u9ede\uff1a\u7ba1\u7406\u5b78\u9662 237 \u6559\u5ba4 / \u5357\u6295\u7e23\u57d4\u91cc\u93ae\u5927\u5b78\u8def 470 \u865f\u7ba1\u7406\u5b78\u9662 237 \u5ba4","author":{"name":"MOLi","uri":"http://moli.rocks/"}}]}';

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

    public function testMoliGetObjectContent()
    {
        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '{"title":"MOLi \u8fd1\u671f\u516c\u958b\u6d3b\u52d5 - KKTIX","updated":"2020-10-02T10:19:35.508+08:00","entry":{"url":"https://moli.kktix.cc/events/moliday-31","published":"2020-06-24T15:00:00.000+08:00","title":"#MOLiDay #31 - \u8cc7\u8a0a\u5b89\u5168\u5165\u9580\u8207\u5be6\u52d9 (\u9032\u968e)","summary":"\u8b93\u6211\u5011\u651c\u624b\u4e86\u89e3\u8cc7\u5b89\u4e26\u7a7f\u68ad\u5728 #\u8cc7\u8a0a\u6f0f\u6d1e \u4e2d\u5427\uff01","content":"\u6642\u9593\uff1a2020/06/24 15:00(+0800)~18:00\n\u5730\u9ede\uff1a\u7ba1\u7406\u5b78\u9662 237 \u6559\u5ba4 / \u5357\u6295\u7e23\u57d4\u91cc\u93ae\u5927\u5b78\u8def 470 \u865f\u7ba1\u7406\u5b78\u9662 237 \u5ba4","author":{"name":"MOLi","uri":"http://moli.rocks/"}}}';

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

    public function testMoliGetEmptyContent()
    {
        $this->expectException(\MOLiBot\Exceptions\DataSourceRetrieveException::class);

        $this->expectExceptionCode(404);

        $mockHeader = ['Content-Type' => 'application/json; charset=utf-8'];

        $mockBody = '';

        $mock = new MockHandler([
            new Response(200, $mockHeader, $mockBody),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $testClient = new Client(['handler' => $handlerStack]);

        $dataSource = new MoliKktix();

        $dataSource->newHttpClient($testClient);

        $dataSource->getContent();
    }

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
