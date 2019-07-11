<?php

namespace MOLiBot\RssSources;

use GuzzleHttp\Client as GuzzleHttpClient;

abstract class Source implements SourceInterface
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new GuzzleHttpClient([
            'headers' => [
                'User-Agent' => 'MOLi Bot',
                'Accept-Encoding' => 'gzip',
                'cache-control' => 'no-cache'
            ],
            'timeout' => 10
        ]);
    }

    public function getContent()
    {
        return [];
    }
}