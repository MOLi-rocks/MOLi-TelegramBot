<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use MOLiBot\Repositories\PublishedNcnuRssRepository;
use SoapBox\Formatter\Formatter;

class NcnuRssService
{
    private $publishedNcnuRssRepository;

    public function __construct(PublishedNcnuRssRepository $publishedNcnuRssRepository)
    {
        $this->publishedNcnuRssRepository = $publishedNcnuRssRepository;
    }

    public function getNcnuRss()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://www.ncnu.edu.tw/ncnuweb/ann/RSS.aspx', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'Accept-Encoding' => 'gzip',
                    'cache-control' => 'no-cache'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }

        $fileContents = $response->getBody()->getContents();

        $formatter = Formatter::make($fileContents, Formatter::XML);

        $json = $formatter->toArray();

        return $json;
    }

    public function checkRssPublished($guid)
    {
        return $this->publishedNcnuRssRepository->checkRssPublished($guid);
    }

    public function storePublishedRss($guid)
    {
        return $this->publishedNcnuRssRepository->storePublishedRss($guid);
    }
}
