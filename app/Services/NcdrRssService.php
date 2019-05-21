<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use MOLiBot\Repositories\PublishedNcdrRssRepository;
use SoapBox\Formatter\Formatter;

class NcdrRssService
{
    private $publishedNcdrRssRepository;

    public function __construct(PublishedNcdrRssRepository $publishedNcdrRssRepository)
    {
        $this->publishedNcdrRssRepository = $publishedNcdrRssRepository;
    }

    public function getNcdrRss()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://alerts.ncdr.nat.gov.tw/RssAtomFeeds.ashx', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
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

    public function checkRssPublished($id)
    {
        return $this->publishedNcdrRssRepository->checkRssPublished($id);
    }

    public function storePublishedRss($id, $category)
    {
        return $this->publishedNcdrRssRepository->storePublishedRss($id, $category);
    }
}